<?php

namespace App\BackgroundJobs;

use App\Contracts\JobInterface;
use App\Contracts\JobLaunchResultInterface;

class RestrictedJob extends BasicJob implements JobInterface
{
    public function execute(array $parameters): JobLaunchResultInterface
    {
        return parent::execute($parameters);
    }
}
