<?php

require_once('config.php');
require_once('json.php');

$table = @$_GET['table'];
if(!@$table) die();
if(@$_GET['host'] && @count($_GET['host'])>0) $host = $_GET['host']; else $host = null;

// connect to database
mysql_pconnect($server, $username, $password) or die("Could not connect");
mysql_select_db($database) or die("Could not select database");

// collect request parameters
$start  = isset($_REQUEST['start'])  ? $_REQUEST['start']  :  0;
$count  = isset($_REQUEST['limit'])  ? $_REQUEST['limit']  : 20;
$sort   = isset($_REQUEST['sort'])   ? $_REQUEST['sort']   : '';
$dir    = isset($_REQUEST['dir'])    ? $_REQUEST['dir']    : 'ASC';

$query  = isset($_REQUEST['query'])    ? $_REQUEST['query']    : null;
$host   = isset($_REQUEST['host'])    ? $_REQUEST['host']    : null;

$where = '0=0';

function sql_filter_last($value){
	return date('Y-m-d H:i:s', strtotime('now -' . $value));
}

if($query){
	$whereParts = array();
	$query = trim($query);
	$keys = array(
		'host'=>array('mod'=>'=', 'wildcard'=>true),
		'facility'=>array('mod'=>'=', 'wildcard'=>true),
		'level'=>array('mod'=>'=', 'wildcard'=>true),
		'datetime'=>array('mod'=>'=', 'wildcard'=>true),
		'program'=>array('mod'=>'=', 'wildcard'=>true),
		'pid'=>array('mod'=>'=', 'wildcard'=>true),
		'msg'=>array('mod'=>'LIKE', 'wildcard'=>true, 'prepend'=>'*', 'append'=>'*'),
		'last'=>array('mod'=>'>=', 'column'=>'datetime', 'wildcard'=>false, 'function'=>'sql_filter_last')
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
echo '{"sql":"'.$sql.'","total":"'.$total.'","data":'.json_encode($arr).'}';
