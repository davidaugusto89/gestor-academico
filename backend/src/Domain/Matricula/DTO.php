<?php

namespace App\Domain\Matricula;

class DTO
{
    public int $aluno_id;
    public int $turma_id;

    public static function fromArray(array $dados): self
    {
        $dto = new self();
        $dto->aluno_id = (int)($dados['aluno_id'] ?? 0);
        $dto->turma_id = (int)($dados['turma_id'] ?? 0);
        return $dto;
    }
}
