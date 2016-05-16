<?php
$filename = '/home/seanlohc/public_html/NDOH/log/log-' . date('Ymd');
// $filename='log/log-'.date('Ymd'); 
$fpLog = fopen($filename, "c") or die('Cannot open file: ' . $filename);
// var_dump($fpLog);
fseek($fpLog, -1, SEEK_END);
$logWriter = new \Slim\LogWriter($fpLog);

