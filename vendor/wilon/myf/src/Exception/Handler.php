<?php

namespace Wilon\Myf\Exception;

use Throwable;
use Wilon\Myf\Core\Request;

class Handler implements ExceptionHandler
{

    public function report($e)
    {
        //
    }

    public function render(Request $request, Throwable $e)
    {
        //
    }

}
