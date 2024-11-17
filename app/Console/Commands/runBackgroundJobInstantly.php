<?php

namespace App\Console\Commands;

use App\Helpers\CommandResponseHelper;
use Illuminate\Console\Command;
use App\Helpers\ReformatHelper;
use App\Services\BackgroundJobService;

class runBackgroundJobInstantly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-background-job-instantly {className} {--param=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected BackgroundJobService $service;
    protected $description = 'instantly run one concrete job';

    public function __construct(BackgroundJobService $service)
    {
        $this->service = $service;
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $className = $this->argument('className');
        $this->info('Started runBackgroundJob class name:' . $className);
        $parameters = $this->prepareParams();
        $result = $this->service->execute($className, $parameters);
        CommandResponseHelper::print($result, $this);
        return $result->getStatus();
    }

    protected function prepareParams(): array
    {
        $parameters = ReformatHelper::format($this->option('param'));
        if (!$parameters) {
            $parameters = [];
            $this->error("Wrong parameters format, right format is --param=key:value");
        }

        return $parameters;
    }


}
