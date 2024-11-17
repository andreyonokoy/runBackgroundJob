<?php

namespace App\Console\Commands;

use App\Helpers\CommandResponseHelper;
use App\Services\BackgroundJobQueueService;
use Illuminate\Console\Command;

class runBackgroundJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-background-job {--repeatCount=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executes all jobs from queue';
    protected BackgroundJobQueueService $queueService;

    public function __construct(BackgroundJobQueueService $queueService)
    {
        $this->queueService = $queueService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->error("Started job launch from queue");
        $repeatCount = $this->option('repeatCount');
        if ((int)$repeatCount < 1) {
            $this->error("Wrong parameters format, repeatCount should be > 1");
        }
        $this->processResponse($this->queueService->run($repeatCount));
        $this->error("Process finished");
    }

    protected function processResponse(array $result): void
    {
        if (!$result) {
            $this->info('No jobs in the queue');
        }
        foreach ($result as $job) {
                       $this->info('Job execution for' . $job['className'] . ' id:' . $job['id']);
            $this->info('parameters: ' . $job['parameters']);
            foreach ($job['launches'] as $launch) {
                CommandResponseHelper::print($launch, $this);
            }
            $this->warn('--------------------------------------------------------');
        }
    }


}
