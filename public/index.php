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
$router->get('/blog', "BlogController#allPosts");
$router->get('/blog/post/:id', "BlogController#getPost");

// Back-Office (admin)
$router->get('/dashboard', "BackOfficeController#dashboard");
$router->get('/dashboard/posts', 'BackOfficeController#allPosts');
$router->get('/dashboard/newpost', 'BackOfficeController#addPost');
$router->post('/dashboard/newpost', 'BackOfficeController#addPost');

try {
    $router->run();
} catch (TypeError $e) {
    $errorController = new ErrorController();
    $errorController->error404();
}