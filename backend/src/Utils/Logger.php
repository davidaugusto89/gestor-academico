<?php

namespace App\Utils;

class Logger
{
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
