<?php


use App\Facades\Log;
use App\ServiceProviders\AppServiceProvider;
use Wilon\Myf\Core\Application;


include_once __DIR__ . '/../vendor/autoload.php';

$app = new Application();

$app->withFacade([
    Log::class => 'Log',
]);
$app->register(AppServiceProvider::class);

return $app;