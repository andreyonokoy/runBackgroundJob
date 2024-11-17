<?php

namespace App\Services;

use App\Exceptions\JobException;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Contracts\BackgroundJobClassValidationInterface;

class BackgroundJobClassValidationService implements BackgroundJobClassValidationInterface
{
    protected ValidationRule $validationRule;

    public function __construct(ValidationRule $validationRule)
    {
        $this->validationRule = $validationRule;
    }

    public function validate(string $attribute, mixed $value): void
    {
        $this->validationRule->validate($attribute, $value, function ($message, $code) {
            throw new JobException($message, $code);
        });
    }

}
