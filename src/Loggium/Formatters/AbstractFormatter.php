<?php

namespace Loggium\Formatters;

use Loggium\Helper;
use Loggium\Mixins\DatetimeMixin;

abstract class AbstractFormatter implements FormatterInterface
{
    public function __construct(protected array $mixins = []) {
        $this->mixins['datetime'] = new DatetimeMixin();
    }

    protected function dump($var) {
        $dumper = new \Symfony\Component\VarDumper\Dumper\CliDumper();
        $cloner = new \Symfony\Component\VarDumper\Cloner\VarCloner();
        return $dumper->dump($cloner->cloneVar($var), true);
    }

    protected function interpolate(string $string, array $context = []): string
    {
        return preg_replace_callback('/\{([^\}]+)\}/', function ($matches) use ($context) {
            $original = $matches[0];
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
                if (array_key_exists($mixin, $this->mixins)) {
                    return $this->mixins[$mixin]->run($data);
                } else {
                    return $original;
                }
            } else if (array_key_exists($key, $this->mixins)) {
                return $this->mixins[$key]->run();
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
                    return $original;
                }
            } else {
                return $original;
            }
        }, $string);
    }
}