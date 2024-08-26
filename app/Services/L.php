<?php

namespace App\Services;

class L
{
    public function info(string ...$msg): void
    {
        echo '[info] ' . implode(' ', $msg) . PHP_EOL;
    }
}