<?php

namespace App\Services;

class D
{

    public function __construct(protected  C $c)
    {
    }

    public function run(): string
    {
        return  $this->c->run() . ' -> d';
    }
}