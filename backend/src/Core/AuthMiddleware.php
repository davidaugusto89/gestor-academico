<?php

namespace App\Core;

use App\Core\Exceptions\UnauthorizedException;
use App\Core\TokenManager;

class AuthMiddleware
{
    public static function proteger(array $headers): void
    {
        $authHeader = $headers['Authorization'] ?? '';

        if (!str_starts_with($authHeader, 'Bearer ')) {
            throw new UnauthorizedException('Token de autenticação ausente');
        }

        $token = trim(str_replace('Bearer ', '', $authHeader));
        $dados = TokenManager::validar($token);

        if (!$dados) {
            throw new UnauthorizedException('Token inválido ou expirado');
        }

        $_REQUEST['auth'] = $dados;
    }
}
