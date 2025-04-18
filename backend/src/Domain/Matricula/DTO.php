<?php

namespace App\Domain\Matricula;

class DTO
{
    private int $aluno_id;
    private int $turma_id;

    private function __construct() {}

    public static function fromArray(array $dados): self
    {
        $dto = new self();
        $dto->aluno_id = (int)($dados['aluno_id'] ?? 0);
        $dto->turma_id = (int)($dados['turma_id'] ?? 0);
        return $dto;
    }

    public function getAlunoId(): int
    {
        return $this->aluno_id;
    }

    public function getTurmaId(): int
    {
        return $this->turma_id;
    }
}
