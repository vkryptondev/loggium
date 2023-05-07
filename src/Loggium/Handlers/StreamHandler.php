<?php

namespace Loggium\Handlers;

use Loggium\Level;
use Loggium\Record;

class StreamHandler extends AbstractHandler
{
    public function handle(Record $record): void
    {
        $stream = fopen($this->options['path'], 'a');
        fwrite($stream, $this->render($record) . PHP_EOL);
        fclose($stream);
    }

    protected function defaults() {
        return [
            'path' => 'php://stdout',
            'format' => '[{datetime}] {level}: {message}',
            'min_level' => Level::Debug,
            'max_level' => Level::Emergency,
            'filter' => fn(Record $record) => true,
        ];
    }

    public function filter(Record $record): bool
    {
        return (
            $record->level->value <= $this->options['min_level']->value &&
            $record->level->value >= $this->options['max_level']->value &&
            $this->options['filter']($record)
        );
    }
}