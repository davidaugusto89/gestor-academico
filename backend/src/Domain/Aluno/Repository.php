<?php

namespace App\Domain\Aluno;

interface Repository
{
    /**
     * Cria um novo aluno.
     *
     * @param Entity $aluno
     */
    public function criar(Entity $aluno): void;

    /**
     * Lista alunos com filtros e ordenação.
     *
     * @param array $params Parâmetros de filtro
     * @param string $ordem Coluna e direção (ex: nome:asc)
     * @param array|null $camposPermitidos Campos permitidos para filtro
     * @return array
     */
    public function listarTodos(array $params, string $ordem, ?array $camposPermitidos): array;

    /**
     * Busca um aluno pelo ID.
     *
     * @param int $id
     * @return object|null
     */
    public function buscarPorId(int $id): ?object;

    /**
     * Busca alunos pelo nome.
     *
     * @param string $nome
     * @return array
     */
    public function buscarPorNome(string $nome): array;

    /**
     * Verifica se já existe aluno com o e-mail ou CPF fornecido.
     *
     * @param string $email
     * @param string $cpf
     * @param int|null $ignorarId ID para ignorar na verificação (em caso de edição)
     * @return bool
     */
    public function emailOuCpfExiste(string $email, string $cpf, ?int $ignorarId = null): bool;

    /**
     * Atualiza os dados de um aluno.
     *
     * @param int $id
     * @param array $dados
     */
    public function atualizar(int $id, array $dados): void;

    /**
     * Remove um aluno pelo ID.
     *
     * @param int $id
     */
    public function remover(int $id): void;
}
