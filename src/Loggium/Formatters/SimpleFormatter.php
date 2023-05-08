<?php

namespace Loggium\Formatters;

use Loggium\Helper;
use Loggium\Record;

class SimpleFormatter extends AbstractFormatter
{
    public function format(Record $record, array $options): string
    {
        return Helper::interpolate(
            $options['format'],
            [
                'module' => $this->handler->logger?->module ?? '',
                'level' => strtoupper($record->level->name),
                'message' => $record->message,
                'context' => $record->context
            ],
            $this->mixins
        );
    }
}