<?php

namespace App\Domain\Usuario;

interface Repository
{
    public function criar(Entity $usuario): void;

    public function listarTodos(): array;
    public function buscarPorId(int $id): ?object;
    public function buscarPorEmail(string $email): ?Entity;

    public function existePorEmail(string $email, ?int $ignorarId = null): bool;

    public function atualizar(int $id, array $dados): void;

    public function remover(int $id): void;
}
