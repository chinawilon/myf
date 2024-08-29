<?php

namespace Wilon\Myf\Core;

class Request
{
    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getRequestURI(): string
    {
        return $_SERVER['REQUEST_URI'];
    }
}