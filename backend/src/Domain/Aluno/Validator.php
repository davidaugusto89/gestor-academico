<?php

namespace App\Domain\Aluno;

use App\Domain\Aluno\Exceptions\AlunoInvalidoException;
use App\Support\CpfValidator;
use App\Support\PasswordManager;

class Validator
{
    public static function validar(DTO $aluno, ?int $id): void
    {
        if (strlen($aluno->getNome()) < 3) {
            throw new AlunoInvalidoException('Nome deve ter ao menos 3 caracteres.');
        }

        if (!filter_var($aluno->getEmail(), FILTER_VALIDATE_EMAIL)) {
            throw new AlunoInvalidoException('E-mail inválido.');
        }

        if (!CpfValidator::isValido($aluno->getCpf())) {
            throw new AlunoInvalidoException('CPF inválido.');
        }

        if (!PasswordManager::ehForte($aluno->getSenha()) && !$id) {
            throw new AlunoInvalidoException('Senha fraca. Use letras maiúsculas, minúsculas, número e símbolo.');
        }

        if ($aluno->getSenha() && $id && !PasswordManager::ehForte($aluno->getSenha())) {
            throw new AlunoInvalidoException('Senha fraca. Use letras maiúsculas, minúsculas, número e símbolo.');
        }

        if (!$aluno->getNascimento() || !strtotime($aluno->getNascimento())) {
            throw new AlunoInvalidoException('Data de nascimento inválida.');
        }
    }
}
