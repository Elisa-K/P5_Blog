<?php

declare(strict_types=1);

namespace Lib\Services\Form;

class EditPostForm extends EditForm
{

	const data_FR = [
		'title' => 'Le titre',
		'excerpt' => 'Le chapô',
		'content' => 'Le contenu de l\'article',
		'featured-img' => 'L\'image à la une'
	];
	public function __construct(string $method)
	{
		if ($method == "add")
			$this->setRulesAdd();
		if ($method == "update")
			$this->setRulesUpdate();
		parent::__construct(self::data_FR);

		$this->data = [
			'title' => filter_input(INPUT_POST, 'title'),
			'excerpt' => filter_input(INPUT_POST, 'excerpt'),
			'content' => filter_input(INPUT_POST, 'content')
		];
		if ($_FILES['featured-img']['error'] != 4) {
			$this->data['featured-img'] = $_FILES['featured-img'];
		}
	}

	public function setRulesAdd()
	{
		$this->setRules(
			[
				'title' => 'required|min:5|max:255',
				'excerpt' => 'required|min:5',
				'content' => 'required|min:25',
				'featured-img' => 'required|image|maxSizeFile:5'
			]
		);
	}

	public function setRulesUpdate()
	{
		$this->setRules(
			[
				'title' => 'required|min:5|max:255',
				'excerpt' => 'required|min:5',
				'content' => 'required|min:25',
				'featured-img' => 'image|maxSizeFile:5'
			]
		);
	}
}