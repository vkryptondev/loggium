<?php

namespace Loggium\Handlers;

use Exception;
use http\Exception\RuntimeException;
use Loggium\Helper;
use Loggium\Record;

class AbstractHandler implements HandlerInterface
{
    protected function dump($var) {
        $dumper = new \Symfony\Component\VarDumper\Dumper\CliDumper();
        $cloner = new \Symfony\Component\VarDumper\Cloner\VarCloner();
        return $dumper->dump($cloner->cloneVar($var), true);
    }

    public function __construct(public array $options)
    {
        $this->options = array_merge($this->defaults(), $this->options);
    }

    protected function defaults() {
        return [];
    }

    /**
     * @throws Exception
     */
    public function handle(Record $record): void
    {
        throw new RuntimeException('This method is not implemented');
    }

    public function filter(Record $record): bool
    {
        return true;
    }

    public function interpolate(string $string, array $context = [], array $mixins = []): string
    {
        if (!isset($mixins['datetime'])) $mixins['datetime'] = fn(string $format = 'Y-m-d H:i:s') => date($format);

        return preg_replace_callback('/\{([^\}]+)\}/', function ($matches) use ($mixins, $context) {
            $key = $matches[1];
            if (array_key_exists($key, $context)) {
                $value = $context[$key];

                if (is_callable($value)) {
                    return $value();
                } else if (is_array($value)) {
                    return $this->dump($value);
                } else {
                    return $value;
                }
            } else if (str_contains($key, ':')) {
                $data = explode(':', $key);
                $mixin = array_shift($data);
                $data = implode(':', $data);
                if (array_key_exists($mixin, $mixins)) {
                    return $mixins[$mixin]($data);
                } else {
                    return $matches[0];
                }
            } else if (array_key_exists($key, $mixins)) {
                return $mixins[$key]();
            } else if (str_contains($key, '.')) {
                $data = Helper::getDotValue($context, $key, '{empty}');
                if ($data !== '{empty}') {
                    if (is_callable($data)) {
                        return $data();
                    } else if (is_array($data)) {
                        return $this->dump($data);
                    } else {
                        return $data;
                    }
                } else {
                    return $matches[0];
                }
            } else {
                return $matches[0];
            }
        }, $string);
    }

    public function render(Record $record) {
        return $this->interpolate($this->options['format'], ['message' => $record->message, 'level' => strtoupper($record->level->name), 'context' => $record->context ]);
    }
}