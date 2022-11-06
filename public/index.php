<?php


use Lib\Router\Router;

require_once("../vendor/autoload.php");


$router = new Router($_GET['url']);

// Routes
$router->get('/', "HomeController#index");



$router->run();