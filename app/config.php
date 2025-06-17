<?php

$url = $_SERVER["SERVER_NAME"];
$last = substr($url, -1); 
$dab = "$url[0]$url[1]$url[2]$url[3]$url[4]$last" ; 

define('PATH', realpath('.'));
define('SUBFOLDER', false);
define('URL', "https://$url");
define('STYLESHEETS_URL', "//$url");
error_reporting(1);
date_default_timezone_set('America/Sao_Paulo');

return [
  'db' => [
    'name'    =>  "spot",
    'host'    =>  'localhost',
    'user'    =>  "spot",
    'pass'    =>  "Ozd048p!6",
    'charset' =>  'utf8mb4' 
  ]
];

