<?php

namespace App\Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

const SESSION_DURATION = 60 * 60;

/**
 * Classe para gerenciar a geração e validação de tokens JWT.
 * Utiliza o Firebase JWT para codificar e decodificar tokens.
 */
class TokenManager
{
    /**
     * @var string|null A chave secreta para codificar e decodificar tokens.
     */
    private static ?string $secret = null;

    /**
     * Carrega a chave secreta do ambiente ou das variáveis de ambiente.
     *
     * @return void
     */
    private static function loadSecret(): void
    {
        if (!self::$secret) {
            self::$secret = $_ENV['JWT_SECRET'] ?? getenv('JWT_SECRET') ?? '';
        }
    }

    /**
     * Gera um token JWT com base no payload fornecido.
     *
     * @param array $payload Dados a serem incluídos no payload do token.
     * @param int $expiraEmSegundos Tempo em segundos até o token expirar (default: 1 hora).
     *
     * @return string O token JWT gerado.
     *
     * @throws \UnexpectedValueException Se a chave secreta não for configurada.
     */
    public static function gerar(array $payload, int $expiraEmSegundos = SESSION_DURATION): string
    {
        self::loadSecret();

        $agora = time();
        $payload = array_merge($payload, [
            'iat' => $agora,
            'exp' => $agora + $expiraEmSegundos,
        ]);

        return JWT::encode($payload, self::$secret, 'HS256');
    }

    /**
     * Valida um token JWT e retorna seus dados decodificados.
     *
     * @param string $token O token JWT a ser validado.
     *
     * @return array|null Os dados decodificados do token, ou null se o token for inválido.
     */
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
