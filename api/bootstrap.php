<?php

error_reporting(E_ALL);
ini_set("display_errors","On");

@session_start();

header('Content-Type: text/html;charset=utf-8');
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin');

define('BASE_PATH', __DIR__);

define('SESSION_PREFIX', 'watsons_');
define('API_SECRET', 'ysDFD34iu7JHJS');

require BASE_PATH.'/vendor/autoload.php';
require BASE_PATH.'/config.php';
require BASE_PATH.'/libs/MysqliDb.php';
require BASE_PATH.'/libs/helper.php';


$db = new MysqliDb ([
    'host' => '47.100.12.3',
    'username' => 'www', 
    'password' => 'erLQ5xtxT8sPIatoYmyYEqon',
    'db'=> 'watsons',
    'port' => 3306,
    'prefix' => '',
    'charset' => 'utf8mb4'
]);
/*$db = new MysqliDb ([
    'host' => '127.0.0.1',
    'username' => 'root',
    'password' => '2000',
    'db'=> 'watsons',
    'port' => 3306,
    'prefix' => '',
    'charset' => 'utf8mb4'
]);*/

