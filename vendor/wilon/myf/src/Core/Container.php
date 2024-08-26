<?php

namespace Wilon\Myf\Core;

class Container
{
    protected array $instances = [];
    protected array $bindings = [];
    protected array $resovled = [];
    protected array $aliases = [];


    public function instance(string $abstract, $instance)
    {
        $this->instances[$abstract] = $instance;
    }

    public function bind(string $abstract, string|\Closure $concrete)
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function alias(string $alias, string $abstract)
    {
        $this->aliases[$alias] = $abstract;
    }

    public function rebound()
    {
        // no implement
    }

    public function singleton(string $abstract, string|\Closure $concrete)
    {
        // no implement
    }

    public function make(string $abstract, array $arguments = [])
    {

        if ( isset($this->aliases[$abstract])) {
            $abstract = $this->aliases[$abstract];
        }

        if ( isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        if ( isset($this->bindings[$abstract])) {
            $concrete = $this->bindings[$abstract];
            if ($concrete instanceof \Closure) {
                return $concrete($this);
            }
            return new $concrete();
        }

        // 反射这个类
        $reflectionClass = new \ReflectionClass($abstract);
        if ( $reflectionClass->isInstantiable()) {
            $constructor = $reflectionClass->getConstructor();
            if ( is_null($constructor)) {
                return new $abstract();
            }
            // 获取构造函数的参数,分别处理
            $params = array_map(function(\ReflectionParameter $parameter) use($arguments) {
                return $this->resolveType($parameter, $arguments);
            }, $constructor->getParameters());
            return $reflectionClass->newInstanceArgs($params);
        }
        throw new \RuntimeException("[{$abstract}] is not instantiable.");
    }

    public function call(mixed $abstract, string $method, array $arguments = [])
    {
        $instance = $abstract;
        if (! is_object($abstract) ) {
            $instance = $this->make($abstract);
        }
        // 反射这个类的方法
        $reflectionMethod = new \ReflectionMethod($instance, $method);
        $params = array_map(function(\ReflectionParameter $parameter) use($arguments){
            return $this->resolveType($parameter, $arguments);
        }, $reflectionMethod->getParameters());
        return $reflectionMethod->invokeArgs($instance, $params);
    }


    public function resolveType(\ReflectionParameter $parameter, array $arguments = [])
    {
        $type = $parameter->getType();
        // 允许传入的参数覆盖
        if ( isset($arguments[$parameter->getName()])) {
            return $arguments[$parameter->getName()];
        }
        if ( is_null($type) || $type->isBuiltin() ) {
            if ( $parameter->isDefaultValueAvailable() ) {
                return $parameter->getDefaultValue();
            }
            return null;
        }
        // 自定义的类或者接口
        return $this->make($type->getName());
    }

}