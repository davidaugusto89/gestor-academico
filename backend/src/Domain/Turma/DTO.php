<?php

namespace App\Domain\Turma;

class DTO
{
    private string $nome;
    private string $descricao;

    private function __construct() {}

    public static function fromArray(array $dados): self
    {
        $dto = new self();
        $dto->nome = trim($dados['nome'] ?? '');
        $dto->descricao = trim($dados['descricao'] ?? '');

        return $dto;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }
}
