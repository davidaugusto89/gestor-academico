<?php

namespace App\Domain\Turma;

class Entity implements \JsonSerializable
{
    private ?int $id;
    private string $nome;
    private string $descricao;
    private ?int $totalAlunos = null;

    public function __construct(string $nome, string $descricao, ?int $id = null, ?int $totalAlunos = 0)
    {
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->id = $id;
        $this->totalAlunos = $totalAlunos;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setDescricao(string $descricao): void
    {
        $this->descricao = $descricao;
    }

    public function getTotalAlunos(): ?int
    {
        return $this->totalAlunos;
    }

    public function setTotalAlunos(int $totalAlunos): void
    {
        $this->totalAlunos = $totalAlunos;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'total_alunos' => $this->totalAlunos
        ];
    }
}
