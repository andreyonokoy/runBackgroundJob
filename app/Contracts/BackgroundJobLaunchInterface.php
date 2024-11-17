<?php

namespace App\Contracts;

use App\BackgroundJobResults\BasicJobLaunchResult;

interface BackgroundJobLaunchInterface
{
    public function run(JobInterface $job, array $parameters): JobLaunchResultInterface;

    public function processResult(JobLaunchResultInterface $result): JobLaunchResultInterface;
}
