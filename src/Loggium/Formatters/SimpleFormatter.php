<?php

namespace Loggium\Formatters;

use Loggium\Record;
use Psr\Log\LoggerInterface;

class SimpleFormatter extends AbstractFormatter
{
    public function format(Record $record, array $options): string
    {
        return $this->interpolate(
            $options['format'],
            [
                'module' => $this->handler->logger?->module ?? '',
                'level' => strtoupper($record->level->name),
                'message' => $record->message,
                'context' => $record->context
            ]
        );
    }
}