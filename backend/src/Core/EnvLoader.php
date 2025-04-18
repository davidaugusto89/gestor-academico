<?php

namespace App\Core;

use Dotenv\Dotenv;

/**
 * Classe responsável por carregar variáveis de ambiente a partir de um arquivo `.env`.
 */
class EnvLoader
{
    /**
     * Carrega as variáveis de ambiente usando a biblioteca Dotenv.
     *
     * @param string|null $path Caminho para o diretório onde está o arquivo .env. Se nulo, usa o diretório raiz do projeto.
     * @return void
     */
    public static function load(string $path = null): void
    {
        $path = $path ?? __DIR__ . '/../../';
        $dotenv = Dotenv::createImmutable($path);
        $dotenv->load();
    }
}
