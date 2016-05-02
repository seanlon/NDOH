<?php
// a simple database connection function - returns a mysqli connection object
$dbinfo= array(
"db_host"=>"localhost",
"db_user"=>"seanlohc_NDOH",
"db_pass"=>"abc#123",
"db_name"=>"seanlohc_NDOH",	
);

$GLOBALS['dbinfo'] =$dbinfo; 
function run_sql_file(){
 
}

function connect_db() {


// 	$server = 'localhost'; // e.g 'localhost' or '192.168.1.100'
// 	$user = 'seanlohc_NDOH';
// 	$pass = 'abc#123';
// 	$database = 'seanlohc_NDOH';
	$connection = new mysqli(
	$GLOBALS['dbinfo']['db_host'],$GLOBALS['dbinfo']['db_user'],$GLOBALS['dbinfo']['db_pass'],$GLOBALS['dbinfo']['db_name']); 
	  /* change character set to utf8 */
  if (!$connection->set_charset("utf8")) {
      // printf("Error loading character set utf8: %s\n", $connection->error);
  } else {
      // printf("Current character set: %s\n", $connection->character_set_name());
  }
	return $connection;
}