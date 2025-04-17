<?php

namespace App\Domain\Turma;

interface Repository
{
    public function salvar(Entity $turma): void;

    public function atualizar(Entity $turma): void;

    public function remover(int $id): void;

    public function buscarPorId(int $id): ?Entity;

    public function listarTodos(int $offset = 0, int $limit = 10): array;

    public function contar(): int;

    public function buscarPorNome(string $nome): array;

    public function existeComMesmoNome(string $nome): bool;
}
