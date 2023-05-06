<?php

namespace Traits;

trait UseLoggium
{
    private ?\Logger $loggiumInstance = null;

    private function loggium(): \Logger
    {
        return $this->loggiumInstance ??= new \Logger(__CLASS__);
    }
}