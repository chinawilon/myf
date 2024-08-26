<?php

namespace App\Services;

use App\Contracts\AI;

class A implements AI
{
    public function __construct(protected B $b, protected $i = 1)
    {
    }

    public function run(): string
    {
        return $this->b->run() . ' -> a';
    }

    public function xxx(D $d, int $i = 10): string
    {
        return $d->run() . '->' . $this->b->run() . ' -> a ->' . $i . '::' .  $this->i;
    }
}