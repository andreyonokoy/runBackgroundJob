<?php

namespace App\Helpers;


class FakeJobHelper
{
    public static function imitate(int $second, int $successValue = 100): bool
    {
        sleep($second);
        try {
            $value = random_int(0, 100);
        } catch (\Exception $exception) {
            $value = 100;
        }
        return $successValue >= $value;
    }
}
