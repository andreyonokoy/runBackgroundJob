<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use App\Contracts\BackgroundJobParametersValidationInterface;

class BackgroundJobParametersValidationService implements BackgroundJobParametersValidationInterface
{
    public function validate(array $parameters, array $rules): void
    {
        $validator = Validator::make(
            $parameters,
            $rules
        );

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first(), 102);
        }
    }
}
