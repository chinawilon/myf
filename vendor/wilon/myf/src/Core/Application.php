<?php

namespace Wilon\Myf\Core;

use Wilon\Myf\Exception\ExceptionHandler;
use Wilon\Myf\Exception\FatalError;
use Wilon\Myf\Exception\Handler;
use Wilon\Myf\Router\Route;

class Application extends Container
{
    public Route $router;
    protected array $middlewares = [];
    protected array $providers = [];
    protected bool $booted = false;
    protected static Application $instance;

    public function __construct()
    {
        $this->bootstrapContainer();
        $this->registerErrorHandling();
    }

    public function registerErrorHandling()
    {
        error_reporting(-1);

        set_exception_handler(function ($e) {
            $this->handleException($this->make(Request::class), $e);
        });

        set_error_handler(function ( int $errno, string $errstr, string $errfile, int $errline) {
            if ( error_reporting() & $errno ) {
                if ( in_array($errno, [E_DEPRECATED, E_USER_DEPRECATED])) {
                    // 不在推荐的错误，做其他的一些记录
                    return ;
                }
                throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
            }
        });

        register_shutdown_function(function () {
            // 非正常结束,报错类并且是致命错误
            if (($error = error_get_last() && $this->isFatal($error['type']))) {
                $this->handleException($this->make(Request::class), new FatalError($error['message'], $error['type']));
            }
        });
    }

    public function isFatal(int $type ): bool
    {
        return in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
    }

    public function handleException(Request $request, $e)
    {
        $this->sendToExceptionHandler($request, $e);
    }

    public function bootstrapContainer()
    {
        $this->router = $this->make(Route::class);
        $this->instance(self::class, $this);
        $this->setInstance($this);
//        $this->bootstrap(); // 内核必须的一些类注入
    }

    public function getInstance()
    {
        return self::$instance ?? new static();
    }

    public function setInstance($instance)
    {
        self::$instance = $instance;
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

    public function boot()
    {
        foreach ($this->providers as $provider) {
            $this->call([$provider, 'boot']);
        }
        $this->booted = true;
    }

    public function run(?Request $request = null)
    {
        if (! $request ) {
            $request = new Request();
        }
        // 声明为单例
        $this->instance(Request::class, $request);

        try {
            $this->boot();
            $then = array_reduce(array_reverse($this->middlewares), function ($carry, $item) {
                return function ($request) use ($carry, $item) {
                    $middleware = $this->make($item);
                    return $middleware->handle($request, $carry);
                };
            }, function ($request) {
                [$route, $params] = $this->router->getRouteInfo($request->getMethod(), $request->getPath());
                if ($handler = $route->getHandler()) {
                    return $this->call($handler, $params);
                }
                throw new \Exception('404 not found');
            });
            $response = $then($request);
        } catch (\Throwable $e) {
            $response = $this->sendToExceptionHandler($request, $e);
        }

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

    public function sendToExceptionHandler(Request $request, $e)
    {
        $handler = $this->resolveExceptionHanlder();
        $handler->report($e);
        return $handler->render($request, $e);
    }

    public function resolveExceptionHanlder(): ExceptionHandler
    {
        return $this->bound(ExceptionHandler::class) ?
            $this->make(ExceptionHandler::class) :
            $this->make(Handler::class);
    }

    public function register(string $service)
    {
        $provider = $this->make($service);
        $this->providers[] = $provider;
        $this->call([$provider, 'register']);

        if ( $this->booted ) {
            $provider->boot();
        }

    }
}