<?php

namespace App\Domain\Usuario;

class DTO
{
    private string $nome;
    private string $email;
    private string $senha;
    private string $papel;

    private function __construct() {}

    public static function fromArray(array $dados): self
    {
        $dto = new self();
        $dto->nome    = trim($dados['nome'] ?? '');
        $dto->email   = trim($dados['email'] ?? '');
        $dto->senha   = trim($dados['senha'] ?? '');
        $dto->papel   = trim($dados['papel'] ?? '');

        return $dto;
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
