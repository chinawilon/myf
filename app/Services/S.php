<?php

namespace App\Services;

use App\Contracts\AI;

class S
{
    public function __construct(protected AI $ai)
    {
    }

    public function run(): string
    {
        return $this->ai->run();
    }
}