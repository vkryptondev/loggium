<?php

namespace Loggium\Handlers;

use Exception;
use Loggium\Formatters\AbstractFormatter;
use Loggium\Formatters\FormatterInterface;
use Loggium\Formatters\SimpleFormatter;
use Loggium\Helper;
use Loggium\Level;
use Loggium\Record;

abstract class AbstractHandler implements HandlerInterface
{
    public function __construct(protected ?FormatterInterface $formatter = null, protected array $options = [])
    {
        $this->formatter = $formatter ?? new SimpleFormatter();
        $this->options = array_merge($this->defaults(), $this->options);
    }

    protected function defaults() {
        return [
            'format' => '[{datetime}] {level}: {message}',
            'min_level' => Level::Debug,
            'max_level' => Level::Emergency,
            'filter' => fn(Record $record): bool => true,
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

    public function format(Record $record) {
        return $this->formatter->format($record, $this->options);
    }

    abstract public function handle(Record $record): void;
}