<?php

declare(strict_types=1);

namespace Lib\Services\Form;

class EditForm
{
	public array $data;
	public array $data_FR;
	public array $rules;
	public array $error;

	public function __construct(array $data_FR)
	{
		$this->data_FR = $data_FR;
		$this->error = [];
	}

	public function isValid(): bool
	{
		$this->cleanData($this->data);
		$this->validate();
		return count($this->error) ? false : true;
	}

	public function setRules(array $rules): void
	{
		$this->rules = $rules;
	}

	public function cleanData($data): void
	{
		foreach ($data as $fieldName => $value) {
			if (!is_array($value))
				$this->data[$fieldName] = $this->cleanField($value);
			else
				$this->data[$fieldName] = $value;
		}
	}

	public function addError(string $fieldName, string $message): void
	{
		$this->error[$fieldName] = $message;
	}

	public function getError(): array
	{
		return $this->error;
	}



	public function validate(): void
	{
		foreach ($this->data as $fieldName => $value) {

			$fieldRules = explode('|', $this->rules[$fieldName]);

			foreach ($fieldRules as $rule) {

				$ruleValue = $this->_getRuleSuffix($rule);
				$rule = $this->_removeRuleSuffix($rule);

				switch ($rule) {
					case 'required':
						if (!is_array($value) && $this->checkEmptyField($value) || (is_array($value) && $this->checkEmptyFiles($value))) {
							$this->addError($fieldName, $this->data_FR[$fieldName] . " est obligatoire");
							break 2;
						}
						break;
					case 'min':
						if ($this->checkMinLength($value, (int) $ruleValue)) {
							$this->addError($fieldName, $this->data_FR[$fieldName] . " doit faire minimum $ruleValue caractères");
							break 2;
						}
						break;
					case 'max':
						if ($this->checkMaxLength($value, (int) $ruleValue)) {
							$this->addError($fieldName, $this->data_FR[$fieldName] . " doit faire maximum $ruleValue caractères");
							break 2;
						}
						break;
					case 'image':
						if ($this->checkMimeTypeFile($value['type'], ['image/jpg', 'image/jpeg', 'image/png'])) {
							$this->addError($fieldName, "L'extension de l'image n'est pas autorisée ! (acceptées : jpg, jpeg et png)");
							break 2;
						}
						break;
					case 'maxSizeFile':
						if ($this->checkSizeFile($value['size'], (int) $ruleValue)) {
							$this->addError($fieldName, "L'image ne doit pas exéder $ruleValue Mb");
							break 2;
						}
						break;
				}
			}
		}
	}

	public function cleanField(string $field): string
	{
		return trim($field);
	}
	public function checkEmptyField(string $field): bool
	{
		return $field == "";
	}

	public function checkEmptyFiles($file): bool
	{
		return $file['error'] === 4;
	}

	public function checkMinLength(string $field, int $minLength): bool
	{
		return strlen($field) < $minLength;
	}

	public function checkMaxLength(string $field, int $maxLength): bool
	{
		return strlen($field) > $maxLength;
	}

	public function checkSizeFile(int $fileSize, int $sizeMax): bool
	{
		return $fileSize > ($sizeMax * 1024 * 1024);
	}

	public function checkMimeTypeFile(string $fileType, array $allowedTypes): bool
	{
		return !in_array($fileType, $allowedTypes);
	}


	public function _removeRuleSuffix(string $string): string
	{
		$arr = explode(':', $string);
		return $arr[0];
	}

	public function _getRuleSuffix($string): string|null
	{
		$arr = explode(":", $string);
		return isset($arr[1]) ? $arr[1] : null;
	}

}