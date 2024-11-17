# Background Jobs Queue System

This repository implements a background jobs queue system using Laravel. It allows you to add, queue, and execute jobs with priority management, custom parameters, and validation.


## Installation Process

1. Clone this repository:
    ```bash
    git clone <repository-url>
    cd <repository-folder>
    ```

2. Configure the database connection in the `.env` file with any relational database.

3. Run the migrations:
    ```bash
    php artisan migrate
    ```

## What Was Implemented

Everything has been implemented except for the visual dashboard, and my code cannot accept specific functions that it should execute. I believe that function execution does not provide a stable interface for the client code.


## Testing Process

To run or queue jobs, you should use a Laravel Command (link to the page in Laravel documentation). 
It can be executed in the terminal on both Windows and Linux systems. 

In the background, we can run it using cron, AWS EventBridge, Kubernetes CronJobs, or Task Scheduler in Windows.


## Command Examples

### Add a Job to the Queue

```bash
php artisan app:add-job-to-queue ComplexJob --param=time:3 --param=successExecutePercent:50 --priority=1
```
This will add a job to the queue with:

class name: ComplexJob
priority: 1
time: 3 (Simulates a job delay in seconds).
successExecutePercent: An integer from 0 to 100 that affects the chance that the job will return a success status. This is not an error in the launch process but a notification that the job was unsuccessful for some reason (based on business logic).

### Run a Job instantly

```bash
php artisan app:run-background-job-instantly ComplexJob --param=time:3 --param=successExecutePercent:50
This will run the job instantly without placing it in a queue.
```

### Execute All Jobs in the Queue
```bash
php artisan app:run-background-job --repeatCount=5
```

### Additional Examples

You can copy/paste and see different behaviors in the following commands:

```bash
php artisan app:add-job-to-queue ComplexJob --param=time:1 --param=successExecutePercent:50 --priority=1
# Will be executed last because of the priority

php artisan app:add-job-to-queue ComplexJob --param=time:1 --param=successExecutePercent:50 --priority=10
# Will be executed first

php artisan app:add-job-to-queue ComplexJob --param=time:1 --param=successExecutePercent:5000 --priority=10
# Will raise an error because successExecutePercent should be between 0 and 100 (job parameters validation)

php artisan app:add-job-to-queue ComplexJob --param=time:1 --param=error --priority=10
# Will catch a "wrong command format" exception at the command level

php artisan app:add-job-to-queue ExceptionJob --param=time:1 --param=successExecutePercent:50 --priority=10
# Situation when Job class throws an exception inside it.

php artisan app:add-job-to-queue RestrictedJob --param=time:1 --param=successExecutePercent:50 --priority=10
# Function not from the whitelist of jobs.
```

### Configuration

All important configurations are stored in config/backgroundjobs.php:

```bash
return [
    'class_path'              => 'App\BackgroundJobs\\',  // Job classes namespace
    'approved_jobs_list'      => [                         // Whitelist of jobs
        'BasicJob',       
        'ComplexJob',
        'ExceptionJob',
    ],
    'successful_logs_channel' => 'background_jobs_success', // Success logs channel
    'failure_logs_channel'    => 'background_jobs_error',   // Error logs channel
];
```


### Solution Explanation

The solution provides a structured and flexible approach to job management, using the following principles:
1. JobInterface: Each job implements JobInterface and returns an object of JobLaunchResultInterface. This ensures that all jobs have a consistent structure for execution and result processing.
2. Polymorphism and Abstraction: The system is designed to execute any job that implements JobInterface, with the ability to pass custom parameters to the job via the constructor. Executing random class functions is too flexible and doesn't provide a stable interface for job classes.
3. Validation and Sanitization: Each job class is responsible for its own validation and sanitization logic. This ensures that each job behaves consistently and safely.
4. Dependency Injection: All important dependencies are injected into job classes, making the system modular and testable. The main logic that processes class names, parameters, and execution can be easily replaced with a different system. You can check the dependency resolution process in app/Providers/AppServiceProvider.php.
5. To queue jobs, an adapter was created that stores and retrieves data from the database. However, this solution is not ideal for queue needs. It implements BackgroundJobQueueInterface, but it cannot easily be replaced with Laravel Queues, Redis, or RabbitMQ, which would be better for this task.
6. You can use the following services to run job classes from any place inside your application: \App\Services\BackgroundJobService::execute \App\Services\BackgroundJobLaunchService::run These services will handle job parameters validation and sanitization. The global function runBackgroundJob will start the queue extraction process.

### Known Limitations 

1. I was too focused on job launches and missed some important details in the command classes layer. It needs to have better input data validation in the command layer.
2. The code that implements task launch needs refactoring to make it a bit simpler.