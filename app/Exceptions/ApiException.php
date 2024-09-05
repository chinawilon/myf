<?php

namespace App\Exceptions;

use Throwable;

class ApiException extends \Exception
{
    public const E_USER_NOT_FOUND = 10001;
    public const E_UNKNOWN = 999999;

    public function __construct( int $code = 0, ?Throwable $previous = null)
    {
        $message = $this->getMsg($code);
        parent::__construct($message, $code, $previous);
    }

    public function getMsg($code): string
    {
        return [
            self::E_USER_NOT_FOUND => '用户不存在',
        ][$code] ?? '未知错误';
    }
}