<?php

namespace App\Enums;


enum JobStatus: string
{
    case QUEUED = 'queued';
    case EXECUTED = 'executed';
    case SUCCESS = 'success';
    case FAILURE = 'failure';
}
