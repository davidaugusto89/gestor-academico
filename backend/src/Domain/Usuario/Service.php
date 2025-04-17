<?php

namespace App\Domain\Usuario;

use App\Domain\Usuario\Exceptions\UsuarioJaExisteException;
use App\Domain\Usuario\Exceptions\UsuarioInvalidoException;
use App\Support\PasswordManager;
use App\Core\Exceptions\NotFoundException;

class Service
{
    public function __construct(
        private readonly Repository $repositorio,
        private readonly Validator $validator
    ) {}

    public function autenticar(string $email, string $senha): Entity
    {
        $usuario = $this->repositorio->buscarPorEmail($email);

        if (!$usuario || !PasswordManager::verificar($senha, $usuario->getSenha())) {
            throw new UsuarioInvalidoException("Credenciais inválidas.");
        }

        return $usuario;
    }

    public function criar(DTO $dados): void
    {
        $this->validator->validar($dados, null);

        if ($this->repositorio->existePorEmail($dados->getEmail())) {
            throw new UsuarioJaExisteException('E-mail já cadastrado.');
        }

        $senhaHash = PasswordManager::gerarHash($dados->getSenha());

        $usuario = new Entity(
            $dados->getNome(),
            $dados->getEmail(),
            $senhaHash,
            null,
            $dados->getPapel()
        );

        $this->repositorio->criar($usuario);
    }

    public function listarTodos(): array
    {
        return $this->repositorio->listarTodos('nome');
    }

    public function buscarPorId(int $id): Entity
    {
        $usuario = $this->repositorio->buscarPorId($id);

        if (!$usuario) {
            throw new UsuarioJaExisteException();
        }

        return $usuario;
    }

    public function buscarPorEmail(string $email): ?Entity
    {
        $usuario = $this->repositorio->buscarPorEmail($email);

        if (!$usuario) {
            throw new NotFoundException("Usuário {$email} nao encontrado.");
        }

        return $usuario;
    }

    public function atualizar(int $id, DTO $dados): void
    {
        $emailExiste = $this->repositorio->existePorEmail($dados->getEmail(), $id);

        if ($emailExiste) {
            throw new UsuarioJaExisteException();
        }

        Validator::validar($dados, $id);

        $usuario = $this->repositorio->buscarPorId($id);

        if (!$usuario) {
            throw new NotFoundException("Usuário {$id} não encontrado.");
        }

        $usuario->setNome($dados->getNome());
        $usuario->setEmail($dados->getEmail());

        if (!empty($dados->getSenha())) {
            $usuario->setSenha(PasswordManager::gerarHash($dados->getSenha()));
        }

        $this->repositorio->atualizar($usuario->getId(), [
            'nome'       => $usuario->getNome(),
            'email'      => $usuario->getEmail(),
            'senha'      => $usuario->getSenha()
        ]);
    }

    public function remover(int $id): void
    {
        $usuario = $this->repositorio->buscarPorId($id);

        if (!$usuario) {
            throw new NotFoundException("Usuário {$id} não encontrado.");
        }

        $this->repositorio->remover($id);
    }
}
