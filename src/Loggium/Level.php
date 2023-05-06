<?php

enum Level: int
{
    case Emergency = 0;
    case Alert = 1;
    case Critical = 2;
    case Error = 3;
    case Warning = 4;
    case Notice = 5;
    case Info = 6;
    case Debug = 7;

    public static function fromName(string $name): self
    {
        return match (strtoupper($name)) {
            'EMERGENCY' => self::Emergency,
            'ALERT' => self::Alert,
            'CRITICAL' => self::Critical,
            'ERROR' => self::Error,
            'WARNING' => self::Warning,
            'NOTICE' => self::Notice,
            'INFO' => self::Info,
            'DEBUG' => self::Debug,
            default => throw new \InvalidArgumentException('Invalid level name: ' . $name),
        };
    }

    public static function fromValue(int $value): self
    {
        return match ($value) {
            0 => self::Emergency,
            1 => self::Alert,
            2 => self::Critical,
            3 => self::Error,
            4 => self::Warning,
            5 => self::Notice,
            6 => self::Info,
            7 => self::Debug,
            default => throw new \InvalidArgumentException('Invalid level value: ' . $value),
        };
    }

    public function toPSR3Level(): string
    {
        return match ($this) {
            self::Emergency => 'emergency',
            self::Alert => 'alert',
            self::Critical => 'critical',
            self::Error => 'error',
            self::Warning => 'warning',
            self::Notice => 'notice',
            self::Info => 'info',
            self::Debug => 'debug',
        };
    }
}