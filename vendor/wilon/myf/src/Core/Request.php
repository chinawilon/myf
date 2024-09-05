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

    public function getPath(): string
    {
        ['path' => $path] = parse_url($_SERVER['REQUEST_URI']);
        return $path;
    }

    public function all(): array
    {
        return $_GET + $_POST;
    }
}