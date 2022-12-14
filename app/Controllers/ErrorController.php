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
                $this->view('front_office/404.html.twig');
                break;
            case "403":
                $this->view('front_office/403.html.twig');
                break;
        }

    }
}