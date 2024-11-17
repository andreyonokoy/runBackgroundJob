<?php

namespace App\Helpers;

use App\Contracts\JobLaunchResultInterface;
use Illuminate\Console\Command;

class CommandResponseHelper
{
    public static function print(JobLaunchResultInterface $result, Command $command): void
    {
        if ($result->getStatus()) {
            $command->info('The class ' . $result->getClassName() . ' has been successfully launched!');
            $command->info('Message: ' . $result->getMessage());
        } elseif ($result->getErrorCode() > 0) {
            $command->warn('The class ' . $result->getClassName() . ' has been executed with error');
            $command->info('ErrorCode: ' . $result->getErrorCode());
            $command->info('Message: ' . $result->getMessage());
        } else {
            $command->warn('The class ' . $result->getClassName() . ' has been unsuccessfully launched!');
            $command->info('Message: ' . $result->getMessage());
        }
    }
}
