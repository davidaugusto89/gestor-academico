<?php

namespace App\Domain\Aluno;

class Entity implements \JsonSerializable
{
    public function __construct(
        private string $nome,
        private string $nascimento,
        private string $cpf,
        private string $email,
        private string $senha,
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

    public function getNascimento(): string
    {
        return $this->nascimento;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getSenha(): string
    {
        return $this->senha;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setNascimento(string $nascimento): void
    {
        $this->nascimento = $nascimento;
    }

    public function setCpf(string $cpf): void
    {
        $this->cpf = $cpf;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setSenha(string $senha): void
    {
        $this->senha = $senha;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'         => $this->id,
            'nome'       => $this->nome,
            'nascimento' => $this->nascimento,
            'cpf'        => $this->cpf,
            'email'      => $this->email
        ];
    }
}
