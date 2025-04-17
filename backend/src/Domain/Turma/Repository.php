<?php

namespace App\Domain\Turma;

interface Repository
{
    public function criar(Entity $turma): void;

    public function listarTodos(array $params, string $ordem = 'nome:asc', ?array $camposPermitidos = null): array;
    public function buscarPorId(int $id): ?object;
    public function buscarPorNome(string $nome): array;

    public function existeComMesmoNome(string $nome, ?int $ignorarId = null): bool;

    public function atualizar(int $id, array $dados): void;

    public function remover(int $id): void;
}
