<?php

namespace Wilon\Myf\Core;

abstract class Facade
{

    protected static $app;

    public static function getFacadeAccessor(): string
    {
        throw new \RuntimeException("Class ". static::class . " must define facade accessor.");
    }

    public static function setApplicationRoot(Application $app)
    {
        self::$app = $app;
    }

    public static function getApplicationRoot()
    {
        return self::$app;
    }

    public static function __callStatic(string $name, array $arguments)
    {
        $instance = self::getApplicationRoot()->make(static::getFacadeAccessor());
        return call_user_func_array([$instance, $name], $arguments);
    }
}