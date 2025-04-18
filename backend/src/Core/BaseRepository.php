<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Classe base para repositórios de dados.
 * Implementa operações genéricas para entidades de domínio persistidas em banco de dados.
 */
abstract class BaseRepository
{
    /**
     * Instância de conexão PDO.
     *
     * @var PDO
     */
    protected PDO $pdo;

    /**
     * Nome da tabela associada ao repositório.
     *
     * @var string
     */
    protected string $tabela;

    /**
     * Construtor base.
     *
     * @param PDO $pdo Conexão com o banco de dados.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Converte os dados do banco em uma entidade de domínio.
     *
     * @param array $dados Dados brutos retornados do banco.
     * @return object Entidade de domínio correspondente.
     */
    abstract protected function mapearParaEntidade(array $dados): object;

    /**
     * Lista todos os registros da tabela paginados, com ordenação e campos permitidos.
     *
     * @param array $params Filtros e parâmetros de paginação.
     * @param string $ordem Campo e direção de ordenação no formato "campo:direcao".
     * @param array|null $camposPermitidos Campos que podem ser usados para ordenação/filtro.
     *
     * @return object[] Lista de entidades da tabela.
     */
    public function listarTodos(array $params, string $ordem = 'nome:asc', ?array $camposPermitidos = null): array
    {
        $camposPermitidos = $camposPermitidos ?? ['nome'];

        $sqlBase = "SELECT * FROM {$this->tabela} AS t";

        return QueryBuilderHelper::paginarResultado(
            pdo: $this->pdo,
            sqlBase: $sqlBase,
            params: $params,
            camposPermitidos: $camposPermitidos,
            ordem: $ordem,
            alias: 't',
            map: fn($row) => $this->mapearParaEntidade($row),
        );
    }

    /**
     * Busca um registro pelo ID.
     *
     * @param int $id ID da entidade.
     * @return object|null Entidade encontrada ou null.
     */
    public function buscarPorId(int $id): ?object
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->tabela} WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $registro = $stmt->fetch(PDO::FETCH_ASSOC);

        return $registro ? $this->mapearParaEntidade($registro) : null;
    }

    /**
     * Atualiza campos do registro com base no ID.
     *
     * @param int $id ID do registro a ser atualizado.
     * @param array $dados Dados a serem atualizados.
     *
     * @return void
     */
    public function atualizar(int $id, array $dados): void
    {
        $campos = array_keys($dados);
        $set = implode(', ', array_map(fn($campo) => "$campo = :$campo", $campos));

        $dados['id'] = $id;

        $stmt = $this->pdo->prepare("UPDATE {$this->tabela} SET $set WHERE id = :id");
        $stmt->execute($dados);
    }

    /**
     * Remove um registro com base no ID.
     *
     * @param int $id ID do registro a ser removido.
     *
     * @return void
     */
    public function remover(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->tabela} WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
