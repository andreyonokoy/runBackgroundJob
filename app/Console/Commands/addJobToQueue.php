<?php

namespace App\Console\Commands;

use App\Helpers\ReformatHelper;
use App\Services\BackgroundJobQueueService;
use Illuminate\Console\Command;

class addJobToQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-job-to-queue {className} {--param=*} {--priority=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adding new job to a queue';
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

        $parameters=$this->prepareParams($this->option('param'));
        if($parameters)
        {
            $this->queueService->insert(
                $this->argument('className'),
                $parameters,
                $this->option('priority')
            );
            $this->info('New job was successfully added');
        }
    }

    protected function prepareParams(array $parameters): array
    {
        $parameters = ReformatHelper::format($parameters);
        if (!$parameters) {
            $parameters = [];
            $this->error("Wrong parameters format, right format is --param=key:value");
        }

        return $parameters;
    }

}
