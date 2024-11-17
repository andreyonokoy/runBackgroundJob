<?php

return [
    'class_path'              => 'App\BackgroundJobs\\',
    'approved_jobs_list'      => [
        'BasicJob',
        'ComplexJob',
        'ExceptionJob',
    ],
    'successful_logs_channel' => 'background_jobs_success',
    'failure_logs_channel'    => 'background_jobs_error',
];
