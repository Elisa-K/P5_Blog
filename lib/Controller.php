<?php

declare(strict_types=1);

namespace Lib;


use PDO;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Controller
{
    private ? PDO $dbConnect;
    public function __construct()
    {
        $db = new Database();
        $this->dbConnect = $db->getConnection();
    }

    public function getDatabase(): ? PDO
    {
        return $this->dbConnect;
    }

    public function view(string $path, array $datas = []): void
    {
        $loader = new FilesystemLoader('../ressources/views');
        $twig = new Environment(
            $loader,
            [
                'cache' => false,
            ]
            );
        print_r($twig->render($path, $datas));
    }
}