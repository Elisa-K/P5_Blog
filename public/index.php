<?php

use Lib\Router\Router;
use App\Controllers\ErrorController;


require_once "../vendor/autoload.php";


$router = new Router(filter_input(INPUT_GET, 'url'));

// Routes
// Front-Office
$router->get('/', "HomeController#index");
$router->post('/sendmail', "HomeController#sendMail");
$router->get('/blog', "BlogController#allPosts");
$router->get('/blog/post/:id', "BlogController#getPost");
$router->post('/blog/post/addComment/:id', "BlogController#addComment");
$router->get('/signup', 'BlogController#signUp');
$router->post('/signup', 'BlogController#signUp');
$router->get('/signin', 'BlogController#signIn');
$router->post('/signin', 'BlogController#signIn');
$router->get('/logout', 'BlogController#logOut');

// Back-Office (admin)

$router->get('/dashboard', "BackOfficeController#dashboard");
$router->get('/dashboard/posts', 'BackOfficeController#allPosts');
$router->get('/dashboard/newpost', 'BackOfficeController#addPost');
$router->post('/dashboard/newpost', 'BackOfficeController#addPost');
$router->get('/dashboard/updatepost/:id', 'BackOfficeController#updatePost');
$router->post('/dashboard/updatepost/:id', 'BackOfficeController#updatePost');
$router->get('/dashboard/deletepost/:id', 'BackOfficeController#deletePost');
$router->get('/dashboard/moderation', 'BackOfficeController#allCommentsToModerate');
$router->get('/dashboard/validcomment/:id', 'BackOfficeController#validateComment');
$router->get('/dashboard/deletecomment/:id', 'BackOfficeController#deleteComment');
$router->get('/dashboard/users', 'BackOfficeController#allUser');
$router->get('/dashboard/allowpermissionadmin/:id', 'BackOfficeController#allowPermissionAdmin');
$router->get('/dashboard/denypermissionadmin/:id', 'BackOfficeController#denyPermissionAdmin');

// try {
//     $router->run();
// } catch (TypeError $e) {
//     $errorController = new ErrorController();
//     $errorController->error404();
// }

$router->run();