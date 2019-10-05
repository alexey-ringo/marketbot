<?php

namespace App\Traits;

use Log;

trait Loggable
{
    protected function log(string $message, array $context = [])
    {
        $class = (new \ReflectionClass($this))->getShortName();
        $message = $class . '.' . $message;
        
        Log::debug($message, $context);
    }
}