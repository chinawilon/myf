<?php

namespace Wilon\Myf\Router;
// GET /test/abc
// POST /test/abc/{id}
class Route
{

    public const ASTERISK = '*';

    public function __construct(protected RouteNode $root, protected array $attributes = [])
    {
    }

    public function get(string $path, string|\Closure $handler): static
    {
        $this->addRoute('GET', $path, $handler);
        return $this;
    }

    public function post(string $path, string|\Closure $handler): static
    {
        $this->addRoute('POST', $path, $handler);
        return $this;
    }

    public function group(array $attributes, \Closure $fn): void
    {
        $this->attributes = $attributes;
        $fn($this);
    }

    public function addRoute(string|array $method, string $path, \Closure|string $handler): void
    {
        $paths = explode('/', rtrim($path, '/'));
        foreach((array) $method as $m) {
            $current = $this->root;
            foreach ($paths as $p) {
                $var = '';
                if ( preg_match_all('/{(.*)}/', $p, $matches) ) {
                    $p = self::ASTERISK;
                    $var = $matches[1][0];
                }
                $name = $this->getName($m, $p);
                if (!$c = $current->getChild($name)) {
                    $c = $current->addChild(new RouteNode($name, $var));
                }
                $current = $c;
            }
            $this->setHandler($current, $handler);
        }
    }

    public function getName(string $m, string $p): string
    {
        return strtoupper($m) . ' ' . strtolower($p);
    }

    public function getRouteInfo(string $method, string $path): array
    {
        $paths = explode('/', rtrim($path, '/'));
        $current = $this->root;
        $params = [];
        foreach ($paths as $p) {
            if ($c = $current->getChild($this->getName($method, $p))) {
                $current = $c;
                continue;
            }
            // 查找通配符
            if ( $c = $current->getChild($this->getName($method, self::ASTERISK))) {
                $params[$c->getVar()] = $p;
                $current = $c;
            }
        }
        return [$current, $params];
    }

    public function setHandler(RouteNode $current, string|\Closure $handler): void
    {
        if (is_string($handler)) {
            $handler = isset($this->attributes['namespace']) ?
                $this->attributes['namespace'] . '\\' . $handler : $handler;
        }
        $current->setHandler($handler);
    }
}