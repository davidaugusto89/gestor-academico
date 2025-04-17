<?php

namespace App\Domain\Usuario;

class DTO
{
    public string $nome;
    public string $email;
    public string $senha;
    public string $papel;

    public function __construct(string $nome, string $email, string $senha, string $papel = 'aluno')
    {
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        $this->papel = $papel;
    }
}
