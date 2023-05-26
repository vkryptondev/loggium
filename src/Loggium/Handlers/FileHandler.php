<?php

namespace Loggium\Handlers;

use Loggium\Handlers\File\AbstractRotatingStrategy;
use Loggium\Record;

class FileHandler extends StreamHandler
{
    public function handle(Record $record): void
    {
        if ($this->options['path'] === null) {
            throw new \InvalidArgumentException('Path is required');
        }

        if ($this->options['rotate'] && ($rotate = $this->options['rotate']) instanceof AbstractRotatingStrategy) {
            if ($rotate->shouldRotate($this->options, $record)) {
                $i = 1;
                $pi = pathinfo($this->options['path']);
                $filename = $pi['dirname'] . DIRECTORY_SEPARATOR . $pi['filename'] . '.%s.' . $pi['extension'] . ($rotate->config['compress'] ? '.gz' : '');
                if (file_exists(sprintf($filename, 1))) {
                    $j = $rotate->config['max_files'];
                    while($j > 0) {
                        if ($j === $rotate->config['max_files'] && file_exists(sprintf($filename, $j))) {
                            unlink(sprintf($filename, $j));
                        }
                        if (file_exists(sprintf($filename, $j))) {
                            rename(sprintf($filename, $j), sprintf($filename, $j + 1));
                        }
                        $j--;
                    }
                }
                if ($rotate->config['compress']) {
                    $data = file_get_contents($this->options['path']);
                    unlink($this->options['path']);
                    file_put_contents('compress.zlib://' . sprintf($filename, 1), $data);
                } else {
                    rename($this->options['path'], sprintf($filename, 1));
                }
            }
        }

        parent::handle($record);
    }

    protected function defaults() {
        return array_merge(parent::defaults(), [
            'path' => null,
            'rotate' => false,
        ]);
    }
}