<?php

namespace App\Http\Middlewares;

use App\Contracts\AI;
use Wilon\Myf\Core\Request;
use Wilon\Myf\Core\Response;

class TestMiddleware
{
    public function __construct(protected AI $ai)
    {
    }

    public function handle(Request $request, \Closure $next)
    {
        // 中间件逻辑
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        file_put_contents('test.txt', 'terminate', FILE_APPEND);
    }
}