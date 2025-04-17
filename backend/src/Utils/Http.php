<?php

namespace App\Utils;

class Http
{
    public static function getNormalizedUri(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove o prefixo /api se existir
        if (str_starts_with($uri, '/api')) {
            $uri = substr($uri, 4) ?: '/';
        }

        return $uri;
    }
}
