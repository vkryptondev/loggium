<?php

namespace Loggium\Handlers\File;

use Carbon\Carbon;
use Loggium\Record;

class DailyRotatingStrategy extends AbstractRotatingStrategy
{
    public function shouldRotate(array $options, Record $record): bool
    {
        if (!Carbon::now()->subRealMinutes(6)->isCurrentDay()) {
            return true;
        }

        return false;
    }
}