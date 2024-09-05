<?php

namespace Wilon\Myf\Exception;

use Throwable;
use Wilon\Myf\Core\Request;

interface ExceptionHandler
{
    public function render(Request $request, Throwable $e);
    public function report($e);
}