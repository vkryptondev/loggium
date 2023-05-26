<?php

namespace Loggium\Handlers\File;

use Loggium\Record;

abstract class AbstractRotatingStrategy implements RotatingStrategyInterface
{
    public function __construct(public array $config = []) {
        $this->config = array_merge([
            'compress' => false,
            'max_files' => 5,
            'max_size' => '1MB',
        ], $this->config);
    }
}