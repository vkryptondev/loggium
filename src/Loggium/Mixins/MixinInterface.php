<?php

namespace Loggium\Mixins;

interface MixinInterface
{
    public function run(?string $data = null): string;
}