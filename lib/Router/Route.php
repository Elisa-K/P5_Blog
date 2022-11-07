<?php
declare(strict_types=1);
namespace Lib\Router;

class Route
{
	private $path;
	private $callable;
	private $matches = [];
	private $params = [];

	public function __construct($path, $callable)
	{
		$this->path = trim($path, '/');
		$this->callable = $callable;
	}

	public function match (string $url): bool
	{
		$url = trim($url, '/');
		$path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);
		$regex = "#^$path$#i";
		if (!preg_match($regex, $url, $matches)) {
			return false;
		}
		array_shift($matches);
		$this->matches = $matches;
		return true;
	}

	private function paramMatch(array $match): string
	{
		if (isset($this->params[$match[1]])) {
			return '(' . $this->params[$match[1]] . ')';
		}
		return '([^/]+)';
	}

	public function call(): void
	{
		if (is_string($this->callable)) {
			$params = explode('#', $this->callable);
			$controller = "App\\Controllers\\" . $params[0];
			$controller = new $controller();
			call_user_func_array([$controller, $params[1]], $this->matches);
		} else {
			call_user_func_array($this->callable, $this->matches);
		}
	}
}