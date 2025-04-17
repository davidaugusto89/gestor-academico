<?php

namespace App\Domain\Aluno;

use App\Utils\DateHelper;
use App\Utils\Normalizer;

class DTO
{
    private string $nome;
    private string $nascimento;
    private string $cpf;
    private string $email;
    private string $senha;

    private function __construct() {}

    public static function fromArray(array $dados): self
    {
        $dto = new self();
        $dto->nome       = trim($dados['nome'] ?? '');
        $dto->nascimento = DateHelper::paraFormatoBanco($dados['nascimento'] ?? '');
        $dto->cpf        = Normalizer::cpf($dados['cpf'] ?? '');
        $dto->email      = trim($dados['email'] ?? '');
        $dto->senha      = trim($dados['senha'] ?? '');

        return $dto;
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
}
