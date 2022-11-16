<?php

declare(strict_types=1);

namespace Lib\Services;

class FormValidator
{
	public function checkTextLength(string $text, string $name, int $minLength, ?int $maxLength): string|bool
	{
		if (strlen($text) < $minLength) {
			return "Le $name doit faire minimum $minLength caractères.";
		}
		if ($maxLength != null && strlen($text) > $maxLength) {
			return "Le $name ne doit pas exéder $maxLength caractères.";
		}
		return false;
	}
}