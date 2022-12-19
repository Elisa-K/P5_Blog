<?php

declare(strict_types=1);

namespace App\Form;

use Lib\Services\Form\EditForm;

class EditMailForm extends EditForm
{
    public const DATA_FR = [
        'firstname' => 'Votre prénom',
        'lastname' => 'Votre nom',
        'email' => 'Votre email',
        'message' => 'Votre message'
    ];

    public function __construct()
    {
        $this->setRules(
            [
                'firstname' => 'required|min:2|max:45',
                'lastname' => 'required|min:2|max:45',
                'email' => 'required|email',
                'message' => 'required|min: 20'
            ]
        );

        $this->data = [
            'firstname' => filter_input(INPUT_POST, 'firstname'),
            'lastname' => filter_input(INPUT_POST, 'lastname'),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'message' => filter_input(INPUT_POST, 'message')
        ];

        parent::__construct(self::DATA_FR);
    }
}
