<?php

namespace App\Domain\Usuario;

use App\Domain\Usuario\Exceptions\UsuarioInvalidoException;
use App\Support\PasswordManager;

class Validator
{
    public static function validar(DTO $dto): void
    {
        if (strlen(trim($dto->nome)) < 3) {
            throw new UsuarioInvalidoException("Nome deve ter ao menos 3 caracteres.");
        }

        if (!filter_var($dto->email, FILTER_VALIDATE_EMAIL)) {
            throw new UsuarioInvalidoException("E-mail inválido.");
        }

        if (!PasswordManager::ehForte($dto->senha)) {
            throw new UsuarioInvalidoException("Senha fraca. Use letras maiúsculas, minúsculas, números e símbolos.");
        }

        if (!in_array($dto->papel, ['admin', 'user'], true)) {
            throw new UsuarioInvalidoException("Papel inválido.");
        }
    }
}
