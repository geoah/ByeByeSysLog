<?php

require_once('bootstrap.php');

$table = @$_GET['table'];
if(!@$table) die();
if(@$_GET['host'] && @count($_GET['host'])>0) $host = $_GET['host']; else $host = null;

// connect to database
mysql_pconnect($config['mysql']['server'], $config['mysql']['username'], $config['mysql']['password']) or die("Could not connect");
mysql_select_db($config['mysql']['database']) or die("Could not select database");

// collect request parameters
$start  = isset($_REQUEST['start'])  ? $_REQUEST['start']  :  0;
$count  = isset($_REQUEST['limit'])  ? $_REQUEST['limit']  : 50;
$sort   = isset($_REQUEST['sort'])   ? $_REQUEST['sort']   : 'id';
$dir    = isset($_REQUEST['dir'])    ? $_REQUEST['dir']    : 'DESC';

$query  = isset($_REQUEST['query'])    ? $_REQUEST['query']    : null;
$host   = isset($_REQUEST['host'])    ? $_REQUEST['host']    : null;

$where = '0=0';

function sql_filter_last($value){
	$value = strtotime('now -' . trim($value));
	if($value==false){
		return false;
	}
	return date('Y-m-d H:i:s', $value);
}
function sql_filter_fromto($value){
	$value = strtotime(trim($value));
	if($value==false){
		return false;
	}
	return date('Y-m-d H:i:s', $value);
}
function sql_filter_date($value){
	$value = strtotime(trim($value));
	if($value==false){
		return false;
	}
	return date('Y-m-d', $value).' %';
}

if($query){
	$whereParts = array();
	$query = trim($query);
	$keys = array(
		'host'=>array('mod'=>'=', 'wildcard'=>true),
		'facility'=>array('mod'=>'=', 'wildcard'=>true),
		'level'=>array('mod'=>'=', 'wildcard'=>true),
		'datetime'=>array('mod'=>'=', 'wildcard'=>true),
		'date'=>array('mod'=>'LIKE', 'column'=>'datetime', 'wildcard'=>true, 'function'=>'sql_filter_date'),
		'program'=>array('mod'=>'=', 'wildcard'=>true),
		'pid'=>array('mod'=>'=', 'wildcard'=>true),
		'msg'=>array('mod'=>'LIKE', 'wildcard'=>true, 'prepend'=>'*', 'append'=>'*'),
		'last'=>array('mod'=>'>=', 'column'=>'datetime', 'wildcard'=>false, 'function'=>'sql_filter_last'),
		'from'=>array('mod'=>'>=', 'column'=>'datetime', 'wildcard'=>false, 'function'=>'sql_filter_fromto'),
		'to'=>array('mod'=>'<=', 'column'=>'datetime', 'wildcard'=>false, 'function'=>'sql_filter_fromto'),
	);
	
	$positions = array();
	foreach(array_keys($keys) as $key){
		$position = strpos($query, $key.':');
		if($position!==false){
			$positions[$key] = $position;
		}
	}
	arsort($positions);
	
	foreach($positions as $key=>$position){
		$pair = substr($query, $position);
		$query = trim(str_replace($pair, '', $query));
		$pairs[$key] = trim(str_replace($key.':', '', $pair));
	}
	
	foreach($pairs as $key=>$value){
		if(@$keys[$key]['function']){
			$value = $keys[$key]['function']($value);
		}
		if($value===false){
			continue;
		}
		if(@$keys[$key]['column']){
			$column = $keys[$key]['column'];
		}else{
			$column = $key;
		}
		$value = @$keys[$key]['prepend'] . $value . @$keys[$key]['append'];
		$allowWildcard = $keys[$key]['wildcard'];
		if(($allowWildcard==true)&&(strstr($value, '*'))){
			$mod = 'LIKE';
			$value = str_replace('*', ' ', $value);
			$value = ereg_replace('[ ]+', ' ', $value);
			$value = str_replace(' ', '%', $value);
		}else{
			$mod = $keys[$key]['mod'];
		}
		switch($mod){
			case '=':
				$whereParts[] = "`{$column}`='{$value}'";
				break;
			case 'LIKE':
				$whereParts[] = "`{$column}` LIKE '{$value}'";
				break;
			case '>=':
			case '<=':
			case '>':
			case '<':
				$whereParts[] = "`{$column}` {$keys[$key]['mod']} '{$value}'";
				break;
		}
	}
	
	$where .= ' AND ' . implode(' AND ', $whereParts);
	
	if(@$config['sphinx']['enabled']==true && count($pairs)==0 && @$query){
		include('libs/sphinx/sphinxapi.php');
		
		// create Sphinx client
		$sphx = new SphinxClient();
		
		// set server and search parameters
		$sphx->setServer($config['sphinx']['server'], $config['sphinx']['port']);
		$sphx->setLimits(0, 500, 1000);
		$sphx->setMatchMode(SPH_MATCH_ALL);
		$sphx->setSortMode(SPH_SORT_ATTR_DESC, 'datetime');
		
		// get and run query from command-line
		$result = $sphx->query($query, 'idx_logs,idx_delta_logs');
		//echo $result['total_found'] . " hit(s) \n\n";
		
		// get document IDs
		$ids = implode(',', array_keys($result['matches']));
		$where = ' id IN ('.$ids.')';
	}
}

if($host) $where .= " AND host = '".$host."'";

// query the database
$sql = "SELECT * FROM {$table} WHERE " . $where;
if ($sort != "") {
    $sql .= " ORDER BY ".$sort." ".$dir;
}
$sql .= " LIMIT ".$start.",".$count;

$rs = mysql_query($sql);
$total = mysql_query("SELECT COUNT(pid) FROM {$table} WHERE ".$where);
$total = mysql_result($total, 0, 0);

$i = 0;
$arr = array();
while($row = mysql_fetch_assoc($rs)) {
	$encodedRow = array();
	foreach($row as $key=>$value){
		$encodedRow[$key] = htmlentities($value);
	}
	$arr[] = $encodedRow;
}

// return response to client
echo '{"version":"1","sql":"'.$sql.'","total":"'.$total.'","data":'.json_encode($arr).'}';
