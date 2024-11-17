<?php

namespace App\Contracts;

use Illuminate\Support\Carbon;

interface JobLaunchResultInterface
{
    public function getClassName(): string;

    public function getStatus(): bool;

    public function getMessage(): string;

    public function getErrorCode(): int;

    public function getTimestamp(): Carbon;
}
