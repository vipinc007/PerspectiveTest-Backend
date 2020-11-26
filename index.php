<?php
ini_set('max_execution_time', 0);
require_once 'database/dbHandler.php';
require 'vendor/autoload.php';
// require 'middleware/cors.php';
$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$c = new \Slim\Container($configuration);

$app = new \Slim\App($c);
require_once "RouteHandler.php";
// Specify domains from which requests are allowed
header('Access-Control-Allow-Origin: *');

// Specify which request methods are allowed
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

// Additional headers which may be sent along with the CORS request
// The X-Requested-With header allows jQuery requests to go through
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Set the age to 1 day to improve speed/caching.
header('Access-Control-Max-Age: 86400');
$app->run();

?>