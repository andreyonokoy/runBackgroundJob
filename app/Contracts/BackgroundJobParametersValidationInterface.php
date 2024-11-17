<?php

namespace App\Contracts;

interface BackgroundJobParametersValidationInterface
{
    public function validate(array $parameters, array $rules): void;
}
