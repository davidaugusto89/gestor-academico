<?php

namespace App\Domain\Turma;

class DTO
{
    public string $nome;
    public string $descricao;

    public function __construct(string $nome, string $descricao)
    {
        $this->nome = $nome;
        $this->descricao = $descricao;
    }
}
