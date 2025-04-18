<?php

namespace App\Domain\Matricula;

interface Repository
{
    /**
     * Realiza a persistência de uma matrícula.
     *
     * @param Entity $matricula
     * @return void
     */
    public function matricular(Entity $matricula): void;

    /**
     * Lista todas as matrículas com filtros, ordenação e paginação.
     *
     * @param array $params Parâmetros de filtro
     * @param string $ordem Campo de ordenação (ex: 'aluno_id:asc')
     * @param array|null $camposPermitidos Campos permitidos para filtragem
     * @return array Lista de matrículas
     */
    public function listarTodos(array $params, string $ordem, ?array $camposPermitidos): array;

    /**
     * Lista todas as matrículas de uma determinada turma.
     *
     * @param int $turmaId ID da turma
     * @return array Lista de alunos matriculados
     */
    public function listarPorTurma(int $turmaId): array;

    /**
     * Verifica se um aluno já está matriculado em uma determinada turma.
     *
     * @param int $alunoId
     * @param int $turmaId
     * @return bool Verdadeiro se já estiver matriculado
     */
    public function alunoJaMatriculado(int $alunoId, int $turmaId): bool;

    /**
     * Remove uma matrícula.
     *
     * @param Entity $matricula
     * @return void
     */
    public function remover(Entity $matricula): void;
}
