<?php

namespace App\Helpers;

use Illuminate\Support\Arr;

class ReformatHelper
{
    public static function format(array $data): array
    {
        $parameters = [];
        foreach ($data as $param) {
            $paramArray = explode(':', $param);
            if (count($paramArray) === 2) {
                $parameters = Arr::add($parameters, $paramArray[0], $paramArray[1]);
            } else {
                return [];
            }
        }
        return $parameters;
    }
}
