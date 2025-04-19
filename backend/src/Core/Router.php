<?php

namespace App\Core;

use App\Utils\Http;
use ReflectionMethod;

/**
 * Classe responsável por registrar e despachar rotas HTTP.
 */
class Router
{
    /**
     * Armazena todas as rotas registradas por método HTTP.
     *
     * @var array
     */
    private array $routes = [];

    public function __construct(
        private readonly ControllerFactory $controllerFactory
    ) {}

    /**
     * Registra uma rota do tipo GET.
     *
     * @param string $uri
     * @param array $action [Controller::class, 'método']
     * @return void
     */
    public function get(string $uri, array $action): void
    {
        $this->routes['GET'][$uri] = $action;
    }

    /**
     * Registra uma rota do tipo POST.
     *
     * @param string $uri
     * @param array $action
     * @return void
     */
    public function post(string $uri, array $action): void
    {
        $this->routes['POST'][$uri] = $action;
    }

    /**
     * Registra uma rota do tipo PUT.
     *
     * @param string $uri
     * @param array $action
     * @return void
     */
    public function put(string $uri, array $action): void
    {
        $this->routes['PUT'][$uri] = $action;
    }

    /**
     * Registra uma rota do tipo DELETE.
     *
     * @param string $uri
     * @param array $action
     * @return void
     */
    public function delete(string $uri, array $action): void
    {
        $this->routes['DELETE'][$uri] = $action;
    }

    /**
     * Processa a URI da requisição atual, encontrando e executando a ação correspondente.
     *
     * @return bool True se uma rota foi encontrada e executada, false caso contrário.
     */
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

                $controller = $this->controllerFactory->make($controllerClass);
                $reflection = new ReflectionMethod($controller, $methodName);
                $paramCount = $reflection->getNumberOfParameters();

                $input = json_decode(file_get_contents('php://input'), true) ?? [];
                $queryParams = $_GET;

                $args = $matches;

                if ($method === 'GET' && $paramCount > count($matches)) {
                    $args[] = $queryParams;
                } elseif ($method !== 'GET' && $paramCount > count($matches)) {
                    $args[] = $input;
                }

                $controller->$methodName(...$args);
                return true;
            }
        }

        return false;
    }
}
