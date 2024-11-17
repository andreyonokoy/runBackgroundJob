<?php

use App\Services\BackgroundJobQueueService;

if (!function_exists('runBackgroundJob')) {
    function runBackgroundJob(int $repeatCount): array
    {
        return app(BackgroundJobQueueService::class)->run($repeatCount);
    }
}
