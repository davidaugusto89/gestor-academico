<?php

namespace App\Domain\Usuario;

use App\Domain\Usuario\Exceptions\UsuarioJaExisteException;
use App\Domain\Usuario\Exceptions\UsuarioInvalidoException;
use App\Support\PasswordManager;
use App\Core\Exceptions\NotFoundException;

class Service
{
    public function __construct(
        private readonly Repository $repo,
        private readonly Validator $validator
    ) {}

    public function registrar(DTO $dto): void
    {
        $this->validator->validar($dto);

        if ($this->repo->existePorEmail($dto->email)) {
            throw new UsuarioJaExisteException('E-mail j\u00e1 cadastrado.');
        }

        $hash = PasswordManager::gerarHash($dto->senha);

        $usuario = new Entity(
            $dto->nome,
            $dto->email,
            $hash,
            $dto->papel
        );

        $this->repo->criar($usuario);
    }

    public function autenticar(string $email, string $senha): Entity
    {
        $usuario = $this->repo->buscarPorEmail($email);

        if (!$usuario || !PasswordManager::verificar($senha, $usuario->getSenha())) {
            throw new UsuarioInvalidoException("Credenciais inv\u00e1lidas.");
        }

        return $usuario;
    }

    public function buscarPorId(int $id): Entity
    {
        $usuario = $this->repo->buscarPorId($id);

        if (!$usuario) {
            throw new NotFoundException("Usu\u00e1rio {$id} n\u00e3o encontrado.");
        }

        return $usuario;
    }
}
