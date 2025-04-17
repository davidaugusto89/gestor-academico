<?php

namespace App\Support;

class PasswordManager
{
    /**
     * Gera um hash seguro da senha usando o algoritmo padrão do PHP (bcrypt/argon).
     */
    public static function gerarHash(string $senha): string
    {
        return password_hash($senha, PASSWORD_BCRYPT);
    }

    /**
     * Verifica se a senha informada bate com o hash salvo.
     */
    public static function verificar(string $senhaDigitada, string $hash): bool
    {
        return password_verify($senhaDigitada, $hash);
    }

    /**
     * Valida a força da senha com base em critérios de segurança (RN07).
     */
    public static function ehForte(string $senha): bool
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $senha);
    }
}
