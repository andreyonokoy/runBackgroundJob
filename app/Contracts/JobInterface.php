<?php

namespace App\Contracts;

use Illuminate\Contracts\Validation\ValidationRule;

interface JobInterface
{
    public function execute(array $parameters): JobLaunchResultInterface;

    public function getValidationRules(): array;

    public function getSanitizationRules(): array;
}
