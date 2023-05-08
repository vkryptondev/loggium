<?php

namespace Loggium\Traits;

use Loggium\Logger;

trait UseLoggium
{
    private ?Logger $loggiumInstance = null;

    private function loggium(): Logger
    {
        return $this->loggiumInstance ??= new Logger($this);
    }
}