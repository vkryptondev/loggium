<?php

namespace Loggium\Handlers;

use Loggium\Formatters\FormatterInterface;
use Loggium\Formatters\SimpleFormatter;
use Loggium\Level;
use Loggium\Record;
use Psr\Log\LoggerInterface;

abstract class AbstractHandler implements HandlerInterface
{
    public LoggerInterface $logger;

    public function __construct(protected ?FormatterInterface $formatter = null, protected array $options = [])
    {
        $this->formatter = $formatter ?? new SimpleFormatter();
        $this->formatter->setHandler($this);
        $this->options = array_merge($this->defaults(), $this->options);
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    protected function defaults() {
        return [
            'format' => '[{datetime}] {module}.{level}: {message} {context}',
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