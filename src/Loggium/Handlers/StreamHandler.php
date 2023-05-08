<?php

namespace Loggium\Handlers;

use Loggium\Level;
use Loggium\Record;

class StreamHandler extends AbstractHandler
{
    public function handle(Record $record): void
    {
        $stream = fopen($this->options['path'], 'a');
        fwrite($stream, $this->format($record) . PHP_EOL);
        fclose($stream);
    }

    protected function defaults() {
        return array_merge(parent::defaults(), [
            'path' => 'php://stdout',
        ]);
    }

    public function filter(Record $record): bool
    {
        return parent::filter($record);
    }
}