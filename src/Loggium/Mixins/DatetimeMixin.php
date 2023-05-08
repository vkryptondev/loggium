<?php

namespace Loggium\Mixins;

class DatetimeMixin extends AbstractMixin
{
    public function run(?string $data = null): string
    {
        return date($data ?? 'Y-m-d H:i:s');
    }
}