<?php

declare(strict_types=1);

namespace Lib\Services\Form;

use Lib\Database;

class EditForm
{
    public array $data;
    private array $dataFR;
    private array $rules;
    private array $error;

    public function __construct(array $dataFR)
    {
        $this->dataFR = $dataFR;
        $this->error = [];
    }

    public function isValid(): bool
    {
        $this->cleanData($this->data);
        $this->validate();
        return count($this->error) ? false : true;
    }

    protected function setRules(array $rules): void
    {
        $this->rules = $rules;
    }

    private function cleanData($data): void
    {
        foreach ($data as $fieldName => $value) {
            if (!is_array($value) && !empty($value)) {
                $this->data[$fieldName] = $this->cleanField($value);
            } else {
                $this->data[$fieldName] = $value;
            }
        }
    }

    private function addError(string $fieldName, string $message): void
    {
        $this->error[$fieldName] = $message;
    }

    public function getError(): array
    {
        return $this->error;
    }


    private function validate(): void
    {
        foreach ($this->data as $fieldName => $value) {
            $fieldRules = explode('|', $this->rules[$fieldName]);

            foreach ($fieldRules as $rule) {
                $ruleValue = $this->getRuleSuffix($rule);
                $rule = $this->removeRuleSuffix($rule);
                switch ($rule) {
                    case 'required':
                        if (!is_array($value) && $this->checkEmptyField($value) || (is_array($value) && $this->checkEmptyFiles($value))) {
                            $this->addError($fieldName, $this->dataFR[$fieldName] . " est obligatoire");
                            break 2;
                        }
                        break;
                    case 'min':
                        if ($this->checkMinLength($value, (int) $ruleValue)) {
                            $this->addError($fieldName, $this->dataFR[$fieldName] . " doit faire minimum $ruleValue caractères");
                            break 2;
                        }
                        break;
                    case 'max':
                        if ($this->checkMaxLength($value, (int) $ruleValue)) {
                            $this->addError($fieldName, $this->dataFR[$fieldName] . " doit faire maximum $ruleValue caractères");
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
                    case 'email':
                        if (!$this->checkEmail($value)) {
                            $this->addError($fieldName, "L'email n'est pas valide");
                            break 2;
                        }
                        break;
                    case 'unique':
                        if ($this->checkIsUnique($fieldName, $value, $ruleValue)) {
                            $this->addError($fieldName, $this->dataFR[$fieldName] . " est déjà utilisé");
                            break 2;
                        }
                        break;
                }
            }
        }
    }

    private function cleanField(string $field): string
    {
        return trim($field);
    }
    private function checkEmptyField(string $field): bool
    {
        return $field == "";
    }

    private function checkEmptyFiles($file): bool
    {
        return $file['error'] === 4;
    }

    private function checkMinLength(string $field, int $minLength): bool
    {
        return strlen($field) < $minLength;
    }

    private function checkMaxLength(string $field, int $maxLength): bool
    {
        return strlen($field) > $maxLength;
    }

    private function checkSizeFile(int $fileSize, int $sizeMax): bool
    {
        return $fileSize > ($sizeMax * 1024 * 1024);
    }

    private function checkMimeTypeFile(string $fileType, array $allowedTypes): bool
    {
        return !in_array($fileType, $allowedTypes);
    }

    private function checkEmail(string $email): string|bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    private function checkIsUnique(string $field, string $value, string $table): bool
    {
        $db = new Database();
        $dbConnect = $db->getConnection();
        $stmt = $dbConnect->query("SELECT * FROM $table WHERE $field = '" . $value . "'");
        $row = $stmt->fetchAll();
        return count($row) > 0;
    }

    private function removeRuleSuffix(string $string): string
    {
        $arr = explode(':', $string);
        return $arr[0];
    }

    private function getRuleSuffix($string): string|null
    {
        $arr = explode(":", $string);
        return isset($arr[1]) ? $arr[1] : null;
    }
}
