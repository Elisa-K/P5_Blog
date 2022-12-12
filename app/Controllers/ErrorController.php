<?php

declare(strict_types=1);

namespace App\Controllers;

use Lib\Controller;

class ErrorController extends Controller
{
    public function error(int $code): void
    {
        switch ($code) {
            case "404":
                $this->view('404.html.twig');
                break;
            case "403":
                $this->view('403.html.twig');
                break;
        }

    }
}