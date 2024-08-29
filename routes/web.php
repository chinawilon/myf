<?php

use Wilon\Myf\Router\Route;

/**@var $router Route */


$router->addRoute(['GET', 'POST'], '/test/{id}', function($id) {
    return 'id -> ' . $id . ' - p';
});

$router->get('/test/index/{id}', 'TestController@index');
