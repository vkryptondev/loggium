<?php
namespace Loggium;

use Loggium\Handlers\HandlerInterface;
use \Psr\Log\LoggerInterface;

class Logger implements LoggerInterface
{
    public const VERSION = '1.1.0';

    public function __construct(public string $module = 'main', private array $handlers = [], public array $extra = [])
    {
    }

    public function addHandler(HandlerInterface $handler): void
    {
        $handler->setLogger($this);

        $this->handlers[] = $handler;
    }

    public function extra(string $key, mixed $value): void
    {
        $this->extra[$key] = $value;
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        if (!($level instanceof Level)) {
            if (is_string($level)) {
                $level = Level::fromName($level);
            } elseif (is_int($level)) {
                $level = Level::fromValue($level);
            } else {
                throw new \InvalidArgumentException('Invalid level');
            }
        }
        /** @var Level $level */

        $record = new Record($level, $message, $context);

        foreach($this->handlers as $handler) {
            if ($handler->filter($record)) {
                $handler->handle($record);
            }
        }
    }

    public function emergency(\Stringable|string $message, array $context = []): void
    {
        $this->log(Level::Emergency, $message, $context);
    }

    public function alert(\Stringable|string $message, array $context = []): void
    {
        $this->log(Level::Alert, $message, $context);
    }

    public function critical(\Stringable|string $message, array $context = []): void
    {
        $this->log(Level::Critical, $message, $context);
    }

    public function error(\Stringable|string $message, array $context = []): void
    {
        $this->log(Level::Error, $message, $context);
    }

    public function warning(\Stringable|string $message, array $context = []): void
    {
        $this->log(Level::Warning, $message, $context);
    }

    public function notice(\Stringable|string $message, array $context = []): void
    {
        $this->log(Level::Notice, $message, $context);
    }

    public function info(\Stringable|string $message, array $context = []): void
    {
        $this->log(Level::Info, $message, $context);
    }

    public function debug(\Stringable|string $message, array $context = []): void
    {
        $this->log(Level::Debug, $message, $context);
    }
}