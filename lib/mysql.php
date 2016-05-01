<?php
// a simple database connection function - returns a mysqli connection object
$dbinfo= array(
"db_host"=>"localhost",
"db_user"=>"seanlohc_ndoh",
"db_pass"=>"abc#123",
"db_name"=>"seanlohc_ndoh",	
);

$GLOBALS['dbinfo'] =$dbinfo; 
function run_sql_file(){
 
}

function connect_db() {


// 	$server = 'localhost'; // e.g 'localhost' or '192.168.1.100'
// 	$user = 'seanlohc_ndoh';
// 	$pass = 'abc#123';
// 	$database = 'seanlohc_ndoh';
	$connection = new mysqli(
	$GLOBALS['dbinfo']['db_host'],$GLOBALS['dbinfo']['db_user'],$GLOBALS['dbinfo']['db_pass'],$GLOBALS['dbinfo']['db_name']); 
	return $connection;
}