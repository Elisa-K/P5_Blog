<?php

declare(strict_types=1);

namespace App\Form;

use Lib\Services\Form\EditForm;

class EditPostForm extends EditForm
{
    public const dataFR = [
        'title' => 'Le titre',
        'excerpt' => 'Le chapô',
        'content' => 'Le contenu de l\'article',
        'featuredImg' => 'L\'image à la une'
    ];
    public function __construct(string $method)
    {
        if ($method == "add") {
            $this->setRulesAdd();
        }
        if ($method == "update") {
            $this->setRulesUpdate();
        }
        parent::__construct(self::dataFR);

        $this->data = [
            'title' => filter_input(INPUT_POST, 'title'),
            'excerpt' => filter_input(INPUT_POST, 'excerpt'),
            'content' => filter_input(INPUT_POST, 'content')
        ];
        if (isset($_FILES['featured-img']) && $_FILES['featured-img']['error'] != 4) {
            $this->data['featuredImg'] = $_FILES['featured-img'];
        }
    }

    private function setRulesAdd()
    {
        $this->setRules(
            [
                'title' => 'required|min:5|max:255',
                'excerpt' => 'required|min:5',
                'content' => 'required|min:25',
                'featuredImg' => 'required|image|maxSizeFile:5'
            ]
        );
    }

    private function setRulesUpdate()
    {
        $this->setRules(
            [
                'title' => 'required|min:5|max:255',
                'excerpt' => 'required|min:5',
                'content' => 'required|min:25',
                'featuredImg' => 'image|maxSizeFile:5'
            ]
        );
    }
}