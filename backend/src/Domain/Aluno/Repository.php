<?php

namespace App\Domain\Aluno;

interface Repository
{
    public function criar(Entity $aluno): void;

    public function listarTodos(array $params, string $ordem, ?array $camposPermitidos): array;
    public function buscarPorId(int $id): ?object;
    public function buscarPorNome(string $nome): array;

    public function emailOuCpfExiste(string $email, string $cpf, ?int $ignorarId = null): bool;

    public function atualizar(int $id, array $dados): void;

    public function remover(int $id): void;
}
