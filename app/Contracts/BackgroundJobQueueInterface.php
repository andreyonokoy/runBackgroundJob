<?php

namespace App\Contracts;

interface BackgroundJobQueueInterface
{
    public function insert(string $className, array $parameters, mixed $priority);

    public function isEmpty(): bool;

    public function extract(): mixed;
}
