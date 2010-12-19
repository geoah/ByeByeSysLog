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
$filters = isset($_REQUEST['filter']) ? $_REQUEST['filter'] : null;

// GridFilters sends filters as an Array if not json encoded
if (is_array($filters)) {
    $encoded = false;
} else {
    $encoded = true;
    $filters = json_decode($filters);
}

// initialize variables
$where = ' 0 = 0 ';
$qs = '';

// loop through filters sent by client
if (is_array($filters)) {
    for ($i=0;$i<count($filters);$i++){
        $filter = $filters[$i];

        // assign filter data (location depends if encoded or not)
        if ($encoded) {
            $field = $filter->field;
            $value = $filter->value;
            $compare = isset($filter->comparison) ? $filter->comparison : null;
            $filterType = $filter->type;
        } else {
            $field = $filter['field'];
            $value = $filter['data']['value'];
            $compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
            $filterType = $filter['data']['type'];
        }
        
        switch($filterType){
            case 'string' : $qs .= " AND ".$field." LIKE '%".$value."%'"; Break;
            case 'list' :
                if (strstr($value,',')){
                    $fi = explode(',',$value);
                    for ($q=0;$q<count($fi);$q++){
                        $fi[$q] = "'".$fi[$q]."'";
                    }
                    $value = implode(',',$fi);
                    $qs .= " AND ".$field." IN (".$value.")";
                }else{
                    $qs .= " AND ".$field." = '".$value."'";
                }
            Break;
            case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
            case 'numeric' :
                switch ($compare) {
                    case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
                    case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
                    case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
                }
            Break;
            case 'date' :
                switch ($compare) {
                    case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
                    case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
                    case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
                }
            Break;
        }
    }
    $where .= $qs;
}

if($host) $where .= " AND host = '".$host."'";

// query the database
$query = "SELECT * FROM {$table} WHERE ".$where;
if ($sort != "") {
    $query .= " ORDER BY ".$sort." ".$dir;
}
$query .= " LIMIT ".$start.",".$count;

$rs = mysql_query($query);
$total = mysql_query("SELECT COUNT(pid) FROM {$table} WHERE ".$where);
$total = mysql_result($total, 0, 0);

$i = 0;
$arr = array();
while($row = mysql_fetch_assoc($rs)) {
	$row['id'] = $start + $i++;
	$encodedRow = array();
	foreach($row as $key=>$value){
		$encodedRow[$key] = htmlentities($value);
	}
	$arr[] = $encodedRow;
}

// return response to client
echo '{"total":"'.$total.'","data":'.json_encode($arr).'}';
