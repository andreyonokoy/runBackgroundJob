<?php

namespace App\BackgroundJobs;

use App\BackgroundJobResults\BasicJobLaunchResult;
use App\Contracts\JobInterface;
use App\Contracts\JobLaunchResultInterface;
use App\Helpers\FakeJobHelper;
use App\Rules\JobParametersValidation;

class BasicJob implements JobInterface
{
    public function execute(array $parameters): JobLaunchResultInterface
    {
        if (FakeJobHelper::imitate($parameters['time'], $parameters['successExecutePercent'])) {
            return new BasicJobLaunchResult(get_class($this), 1, 'Completed successfully');
        }

        return new BasicJobLaunchResult(get_class($this), 0, 'Completed unsuccessfully');
    }

    public function getValidationRules(): array
    {
        return [
            'time'                  => 'required|integer|between:0,120',
            'successExecutePercent' => 'required|integer|between:0,100',
            'randomParameter'       => 'string|min:10|confirmed',
        ];
    }

    public function getSanitizationRules(): array
    {
        return [
            'time'                  => FILTER_VALIDATE_INT,
            'successExecutePercent' => FILTER_VALIDATE_INT,
        ];
    }

}
