<?php

namespace App\Utils;

/**
 * Classe utilitária para registrar erros em arquivos de log.
 *
 * @package App\Utils
 */
class Logger
{
    /**
     * Registra um erro no arquivo de log `storage/logs/error.log`.
     *
     * @param string|\Throwable $erro Erro a ser registrado. Pode ser uma string simples ou uma exceção/lançável.
     * @return void
     */
    public static function erro(string|\Throwable $erro): void
    {
        $agora = date('Y-m-d H:i:s');

        $mensagem = is_string($erro)
            ? "[$agora] $erro"
            : sprintf(
                "[%s] %s em %s:%d\nTrace: %s\n",
                $agora,
                $erro->getMessage(),
                $erro->getFile(),
                $erro->getLine(),
                $erro->getTraceAsString()
            );

        file_put_contents(
            __DIR__ . '/../../storage/logs/error.log',
            $mensagem . "\n",
            FILE_APPEND
        );
    }
}
