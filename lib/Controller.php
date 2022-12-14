<?php

declare(strict_types=1);

namespace Lib;

use PDO;
use Twig\Environment;
use Lib\Services\{FlashMessage, SessionManager};
use Twig\Loader\FilesystemLoader;
use Lib\Exceptions\{AccessDeniedException, UnauthorizedException};

class Controller
{
    private ?PDO $dbConnect;
    public ?SessionManager $session;
    public FlashMessage $flashMessage;

    public function __construct()
    {
        $db = new Database();
        $this->dbConnect = $db->getConnection();
        $this->session = new SessionManager();
        if (!$this->session->has('messages')) {
            $this->session->set('messages', array());
        }
        $this->flashMessage = new FlashMessage($_SESSION['messages']);
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

        $twig->addGlobal('flashMessage', $this->flashMessage);
        $twig->addGlobal('sessionUser', $this->session->get('user'));
        $twig->addExtension(new \Twig\Extension\DebugExtension());
        print_r($twig->render($path, $datas));
    }

    public function redirect(string $path)
    {
        header('Location: ' . $path);
        exit();
    }

    public function isSubmit(): bool
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    public function checkUserConnect(): void
    {
        if (!$this->session->has('user')) {
            throw new UnauthorizedException();
        }
    }
    public function checkIsAdmin(): void
    {
        $this->checkUserConnect();
        if (!(bool) $this->session->get('user')->isAdmin) {
            throw new AccessDeniedException();
        }
    }

    public function addFlashMessage(array $message): void
    {
        $this->flashMessage->add($message);
    }
}
