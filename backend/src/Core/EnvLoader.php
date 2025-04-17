<?php

namespace App\Core;

use Dotenv\Dotenv;

class EnvLoader
{
    public static function load(string $path = null): void
    {
        $path = $path ?? __DIR__ . '/../../';
        $dotenv = Dotenv::createImmutable($path);
        $dotenv->load();
    }
}
