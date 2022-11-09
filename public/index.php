<?php


use Lib\Router\Router;
use App\Controllers\ErrorController;
use Symfony\Component\Dotenv\Dotenv;

require_once "../vendor/autoload.php";
// $dotenv = new Dotenv();
// $dotenv->load(__DIR__ . '/../.env');

$router = new Router(filter_input(INPUT_GET, 'url'));

// Routes
    // Front-Office
$router->get('/', "HomeController#index");

    // Back-Office (admin)
$router->get('/dashboard', "HomeController#dashboard");

try {
    $router->run();
} catch (TypeError $e) {
    $errorController = new ErrorController();
    $errorController->error404();
}
