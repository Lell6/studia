<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//phpinfo();

use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

require __DIR__ . '/routes.php';
$routes = require __DIR__ . '/routes.php';
$routes($app);

$app->run();