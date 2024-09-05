<?php

namespace App\Facades;

use Wilon\Myf\Core\Facade;

/**
 * @method static void info(string ...$message)
 */
class Log extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'log';
    }
}