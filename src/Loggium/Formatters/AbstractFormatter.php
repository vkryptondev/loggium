<?php

namespace Loggium\Formatters;

use Loggium\Handlers\HandlerInterface;
use Loggium\Helper;
use Loggium\Mixins\DatetimeMixin;
use Psr\Log\LoggerInterface;

abstract class AbstractFormatter implements FormatterInterface
{
    public HandlerInterface $handler;

    public function __construct(protected array $mixins = []) {
        $this->mixins['datetime'] = new DatetimeMixin();
    }

    public function setHandler(HandlerInterface $handler): void
    {
        $this->handler = $handler;
    }
}