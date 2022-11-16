<?php

declare(strict_types=1);

namespace App\Controllers;

use Lib\Controller;

class ErrorController extends Controller
{
    public function error404()
    {
        $this->view('404.html.twig');
    }
}
