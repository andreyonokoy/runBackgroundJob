<?php

namespace App\BackgroundJobResults;

use App\Contracts\JobLaunchResultInterface;
use \Illuminate\Support\Carbon;

class BasicJobLaunchResult implements JobLaunchResultInterface
{
    protected string $className;
    protected bool $status;
    protected string $message;
    protected int $errorCode;
    protected Carbon $timestamp;

    public function __construct(
        string $className,
        bool $status,
        string $message,
        int $errorCode = 0
    ) {
        $this->className = $className;
        $this->timestamp = now();
        $this->status = $status;
        $this->message = $message;
        $this->errorCode = $errorCode;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function getTimestamp(): Carbon
    {
        return $this->timestamp;
    }

    public function getClassName(): string
    {
        return $this->className;
    }


}
