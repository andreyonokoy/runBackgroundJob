<?php

namespace App\Services;

use App\BackgroundJobResults\BasicJobLaunchResult;
use App\Contracts\JobInterface;
use App\Contracts\JobLaunchResultInterface;
use App\Contracts\BackgroundJobParametersValidationInterface;
use App\Contracts\BackgroundJobParametersSanitizationInterface;
use App\Traits\LoggableJobs;
use App\Contracts\BackgroundJobLaunchInterface;
use App\Enums\JobStatus;

class BackgroundJobLaunchService implements BackgroundJobLaunchInterface
{
    use LoggableJobs;

    protected BackgroundJobParametersValidationInterface $validation;
    protected BackgroundJobParametersSanitizationInterface $sanitization;

    public function __construct(
        BackgroundJobParametersValidationInterface $validation,
        BackgroundJobParametersSanitizationInterface $sanitization
    ) {
        $this->validation = $validation;
        $this->sanitization = $sanitization;
    }

    public function run(JobInterface $job, array $parameters): JobLaunchResultInterface
    {
        try {
            $this->validation->validate($parameters, $job->getValidationRules());
            $this->sanitization->sanitize($parameters, $job->getSanitizationRules());
            return $this->processResult($job->execute($parameters));
        } catch (\Exception $exception) {
            return $this->processResult(
                new BasicJobLaunchResult(get_class($job), 0, $exception->getMessage(), $exception->getCode())
            );
        }
    }

    public function processResult(JobLaunchResultInterface $result): JobLaunchResultInterface
    {
        if ($result->getErrorCode() !== 0) {
            $this->logError($result->getClassName(), $result->getMessage(), $result->getErrorCode());
        } else {
            $this->logSuccess($result->getClassName(), JobStatus::SUCCESS);
        }
        return $result;
    }

}
