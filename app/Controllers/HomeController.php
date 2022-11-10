<?php

declare(strict_types=1);

namespace App\Controllers;

use Lib\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('front_office/homepage.html.twig', ['route' => '/']);
    }

    public function dashboard(): void
    {
        $this->view('back_office/dashboard.html.twig', ['route' => '/dashboard']);
    }
}