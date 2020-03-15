<?php

define('BASE_PATH',    __DIR__ . '/application/');
define('BASE_PUBLIC',  __DIR__ . '/public/');

include_once BASE_PATH . 'App.php';
include_once BASE_PATH . 'Controller.php';

$app = new App();
$app->run();


