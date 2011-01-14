<?php
session_start();

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

error_reporting(E_ALL);

if(file_exists('config.ini')){
	$config = parse_ini_file('config.ini', true);
	if(!is_array($config)){
		die('<hr />Config file error');
	}
}else{
	die('Config file missing');
}

error_reporting(0);

// Check if json_encode and json_decode exist and if not import the 
// json class and create them.
if(!function_exists('json_decode')){
	function json_decode($content, $assoc=false){
		require_once 'libs/json/json.class.php';
		if($assoc){
			$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
		}else{
			$json = new Services_JSON;
		}
		return $json->decode($content);
	}
}

if(!function_exists('json_encode')){
	function json_encode($content){
		require_once 'libs/json/json.class.php';
		$json = new Services_JSON;
		return $json->encode($content);
	}
}

if(@$config['ldap']['enabled']){
	require_once('auth.php');
}