<?php

namespace App\Http\Controllers;

use Wilon\Myf\Core\Response;

class TestController
{
    public function index($id): Response
    {
        return new Response(['id' => $id]);
    }
}