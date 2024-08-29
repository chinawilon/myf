<?php


use App\Facades\Log;
use App\Http\Middlewares\TestMiddleware;
use App\ServiceProviders\AppServiceProvider;
use Wilon\Myf\Core\Application;
use Wilon\Myf\Router\Route;


include_once __DIR__ . '/../vendor/autoload.php';

$app = new Application();

$app->withFacade([
    Log::class => 'Log',
]);
$app->register(AppServiceProvider::class);

$app->middlewares([
    TestMiddleware::class
]);

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function (Route $router) {
    include __DIR__ . '/../routes/web.php';
});

return $app;