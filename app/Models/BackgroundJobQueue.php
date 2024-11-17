<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackgroundJobQueue extends Model
{
    use HasFactory;

    /**
     * @var mixed|string
     */
    public mixed $className;
    protected $table = 'background_jobs_queue';
    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'className', 'status', 'priority', 'parameters'
    ];

}
