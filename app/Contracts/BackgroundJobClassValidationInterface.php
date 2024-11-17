<?php

namespace App\Contracts;

interface BackgroundJobClassValidationInterface
{
    public function validate(string $attribute, mixed $value): void;
}
