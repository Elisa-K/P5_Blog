<?php
declare(strict_types=1);
namespace Lib;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Controller
{

	public function view(string $path, array $datas = []): void
	{
		$loader = new FilesystemLoader('../ressources/views');
		$twig = new Environment($loader, [
			'cache' => false,
		]
			);
		echo $twig->render($path, $datas);
		return;
	}
}