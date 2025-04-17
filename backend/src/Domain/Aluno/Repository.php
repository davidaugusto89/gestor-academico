<?php

namespace App\Domain\Aluno;

interface Repository
{
    /**
     * @return Entity[]
     */
    public function listarTodos(): array;

    public function buscarPorId(int $id): ?object;
    public function atualizar(int $id, array $dados): void;
    public function remover(int $id): void;

    public function emailOuCpfExiste(string $email, string $cpf): bool;
    public function criar(Entity $aluno): void;

    /**
     * @return Entity[]
     */
    public function buscarPorNome(string $nome): array;
}
