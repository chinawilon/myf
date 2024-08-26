<?php

namespace App\Facades;

use Wilon\Myf\Core\Facade;

class Log extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'log';
    }
}