<?php

namespace Wilon\Myf\Core;

class Application extends Container
{

    public function withFacade(array $userAliases = [])
    {
        Facade::setApplicationRoot($this);

        // 绑定用户自定义的别名
        foreach ($userAliases as $abstract => $alias) {
            class_alias($abstract, $alias);
        }

    }

    public function register(string $serviver)
    {
        $provider = new $serviver($this);
        $provider->register();
    }
}