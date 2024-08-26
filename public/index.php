<?php


/**@var $app Application */


use App\Services\A;
use Wilon\Myf\Core\Application;

$app = include_once __DIR__ . '/../bootstrap/app.php';

//var_dump($app->call(A::class, 'xxx', ['i' => 10000]));


Log::info('yyyy');
Session::xxx();
File::xxx();