<?php
//slim framework example from http://scottnelle.com/
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();


//import api functions
require('apiFunctions.php');
$GLOBALS['pathapi'] = "/v2"; 
require('apiV1.php');
require('apiV2.php');
$app->run();