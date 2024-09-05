<?php

namespace Wilon\Myf\Exception;

class FatalError extends \Error
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}