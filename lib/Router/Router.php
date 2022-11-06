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
		if (!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {
			$this->error404();
			throw new RouteNotFoundException('REQUEST_METHOD does not exist');
		}
		foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
			if ($route->match($this->url)) {
				try {
					return $route->call();
				} catch (TypeError $e) {
					$this->error404();
					throw new TypeError("Error Param URL Type : " . $e->getMessage());
				}
			}
		}
		$this->error404();
		throw new RouteNotFoundException('No matching routes');
	}

	public function error404(): void
	{
		$controller = new \Lib\Controller;
		$controller->view('404.html.twig');
	}
}