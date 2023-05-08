<?php

namespace Loggium\Formatters;

use Loggium\Record;

class SimpleFormatter extends AbstractFormatter
{
    public function format(Record $record, array $options): string
    {
        return $this->interpolate($options['format'], ['message' => $record->message, 'level' => strtoupper($record->level->name), 'context' => $record->context]);
    }
}