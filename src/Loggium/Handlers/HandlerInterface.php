<?php

namespace Loggium\Handlers;

use Loggium\Record;

interface HandlerInterface
{
    public function __construct(array $options);

    public function handle(Record $record): void;

    public function filter(Record $record): bool;
}