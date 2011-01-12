<?php
error_reporting(0);

session_start();

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

// database connection settings
$server   = 'localhost';
$username = 'root';
$password = 'root';
$database = 'syslog';

// Your syslog tables for services logged.
$tables = array('messages','mail','auth','cron','local1','local2','local3','local4','local5','local6','local7','local0');

// Getting all hosts uses DISTINCT on each of you tables (only on the initial page load).
// If you have a HUGE db (Yes YOU) uncomment the following line and add your hosts.
// $hosts = array('misa','hell','local', 'null');

// DN
$fqdns = array(
	'10.10.0.2' => 'Cisco 876',
	'DC1' => 'Domain Controller'
);


$ldap = array(
	'server' => 'ldaps://domain.com',
	'port' => 636,
	'top' => 'dc=domain,dc=com'
);