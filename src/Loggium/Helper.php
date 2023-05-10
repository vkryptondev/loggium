<?php

namespace Loggium;

class Helper
{
    public static function interpolate(string $template, array $data = [], array $mixins = [])
    {
        return preg_replace_callback('/\{([^\}]+)\}/', function ($matches) use ($data, $mixins) {
            $original = $matches[0];
            $key = $matches[1];
            if (array_key_exists($key, $data)) {
                $value = $data[$key];

                if (empty($value)) {
                    return '';
                } else if (is_array($value) || is_object($value)) {
                    return self::dump($value);
                } else {
                    return $value;
                }
            } else if (str_contains($key, ':')) {
                $mixin = substr($key, 0, strpos($key, ':'));
                $options = substr($key, strpos($key, ':') + 1);
                if (array_key_exists($mixin, $mixins)) {
                    return $mixins[$mixin]->run($options);
                } else {
                    return $original;
                }
            } else if (array_key_exists($key, $mixins)) {
                return $mixins[$key]->run();
            } else if (str_contains($key, '.')) {
                $value = Helper::getDotValue($data, $key, '{empty}');
                if ($value === '{empty}') {
                    return $original;
                } else if (is_array($value) || is_object($value)) {
                    return self::dump($value);
                } else {
                    return $value;
                }
            } else {
                return $original;
            }
        }, $template);
    }

    public static function dump(mixed $object): string {
        $dumper = new \Symfony\Component\VarDumper\Dumper\CliDumper();
        $cloner = new \Symfony\Component\VarDumper\Cloner\VarCloner();
        return $dumper->dump($cloner->cloneVar($object), true);
    }

    public static function getDotValue(array $array, string $key, mixed $default = null): mixed
    {
        $keys = explode('.', $key);
        foreach ($keys as $key) {
            if (!is_array($array) || !array_key_exists($key, $array)) {
                return $default;
            }
            $array = &$array[$key];
        }
        return $array;
    }

    public static function fqcn_slug(string $fqcn): string
    {
        return str_replace('\\', '_', $fqcn);
    }
}