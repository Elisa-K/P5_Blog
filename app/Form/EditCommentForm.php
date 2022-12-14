<?php

declare(strict_types=1);

namespace App\Form;

use Lib\Services\Form\EditForm;

class EditCommentForm extends EditForm
{
    public const data_FR = ['comment' => 'Le commentaire'];

    public function __construct()
    {
        $this->setRules(['comment' => 'required|min:5']);
        parent::__construct(self::data_FR);
        $this->data = [
            'comment' => filter_input(INPUT_POST, 'comment')
        ];
    }
}