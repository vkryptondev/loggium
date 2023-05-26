<?php

namespace Loggium\Handlers\File;

use Loggium\Record;

interface RotatingStrategyInterface
{
    public function shouldRotate(array $options, Record $record): bool;
}