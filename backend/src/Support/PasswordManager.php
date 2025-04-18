<?php

namespace App\Support;

/**
 * Classe utilitária para gerenciamento de senhas, incluindo hash, verificação e validação de força.
 *
 * @package App\Support
 */
class PasswordManager
{
    /**
     * Gera um hash seguro da senha usando o algoritmo padrão do PHP (bcrypt).
     *
     * @param string $senha Senha em texto plano.
     * @return string Hash seguro da senha.
     */
    public static function gerarHash(string $senha): string
    {
        return password_hash($senha, PASSWORD_BCRYPT);
    }

    /**
     * Verifica se a senha informada bate com o hash salvo.
     *
     * @param string $senhaDigitada Senha digitada pelo usuário.
     * @param string $hash Hash armazenado da senha.
     * @return bool Retorna true se a senha corresponder ao hash.
     */
    public static function verificar(string $senhaDigitada, string $hash): bool
    {
        return password_verify($senhaDigitada, $hash);
    }

    /**
     * Valida a força da senha com base nos seguintes critérios:
     * - Pelo menos 8 caracteres
     * - Pelo menos uma letra minúscula
     * - Pelo menos uma letra maiúscula
     * - Pelo menos um número
     * - Pelo menos um caractere especial
     *
     * @param string $senha Senha a ser validada.
     * @return bool Retorna true se a senha for considerada forte.
     */
    public static function ehForte(string $senha): bool
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $senha);
    }
}
