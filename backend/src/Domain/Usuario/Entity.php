<?php

namespace App\Domain\Usuario;

class Entity implements \JsonSerializable
{
    public function __construct(
        private string $nome,
        private string $email,
        private string $senha,
        private ?int $id = null,
        private string $papel = Papel::USER->value,
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

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setSenha(string $senha): void
    {
        $this->senha = $senha;
    }

    public function setPapel(string $papel): void
    {
        $this->papel = $papel === Papel::ADMIN->value ? Papel::ADMIN->value : Papel::USER->value;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'email' => $this->email,
            'papel' => $this->papel,
        ];
    }
}
