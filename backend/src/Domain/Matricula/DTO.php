<?php

namespace App\Domain\Matricula;

/**
 * DTO para transferência de dados de matrícula.
 *
 * @property-read int $aluno_id
 * @property-read int $turma_id
 */
class DTO
{
    private int $aluno_id;
    private int $turma_id;

    private function __construct() {}

    /**
     * Cria uma instância de DTO a partir de um array.
     *
     * @param array $dados ['aluno_id' => int, 'turma_id' => int]
     * @return self
     */
    public static function fromArray(array $dados): self
    {
        $dto = new self();
        $dto->aluno_id = (int)($dados['aluno_id'] ?? 0);
        $dto->turma_id = (int)($dados['turma_id'] ?? 0);
        return $dto;
    }

    /** @return int */
    public function getAlunoId(): int
    {
        return $this->aluno_id;
    }

    /** @return int */
    public function getTurmaId(): int
    {
        return $this->turma_id;
    }
}
