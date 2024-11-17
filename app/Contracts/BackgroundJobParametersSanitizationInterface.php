<?php

namespace App\Contracts;

interface BackgroundJobParametersSanitizationInterface
{
    public function sanitize(array $parameters, array $rules): array;
}
