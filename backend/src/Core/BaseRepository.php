<?php

namespace App\Core;

use PDO;
use PDOException;

abstract class BaseRepository
{
    protected PDO $pdo;
    protected string $tabela;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Converte os dados do banco em uma entidade de domínio.
     *
     * @param array $dados
     * @return object
     */
    abstract protected function mapearParaEntidade(array $dados): object;

    /**
     * Retorna todos os registros da tabela como entidades, ordenados alfabeticamente (por RN01).
     *
     * @param string $colunaOrdenacao
     * @return object[]
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
     * Busca um registro por ID e retorna como entidade.
     *
     * @param int $id
     * @return object|null
     */
    public function buscarPorId(int $id): ?object
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->tabela} WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $registro = $stmt->fetch(PDO::FETCH_ASSOC);

        return $registro ? $this->mapearParaEntidade($registro) : null;
    }

    /**
     * Atualiza campos da tabela pelo ID.
     *
     * @param int $id
     * @param array $dados
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
     * Remove um registro da tabela pelo ID.
     *
     * @param int $id
     * @return void
     */
    public function remover(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->tabela} WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
