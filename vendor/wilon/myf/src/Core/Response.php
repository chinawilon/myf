<?php

namespace Wilon\Myf\Core;

class Response
{
    public function __construct(protected mixed $content)
    {
        $this->content = is_array($content) ? json_encode($content) : $content;
    }

    public function send(): void
    {
        echo $this->content;
    }
}