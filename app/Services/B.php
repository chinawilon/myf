<?php

namespace App\Services;

class B
{
    public function __construct(protected C $c)
    {
    }

    public function run(): string
    {
        return $this->c->run() . ' -> b';
    }
}