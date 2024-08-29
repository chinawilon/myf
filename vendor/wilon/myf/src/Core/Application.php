<?php

namespace Wilon\Myf\Core;

use Wilon\Myf\Router\Route;

class Application extends Container
{
    public Route $router;
    protected array $middlewares = [];

    public function __construct()
    {
        $this->router = $this->make(Route::class);
    }

    public function middlewares(array $middlewares = [])
    {
        $this->middlewares = $middlewares;
    }

    public function withFacade(array $userAliases = [])
    {
        Facade::setApplicationRoot($this);

        // 绑定用户自定义的别名
        foreach ($userAliases as $abstract => $alias) {
            class_alias($abstract, $alias);
        }

    }

    public function run(?Request $request = null)
    {
        if (! $request ) {
            $request = new Request();
        }
        // 声明为单例
        $this->instance(Request::class, $request);

        [$route, $params] = $this->router->getRouteInfo($request->getMethod(), $request->getRequestURI());

        if ( $handler = $route->getHandler() ) {
            $then = array_reduce(array_reverse($this->middlewares), function ($carry, $item) {
                return function ($passable) use($carry, $item) {
                    $middleware = $this->make($item);
                    return $middleware->handle($passable, $carry);
                };
            }, function ($passable) use($handler, $params){
                return $this->call($handler, $params);
            });
            $response = $then($request);
            if ( !$response instanceof Response ) {
                $response = new Response($response);
            }
            $response->send();
            fastcgi_finish_request();
            // 可终止中间件的用法
            foreach($this->middlewares as $middleware) {
                if ( method_exists($instance = $this->make($middleware), 'terminate') ) {
                    $instance->terminate($request, $response);
                }
            }
        }

    }

    public function register(string $serviver)
    {
        $provider = new $serviver($this);
        $provider->register();
    }
}