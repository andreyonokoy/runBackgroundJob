<?php

namespace App\Traits;

use App\Contracts\JobLaunchResultInterface;
use Illuminate\Support\Facades\Log;
use App\Enums\JobStatus;

trait LoggableJobs
{
    public function logSuccess(string $className, JobStatus $status): void
    {
        Log::channel(config('backgroundjobs.successful_logs_channel'))->info([
            'timestamp' => time(),
            'className' => $className,
            'status'    => $status->value,
        ]);
    }

    public function logError(string $className, string $message, int $errorCode): void
    {
        Log::channel(config('backgroundjobs.failure_logs_channel'))->info([
            'timestamp' => time(),
            'className' => $className,
            'status'    => JobStatus::FAILURE->value,
            'errorCode' => $errorCode,
            'message'   => $message,
        ]);
    }


}
