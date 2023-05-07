<?php
namespace Loggium;

class Record
{
    public function __construct(public Level $level, public string $message, public array $context = [])
    {
        //
    }
}