<?php 
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

// //import api log  writer
// require('apiLogConfig.php');
// $app = new \Slim\Slim(  array( 'debug' => true, 'log.enabled' => true , 'log.level' => \Slim\Log::DEBUG , 'log.writer' => $logWriter) );  

$app = new \Slim\Slim();
 
// //import api logging features
//  require('apiLogFunctions.php');


//import api functions 
$GLOBALS['pathapi'] = "/v2"; 
require_once 'lib/mysql.php';
require_once 'apiV1.php' ;
require_once 'apiV2.php' ;
$app->run();