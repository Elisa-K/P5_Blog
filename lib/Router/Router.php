<?php

declare(strict_types=1);

namespace Lib\Router;

use TypeError;
use Lib\Router\Route;
use Lib\Exceptions\RouteNotFoundException;

class Router
{
    private array $routes;
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function get(string $path, string $callable): Route
    {
        return $this->add($path, $callable, 'GET');
    }

    public function post($path, $callable): Route
    {
        return $this->add($path, $callable, 'POST');
    }

    public function add(string $path, callable |string $callable, string $method): Route
    {
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;
        return $route;
    }

    public function run(): mixed
    {
        if (isset($this->routes[filter_input(INPUT_SERVER, 'REQUEST_METHOD')])) {
            foreach ($this->routes[filter_input(INPUT_SERVER, 'REQUEST_METHOD')] as $route) {
                if ($route->match($this->url)) {
                    return $route->call();
                }

            }
        }
        throw new RouteNotFoundException();
    }
}