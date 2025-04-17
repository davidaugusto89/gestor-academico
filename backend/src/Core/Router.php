<?php

namespace App\Core;

use App\Utils\Http;
use ReflectionMethod;

class Router
{
    private array $routes = [];

    public function get(string $uri, array $action): void
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post(string $uri, array $action): void
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function put(string $uri, array $action): void
    {
        $this->routes['PUT'][$uri] = $action;
    }

    public function delete(string $uri, array $action): void
    {
        $this->routes['DELETE'][$uri] = $action;
    }

    public function dispatch(): bool
    {
        $uri = Http::getNormalizedUri();
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes[$method] ?? [] as $route => $action) {
            $pattern = preg_replace('#\{[^\}]+\}#', '([^/]+)', $route);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                [$controllerClass, $methodName] = $action;

                $controller = \App\Core\ControllerFactory::make($controllerClass);
                $reflection = new \ReflectionMethod($controller, $methodName);
                $paramCount = $reflection->getNumberOfParameters();

                $input = json_decode(file_get_contents('php://input'), true) ?? [];

                if ($paramCount > 0) {
                    $args = $matches;
                    if ($paramCount > count($matches)) {
                        $args[] = $input;
                    }
                    $controller->$methodName(...$args);
                } else {
                    $controller->$methodName();
                }

                return true;
            }
        }

        return false;
    }
}
