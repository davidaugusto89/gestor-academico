<?php

namespace App\Domain\Usuario;

class Entity
{
    public function __construct(
        private string $nome,
        private string $email,
        private string $senha,
        private string $papel = 'aluno',
        private ?int $id = null
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getSenha(): string
    {
        return $this->senha;
    }

    public function getPapel(): string
    {
        return $this->papel;
    }
}
