<?php

namespace App\Domain\Matricula;

interface Repository
{
    public function alunoJaMatriculado(int $alunoId, int $turmaId): bool;
    public function criar(Entity $matricula): void;
    public function listarPorTurma(int $turmaId): array;

    public function remover(int $alunoId, int $turmaId): void;
    public function atualizar(Entity $matricula): void;
    public function buscar(int $alunoId, int $turmaId): ?Entity;
}
