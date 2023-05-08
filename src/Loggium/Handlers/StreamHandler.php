<?php

namespace Loggium\Handlers;

use Loggium\Helper;
use Loggium\Level;
use Loggium\Mixins\DatetimeMixin;
use Loggium\Record;
/**
 * @property array{int f} $options
 */
class StreamHandler extends AbstractHandler
{
    public function handle(Record $record): void
    {
        $path = Helper::interpolate(
            $this->options['path'],
            [
                'module' => $this->logger?->module ?? '',
                'level' => strtolower($record->level->name),
            ],
            [
                'datetime' => new DatetimeMixin()
            ]
        );
        $stream = fopen($path, 'a');
        fwrite($stream, $this->format($record) . PHP_EOL);
        fclose($stream);
    }

    protected function defaults() {
        return array_merge(parent::defaults(), [
            'path' => 'php://stdout',
        ]);
    }
}