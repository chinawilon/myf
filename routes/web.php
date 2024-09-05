<?php

use Wilon\Myf\Router\Route;

/**@var $router Route */


$router->get('/', function () {
    throw new Exception('404');
});