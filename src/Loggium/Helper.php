<?php

namespace Loggium;

class Helper
{
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
}