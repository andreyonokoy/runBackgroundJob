<?php

namespace App\Adapters;

use App\Contracts\BackgroundJobQueueInterface;
use Illuminate\Support\Facades\DB;

class QueueAdapter implements BackgroundJobQueueInterface
{
    protected $tableName = 'background_jobs_queue';

    /**
     * @throws \JsonException
     */
    public function insert(string $className, array $parameters, mixed $priority): void
    {
        DB::table($this->tableName)->insert([
            'className'  => $className,
            'priority'   => $priority,
            'parameters' => json_encode($parameters, JSON_THROW_ON_ERROR),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function isEmpty(): bool
    {
        return DB::table($this->tableName)
                ->count() === 0;
    }

    public function extract(): array
    {
        $job = DB::table($this->tableName)
            ->orderBy('priority', 'desc')
            ->first(); // Get the first job with the highest priority

        if ($job) {
            DB::table($this->tableName)
                ->where('id', $job->id)
                ->delete();

            return [
                'className'  => $job->className,
                'parameters' => $job->parameters,
                'id'         => $job->id,
            ];
        }

        return [];
    }


}
