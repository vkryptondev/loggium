<?php

namespace Loggium\Formatters;

use Loggium\Handlers\HandlerInterface;
use Loggium\Record;

interface FormatterInterface
{
    public function format(Record $record, array $options): string;

    public function setHandler(HandlerInterface $handler): void;
}