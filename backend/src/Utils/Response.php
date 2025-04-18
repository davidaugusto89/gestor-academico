<?php

namespace App\Utils;

use App\Core\HttpStatus;

/**
 * Classe utilitária para envio de respostas HTTP formatadas.
 */
class Response
{
    /**
     * Define se o modo de teste está ativo (ignora `exit`).
     */
    private static bool $modoTeste = false;

    /**
     * Último código HTTP definido (usado em testes).
     */
    private static int $statusAtual = HttpStatus::OK;

    /**
     * Ativa o modo de teste.
     */
    public static function ativarModoTeste(): void
    {
        self::$modoTeste = true;
    }

    /**
     * Desativa o modo de teste.
     */
    public static function desativarModoTeste(): void
    {
        self::$modoTeste = false;
    }

    /**
     * Retorna uma resposta JSON.
     *
     * @param mixed $dados Dados para a resposta.
     * @param int $status Código HTTP.
     */
    public static function json(mixed $dados, int $status = HttpStatus::OK): void
    {
        self::enviarCabecalho($status);
        echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        self::finalizar();
    }

    /**
     * Retorna uma resposta 204 No Content.
     */
    public static function noContent(): void
    {
        self::enviarCabecalho(HttpStatus::NO_CONTENT);
        self::finalizar();
    }

    /**
     * Retorna uma resposta JSON de erro.
     *
     * @param string $mensagem Mensagem de erro.
     * @param int $status Código HTTP.
     */
    public static function error(string $mensagem, int $status = HttpStatus::BAD_REQUEST): void
    {
        self::enviarCabecalho($status);
        echo json_encode(['error' => $mensagem], JSON_UNESCAPED_UNICODE);
        self::finalizar();
    }

    /**
     * Envia o cabeçalho HTTP e content-type.
     */
    private static function enviarCabecalho(int $status): void
    {
        self::$statusAtual = $status;
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
    }

    /**
     * Finaliza a execução, exceto em modo de teste.
     */
    private static function finalizar(): void
    {
        if (!self::$modoTeste) {
            exit;
        }
    }

    /**
     * Retorna o último código de status definido (usado para teste).
     *
     * @return int
     */
    public static function getStatus(): int
    {
        return self::$statusAtual;
    }
}
