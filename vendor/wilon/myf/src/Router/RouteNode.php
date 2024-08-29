<?php

namespace Wilon\Myf\Router;

class RouteNode
{
    protected array $children = [];

    protected null|string|\Closure $handler = null;

    public function __construct(protected string $name = '', protected string $var = '')
    {}

    public function getVar(): string
    {
        return $this->var;
    }

    public function where()
    {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getChild(string $name): ?RouteNode
    {
        return $this->children[$name] ?? null;
    }

    public function addChild(RouteNode $node): RouteNode
    {
        $this->children[$node->getName()] = $node;
        return $node;
    }

    public function setHandler(string|\Closure $handler): void
    {
        $this->handler = $handler;
    }

    public function getHandler(): null|\Closure|string
    {
        return $this->handler;
    }
}