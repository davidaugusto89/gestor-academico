<?php

namespace App\Domain\Usuario;

use App\Domain\Usuario\Exceptions\UsuarioInvalidoException;
use App\Support\PasswordManager;

class Validator
{
    /**
     * Valida os dados do usuário.
     *
     * @param DTO $dto Dados do usuário
     * @param int|null $id ID do usuário, se estiver sendo atualizado
     * @throws UsuarioInvalidoException
     */
    public static function validar(DTO $dto, ?int $id = null): void
    {
        if (strlen(trim($dto->getNome())) < 3) {
            throw new UsuarioInvalidoException("Nome deve ter ao menos 3 caracteres.");
        }

        if (!filter_var($dto->getEmail(), FILTER_VALIDATE_EMAIL)) {
            throw new UsuarioInvalidoException("E-mail inválido.");
        }

        if (!PasswordManager::ehForte($dto->getSenha()) && !$id) {
            throw new UsuarioInvalidoException("Senha fraca. Use letras maiúsculas, minúsculas, números e símbolos.");
        }

        if ($id && $dto->getSenha() && !PasswordManager::ehForte($dto->getSenha())) {
            throw new UsuarioInvalidoException("Senha inválida.");
        }

        if (!in_array($dto->getPapel(), ['admin', 'user'], true)) {
            throw new UsuarioInvalidoException("Papel inválido.");
        }
    }
}
