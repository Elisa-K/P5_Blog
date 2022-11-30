<?php

declare(strict_types=1);

namespace Lib;

use PDO;
use Twig\Environment;
use Lib\Services\FlashMessage;
use Twig\Loader\FilesystemLoader;

class Controller
{
    private ?PDO $dbConnect;
    private array $messages = [];
    public function __construct()
    {
        $db = new Database();
        $this->dbConnect = $db->getConnection();
        if (!isset($_SESSION['messages'])) {
            $_SESSION['messages'] = [];
        }
    }

    public function getDatabase(): ?PDO
    {
        return $this->dbConnect;
    }

    public function view(string $path, array $datas = []): void
    {
        $loader = new FilesystemLoader('../ressources/views');
        $twig = new Environment(
            $loader,
            [
                'debug' => true,
                'cache' => false,
            ]
        );
        $twig->addGlobal('flashMessage', new FlashMessage($_SESSION['messages']));
        $twig->addExtension(new \Twig\Extension\DebugExtension());
        print_r($twig->render($path, $datas));
    }

    public function isSubmit(): bool
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    public function addFlashMessage(array $message)
    {
        array_push($_SESSION['messages'], $message);
    }
}
