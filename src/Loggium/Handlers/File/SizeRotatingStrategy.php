<?php

namespace Loggium\Handlers\File;

use Loggium\Record;

class SizeRotatingStrategy extends AbstractRotatingStrategy
{

    public function shouldRotate(array $options, Record $record): bool
    {
        $size = $this->cm_common_convert_to_bytes($this->config['max_size']);
        return filesize($options['path']) > $size;
    }

    /**
     * Converts human readable file size into bytes.
     *
     * Note: This is 1024 based version which assumes that a 1 KB has 1024 bytes.
     * Based on https://stackoverflow.com/a/17364338/1041470
     *
     * @param string $from
     *   Required. Human readable size (file, memory or traffic).
     *   For example: '5Gb', '533Mb' and etc.
     *   Allowed integer and float values. Eg., 10.64GB.
     *
     * @return int
     *   Returns given size in bytes.
     *
     * @see https://stackoverflow.com/questions/11807115/php-convert-kb-mb-gb-tb-etc-to-bytes/65726567#65726567
     */
    private function cm_common_convert_to_bytes(string $from): ?int {
        static $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $from = trim($from);
        // Get suffix.
        $suffix = strtoupper(trim(substr($from, -2)));
        // Check one char suffix 'B'.
        if (intval($suffix) !== 0) {
            $suffix = 'B';
        }
        if (!in_array($suffix, $units)) {
            return FALSE;
        }
        $number = trim(substr($from, 0, strlen($from) - strlen($suffix)));
        if (!is_numeric($number)) {
            // Allow only float and integer. Strings produces '0' which is not corect.
            return FALSE;
        }
        return (int) ($number * pow(1024, array_flip($units)[$suffix]));
    }
}