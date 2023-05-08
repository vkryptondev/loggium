<?php

namespace Loggium\Formatters;

use Loggium\Record;

interface FormatterInterface
{
    public function format(Record $record, array $options): string;
}