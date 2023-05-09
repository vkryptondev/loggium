<?php

namespace Loggium\Traits;

use Loggium\Logger;

trait HasLoggium
{
    private ?Logger $loggiumInstance = null;

    private function loggium(): Logger
    {
        return $this->loggiumInstance ??= new Logger(get_class($this));
    }
}