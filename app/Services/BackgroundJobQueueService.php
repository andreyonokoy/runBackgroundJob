<?php

namespace App\Services;

use App\Adapters\SplPriorityQueueAdapter;
use App\Contracts\BackgroundJobQueueInterface;
use App\Traits\LoggableJobs;
use App\Enums\JobStatus;

class BackgroundJobQueueService
{
    use LoggableJobs;

    protected BackgroundJobQueueInterface $queue;
    protected BackgroundJobService $service;

    public function __construct(BackgroundJobQueueInterface $queue, BackgroundJobService $service)
    {
        $this->queue = $queue;
        $this->service = $service;
    }

    public function insert(string $className, array $parameters, mixed $priority): void
    {
        $this->queue->insert($className, $parameters, $priority);
        $this->logSuccess($className, JobStatus::QUEUED);
    }

    public function run(int $repeatCount): array
    {
        $result = [];
        while (!$this->queue->isEmpty()) {
            ['className' => $className, 'parameters' => $parameters] = $job = $this->queue->extract();
            $iteration = 1;
            $this->logSuccess($className, JobStatus::EXECUTED);
            while ($repeatCount >= $iteration) {
                $launches = $this->service->execute($className, json_decode($parameters, true));
                $job['launches'][] = $launches;
                if ($launches->getStatus()) {
                    $this->logSuccess($className, JobStatus::SUCCESS);
                    break;
                }
                $iteration++;
            }
            $result[] = $job;
            $this->logSuccess($className, JobStatus::FAILURE);
        }
        return $result;
    }

}
