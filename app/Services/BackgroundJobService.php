<?php

namespace App\Services;

use App\BackgroundJobResults\BasicJobLaunchResult;
use App\Contracts\BackgroundJobClassValidationInterface;
use App\Contracts\JobLaunchResultInterface;
use App\Traits\LoggableJobs;
use App\Contracts\BackgroundJobLaunchInterface;

class BackgroundJobService
{
    use LoggableJobs;

    protected BackgroundJobLaunchInterface $executionJobService;
    protected BackgroundJobClassValidationInterface $backgroundJobClassValidationService;

    protected string $jobClassesPath;

    public function __construct(
        BackgroundJobLaunchInterface $executionJobService,
        BackgroundJobClassValidationInterface $backgroundJobClassValidationService,
        string $jobClassesPath
    ) {
        $this->jobClassesPath = $jobClassesPath;
        $this->backgroundJobClassValidationService = $backgroundJobClassValidationService;
        $this->executionJobService = $executionJobService;
    }

    public function execute(string $className, array $parameters): JobLaunchResultInterface
    {
        try {
            $classNameWithNameSpace = $this->setClassNameWithNameSpace($className);
            $this->validate($className, $classNameWithNameSpace, $parameters);
            return $this->executionJobService->run(new $classNameWithNameSpace(), $parameters);
        } catch (\Exception $exception) {
            return $this->processException($classNameWithNameSpace, $exception);
        }
    }

    public function validate(string $className, string $classNameWithNameSpace, array $parameters): void
    {
        $this->backgroundJobClassValidationService->validate('className', [
            'className'              => $className,
            'classNameWithNameSpace' => $classNameWithNameSpace,
        ]);
    }

    public function setClassNameWithNameSpace(string $className): string
    {
        return $this->jobClassesPath . $className;
    }

    public function processException(string $classNameWithNameSpace, \Exception $exception): JobLaunchResultInterface
    {
        $result = new BasicJobLaunchResult(
            $classNameWithNameSpace,
            0,
            $exception->getMessage(),
            $exception->getCode()
        );
        $this->logError($result->getClassName(), $result->getMessage(), $result->getErrorCode());
        return $result;
    }

}
