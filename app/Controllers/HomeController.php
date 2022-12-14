<?php

declare(strict_types=1);

namespace App\Controllers;

use Lib\Controller;
use Lib\Services\Mail;
use App\Form\EditMailForm;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('front_office/homepage.html.twig', ['route' => '/']);
    }
    public function sendMail(): void
    {
        $mailForm = new EditMailForm();

        if ($this->isSubmit() && $mailForm->isValid()) {
            $mail = new Mail();

            if ($mail->sendMail($mailForm->data['email'], $mailForm->data['firstname'], $mailForm->data['lastname'], $mailForm->data['message'])) {
                $this->addFlashMessage(["success" => "Votre message a bien été envoyé"]);
            } else {
                $this->addFlashMessage(["danger" => "Une erreur s'est produite. Votre message n'a pu être envoyé"]);
            }

            $this->redirect('/#contact');
        }

        $this->view('front_office/homepage.html.twig', ['route' => '/#contact', 'errors' => $mailForm->getError(), 'contact' => $mailForm->data]);
    }
}
