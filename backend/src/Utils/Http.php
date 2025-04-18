<?php

namespace App\Utils;

/**
 * Classe utilitária para operações relacionadas a HTTP.
 *
 * @package App\Utils
 */
class Http
{
    /**
     * Retorna a URI normalizada da requisição atual.
     *
     * Regras de normalização:
     * - Remove o prefixo "/api" caso esteja presente.
     * - Remove a barra no final da URI (exceto quando a URI for "/").
     *
     * @return string URI normalizada.
     */
    public static function getNormalizedUri(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove o prefixo /api se existir
        if (str_starts_with($uri, '/api')) {
            $uri = substr($uri, 4) ?: '/';
        }

        // Remove barra final (exceto se for apenas '/')
        if ($uri !== '/' && str_ends_with($uri, '/')) {
            $uri = rtrim($uri, '/');
        }

        return $uri;
    }
}
