<?php
ini_set('max_execution_time', 0);
require 'cors.php';
require_once 'database/dbHandler.php';
require 'vendor/autoload.php';

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$c = new \Slim\Container($configuration);

$app = new \Slim\App($c);
require_once "RouteHandler.php";

$app->run();

?>