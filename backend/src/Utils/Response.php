<?php

namespace App\Utils;

use App\Core\HttpStatus;

class Response
{
    public static function json(mixed $dados, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        exit;
    }

    public static function noContent(): void
    {
        http_response_code(HttpStatus::NO_CONTENT);
        exit;
    }

    public static function error(string $mensagem, int $status = 400): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => $mensagem], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
