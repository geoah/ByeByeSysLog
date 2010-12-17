<?php

require_once('config.php');
require_once('json.php');

if(@$_GET['host']) $host = $_GET['host']; else $host = null;


// connect to database
mysql_pconnect($server, $username, $password) or die("Could not connect");
mysql_select_db($database) or die("Could not select database");

// keep only tables that actually exist and we have access to.
$tempTables = $tables;
$tables = array();
$existingTablesList = mysql_list_tables($database);
while(list($existingTableName) = mysql_fetch_array($existingTablesList)){
	$existingTables[] = $existingTableName;
}
foreach($tempTables as &$table){
	if(in_array($table, $existingTables))
		$tables[] = $table;
}
unset($tempTables, $existingTablesList, $existingTableName);

// find all different hosts from tables.
if(!isset($hosts) || !empty($hosts)){
	$hosts = array();
	foreach($tables as $table){
		$query = "SELECT DISTINCT(host) host FROM {$table}";
		$rs = mysql_query($query);
		while (list($host) = mysql_fetch_array($rs)) {
			$hosts[$host][] = $table;
		}
	}
}

$tree = array();

$node = array(
	'host'=>'',
	'table'=>'',
	'text'=>'All hosts',
	'fulltext'=>'All hosts',
	'leaf'=>false,
	'expanded'=>true
);
foreach($tables as $feature){
	$node['children'][] = array(
		'host'=>'',
		'table'=>$feature,
		'text'=>$feature,
		'fulltext'=>'All hosts: '.$feature,
		'leaf'=>true,
		'icon'=>'resources/icons/'.$feature.'.png'
	);
}
$tree[] = $node;

foreach($hosts as $host=>$features){
	$fqdn = @$fqdns[$host] ? $fqdns[$host] : $host;
	$node = array(
		'host'=>$host,
		'text'=>$fqdn,
		'fulltext'=>$fqdn,
		'expanded'=>false,
		'leaf'=>false
	);
	foreach($features as $feature){
		$node['children'][] = array(
			'host'=>$host,
			'table'=>$feature,
			'text'=>$feature,
			'fulltext'=>$fqdn.': '.$feature,
			'leaf'=>true,
			'icon'=>'resources/icons/'.$feature.'.png'
		);
	}
	$tree[] = $node;
}
// return response to client
echo json_encode($tree);
