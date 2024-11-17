<?php

namespace App\Providers;

use App\Contracts\BackgroundJobClassValidationInterface;
use App\Contracts\BackgroundJobLaunchInterface;
use App\Contracts\BackgroundJobParametersSanitizationInterface;
use App\Contracts\BackgroundJobParametersValidationInterface;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\ServiceProvider;
use App\Rules\JobClassValidator;
use App\Services\BackgroundJobClassValidationService;
use App\Services\BackgroundJobParametersValidationService;
use App\Services\BackgroundJobParametersSanitizationService;
use App\Services\BackgroundJobLaunchService;
use App\Services\BackgroundJobService;
use App\Services\BackgroundJobQueueService;
use App\Adapters\QueueAdapter;
use App\Contracts\BackgroundJobQueueInterface;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ValidationRule::class, function () {
            return new JobClassValidator(config('jobexecution.approved_jobs_list'));
        });

        $this->app->bind(BackgroundJobClassValidationInterface::class, function () {
            return new BackgroundJobClassValidationService(app(ValidationRule::class));
        });

        $this->app->bind(BackgroundJobClassValidationInterface::class, function () {
            return new BackgroundJobClassValidationService(app(ValidationRule::class));
        });

        $this->app->bind(
            BackgroundJobParametersValidationInterface::class,
            BackgroundJobParametersValidationService::class
        );

        $this->app->bind(
            BackgroundJobParametersSanitizationInterface::class,
            BackgroundJobParametersSanitizationService::class
        );

        $this->app->bind(BackgroundJobLaunchInterface::class, function () {
            return new BackgroundJobLaunchService(
                app(BackgroundJobParametersValidationInterface::class),
                app(BackgroundJobParametersSanitizationInterface::class)
            );
        });

        $this->app->singleton(BackgroundJobService::class, function () {
            return new BackgroundJobService(
                app(BackgroundJobLaunchInterface::class),
                app(BackgroundJobClassValidationInterface::class),
                config('backgroundjobs.class_path')
            );
        });

        $this->app->bind(
            BackgroundJobQueueInterface::class,
            QueueAdapter::class
        );

        $this->app->bind(ValidationRule::class, function () {
            return new JobClassValidator(config('backgroundjobs.approved_jobs_list'));
        });

        $this->app->singleton(BackgroundJobQueueService::class, function () {
            return new BackgroundJobQueueService(
                app(BackgroundJobQueueInterface::class),
                app(BackgroundJobService::class)
            );
        }
        );


        require_once app_path('Helpers/runBackgroundJobHelper.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
