<?php

use Http\Factory\Guzzle\ServerRequestFactory;
use Mailer\Router;
use Narrowspark\HttpEmitter\SapiEmitter;

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required(['APP_URL', 'MYSQL_DB_HOST', 'MYSQL_DB_PORT', 'MYSQL_DB_NAME', 'MYSQL_DB_USER', 'MYSQL_DB_PASSWORD']);

$router = new Router();

$requestFactory = new ServerRequestFactory();
$request = $requestFactory->createServerRequest($_SERVER['REQUEST_METHOD'], $_SERVER["REQUEST_URI"], $_SERVER);
$response = $router->handleRequest($request);

$emitter = new SapiEmitter();
$emitter->emit($response);