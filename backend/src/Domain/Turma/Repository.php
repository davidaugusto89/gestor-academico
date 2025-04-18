<?php

namespace App\Domain\Turma;

interface Repository
{
    /**
     * Cria uma nova turma no banco de dados.
     *
     * @param Entity $turma
     */
    public function criar(Entity $turma): void;

    /**
     * Lista todas as turmas com filtros e ordenação.
     *
     * @param array $params
     * @param string $ordem
     * @param array|null $camposPermitidos
     * @return array
     */
    public function listarTodos(array $params, string $ordem = 'nome:asc', ?array $camposPermitidos = null): array;

    /**
     * Busca uma turma pelo ID.
     *
     * @param int $id
     * @return object|null
     */
    public function buscarPorId(int $id): ?object;

    /**
     * Busca turmas por nome.
     *
     * @param string $nome
     * @return array
     */
    public function buscarPorNome(string $nome): array;

    /**
     * Verifica se existe outra turma com o mesmo nome.
     *
     * @param string $nome
     * @param int|null $ignorarId
     * @return bool
     */
    public function existeComMesmoNome(string $nome, ?int $ignorarId = null): bool;

    /**
     * Atualiza os dados da turma.
     *
     * @param int $id
     * @param array $dados
     */
    public function atualizar(int $id, array $dados): void;

    /**
     * Remove uma turma pelo ID.
     *
     * @param int $id
     */
    public function remover(int $id): void;
}
