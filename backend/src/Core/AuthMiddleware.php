<?php

namespace App\Core;

use App\Core\Exceptions\UnauthorizedException;

/**
 * Middleware de autenticação para proteger rotas com base em token JWT.
 */
class AuthMiddleware
{
    /**
     * Verifica o cabeçalho Authorization e valida o token JWT.
     *
     * @param array $headers Cabeçalhos da requisição, incluindo Authorization.
     *
     * @throws UnauthorizedException Se o token estiver ausente, inválido ou expirado.
     *
     * @return void
     */
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
