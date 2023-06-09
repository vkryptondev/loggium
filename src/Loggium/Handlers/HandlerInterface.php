<?php

namespace Loggium\Handlers;

use Loggium\Formatters\FormatterInterface;
use Loggium\Record;
use Psr\Log\LoggerInterface;

interface HandlerInterface
{
    public function __construct(?FormatterInterface $formatter = null, array $options = []);

    public function handle(Record $record): void;

    public function filter(Record $record): bool;
}