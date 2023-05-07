<?php

namespace Loggium;

class Helper
{
    public static function arrayToDot(array $array): array
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array));
        $result = [];
        foreach ($iterator as $leafValue) {
            $keys = [];
            foreach (range(0, $iterator->getDepth()) as $depth) {
                $keys[] = $iterator->getSubIterator($depth)->key();
            }
            $result[implode('.', $keys)] = $leafValue;
        }
        return $result;
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
}