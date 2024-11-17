<?php

namespace App\Services;

use App\Contracts\BackgroundJobParametersSanitizationInterface;

class BackgroundJobParametersSanitizationService implements BackgroundJobParametersSanitizationInterface
{
    public function sanitize(array $parameters, array $rules): array
    {
        $parameters = filter_var_array($parameters, $rules);
        if (!is_array($parameters)) {
            throw new \InvalidArgumentException('Sanitizing parameters error', 103);
        }
        return $parameters;
    }
}
