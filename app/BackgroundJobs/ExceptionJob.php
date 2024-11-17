<?php

namespace App\BackgroundJobs;

use App\Contracts\JobLaunchResultInterface;
use App\Exceptions\JobException;

class ExceptionJob extends BasicJob
{
    public function execute(array $parameters): JobLaunchResultInterface
    {
        throw new JobException('Something went wrong', 1);
        return parent::execute($parameters);
    }
}
