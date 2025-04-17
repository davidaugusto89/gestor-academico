<?php

namespace App\Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenManager
{
    private static ?string $secret = null;

    private static function loadSecret(): void
    {
        if (!self::$secret) {
            self::$secret = $_ENV['JWT_SECRET'] ?? getenv('JWT_SECRET') ?? '';
        }
    }

    public static function gerar(array $payload, int $expiraEmSegundos = 3600): string
    {
        self::loadSecret();

        $agora = time();
        $payload = array_merge($payload, [
            'iat' => $agora,
            'exp' => $agora + $expiraEmSegundos,
        ]);

        return JWT::encode($payload, self::$secret, 'HS256');
    }

    public static function validar(string $token): ?array
    {
        self::loadSecret();

        try {
            $decoded = JWT::decode($token, new Key(self::$secret, 'HS256'));
            return (array) $decoded;
        } catch (\Exception) {
            return null;
        }
    }
}
