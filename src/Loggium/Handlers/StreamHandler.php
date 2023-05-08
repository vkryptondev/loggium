<?php

namespace Loggium\Handlers;

use Loggium\Level;
use Loggium\Record;
/**
 * @property array{int f} $options
 */
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
}