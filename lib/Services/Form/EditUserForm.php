<?php

declare(strict_types=1);

namespace Lib\Services\Form;

class EditUserForm extends EditForm
{
	public const dataFR = [
		'username' => 'Le pseudo',
		'firstname' => 'Votre prénom',
		'lastname' => 'Votre nom',
		'email' => 'Votre email',
		'password' => 'Le mot de passe'

	];
	public function __construct()
	{

		parent::__construct(self::dataFR);

		$this->data = [
			'username' => filter_input(INPUT_POST, 'username'),
			'firstname' => filter_input(INPUT_POST, 'firstname'),
			'lastname' => filter_input(INPUT_POST, 'lastname'),
			'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
			'password' => filter_input(INPUT_POST, 'password')
		];

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
}