<?php

declare(strict_types=1);

namespace App\Form;

use Lib\Services\Form\EditForm;

class EditUserForm extends EditForm
{
    public const DATA_FR = [
        'username' => 'Le pseudo',
        'firstname' => 'Votre prénom',
        'lastname' => 'Votre nom',
        'email' => 'Votre email',
        'password' => 'Le mot de passe'

    ];

    public function __construct(string $method)
    {
        if ($method == "register") {
            $this->setRulesRegister();
            $this->data = [
                'username' => filter_input(INPUT_POST, 'username'),
                'firstname' => filter_input(INPUT_POST, 'firstname'),
                'lastname' => filter_input(INPUT_POST, 'lastname'),
                'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
                'password' => filter_input(INPUT_POST, 'password')
            ];
        }

        if ($method == "login") {
            $this->setRulesLogin();
            $this->data = [
                'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
                'password' => filter_input(INPUT_POST, 'password')
            ];
        }

        parent::__construct(self::DATA_FR);
    }

    private function setRulesRegister(): void
    {
        $this->setRules(
            [
                'username' => 'required|min:5|max:45|unique:user',
                'firstname' => 'required|min:2|max:45',
                'lastname' => 'required|min:2|max:45',
                'email' => 'required|email|unique:user',
                'password' => 'required|min:8'
            ]
        );
    }

    private function setRulesLogin(): void
    {
        $this->setRules(
            [
                'email' => 'required|email',
                'password' => 'required|min:8'
            ]
        );
    }
}
