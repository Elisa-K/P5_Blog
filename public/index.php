<?php


use Lib\Router\Router;
use Symfony\Component\Dotenv\Dotenv;

require_once("../vendor/autoload.php");
$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

$router = new Router($_GET['url']);

// Routes
$router->get('/', "HomeController#index");



$router->run();