<?php

namespace App\Exceptions;

use App\Facades\Log;
use Throwable;
use Wilon\Myf\Core\Request;
use Wilon\Myf\Core\Response;

class Handler extends \Wilon\Myf\Exception\Handler
{

    public function render(Request $request, Throwable $e): Response
    {
        if ( !$e instanceof ApiException) {
            Log::info('[internal] ', json_encode($request->all()));
            $e = new ApiException(ApiException::E_UNKNOWN);
        }
        return new Response(['code' => $e->getCode(), 'msg'=> $e->getMessage(), 'data' => '']);
    }


}