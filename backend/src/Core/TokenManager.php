<?php

namespace App\Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use UnexpectedValueException;

const SESSION_DURATION = 3600;

/**
 * Gerencia a geração e validação de tokens JWT.
 */
class TokenManager
{
    /** @var string|null */
    private static ?string $secret = null;

    /**
     * Carrega a chave secreta ou lança se não estiver configurada.
     *
     * @throws UnexpectedValueException
     */
    private static function loadSecret(): void
    {
        if (self::$secret === null) {
            $secret = $_ENV['JWT_SECRET'] ?? getenv('JWT_SECRET');
            if (!$secret) {
                throw new UnexpectedValueException('Chave secreta não configurada');
            }
            self::$secret = $secret;
        }
    }

    /**
     * @param array $payload
     * @param int   $expiraEmSegundos
     * @return string
     * @throws UnexpectedValueException
     */
    public function gerar(array $payload, int $expiraEmSegundos = SESSION_DURATION): string
    {
        self::loadSecret();

        $agora = time();
        $dados = array_merge($payload, [
            'iat' => $agora,
            'exp' => $agora + $expiraEmSegundos,
        ]);

        return JWT::encode($dados, self::$secret, 'HS256');
    }

    /**
     * @param string $token
     * @return array|null
     */
    public static function validar(string $token): ?array
    {
        try {
            self::loadSecret();
            $decoded = JWT::decode($token, new Key(self::$secret, 'HS256'));
            return (array) $decoded;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
