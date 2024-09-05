<?php

namespace App\Services;

class L
{
    public function info(string ...$msg): void
    {
        file_put_contents("php://stderr", '[info] ' . implode(' ', $msg) . PHP_EOL, FILE_APPEND);
    }
}