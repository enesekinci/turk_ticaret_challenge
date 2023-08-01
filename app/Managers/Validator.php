<?php

namespace App\Managers;

use InvalidArgumentException;

class Validator
{
    public function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $rulesArray = explode('|', $rule);

            foreach ($rulesArray as $singleRule) {
                list($ruleName, $ruleValue) = $this->getRuleNameAndValue($singleRule);

                $validator = $this->createValidator($ruleName);
                $error = $validator->validate($data, $field, $ruleValue);

                if ($error) {
                    $errors[$field][] = $error;
                }
            }
        }

        return $errors;
    }

    private function getRuleNameAndValue(string $rule): array
    {
        $ruleParts = explode(':', $rule);
        $ruleName = $ruleParts[0];
        $ruleValue = $ruleParts[1] ?? null;

        return [$ruleName, $ruleValue];
    }

    private function createValidator(string $ruleName): ValidatorInterface
    {
        return match ($ruleName) {
            'required' => new RequiredValidator(),
            'email' => new EmailValidator(),
            'min' => new MinLengthValidator(),
            'max' => new MaxLengthValidator(),
            default => throw new InvalidArgumentException("Geçersiz doğrulama kuralı: {$ruleName}"),
        };
    }
}

interface ValidatorInterface
{
    public function validate(array $data, string $field, ?string $ruleValue): ?string;
}

class RequiredValidator implements ValidatorInterface
{
    public function validate(array $data, string $field, ?string $ruleValue): ?string
    {
        return (!isset($data[$field]) || empty($data[$field])) ? 'Bu alan zorunludur.' : null;
    }
}

class EmailValidator implements ValidatorInterface
{
    public function validate(array $data, string $field, ?string $ruleValue): ?string
    {
        return (!filter_var($data[$field], FILTER_VALIDATE_EMAIL)) ? 'Geçerli bir email adresi giriniz.' : null;
    }
}

class MinLengthValidator implements ValidatorInterface
{
    public function validate(array $data, string $field, ?string $ruleValue): ?string
    {
        return (strlen($data[$field]) < (int) $ruleValue) ? "Bu alan en az {$ruleValue} karakter olmalıdır." : null;
    }
}

class MaxLengthValidator implements ValidatorInterface
{
    public function validate(array $data, string $field, ?string $ruleValue): ?string
    {
        return (strlen($data[$field]) > (int) $ruleValue) ? "Bu alan en fazla {$ruleValue} karakter olmalıdır." : null;
    }
}
