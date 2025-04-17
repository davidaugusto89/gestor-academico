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
        list($colunaOrdenacao, $ordem) = explode(':', $ordem);
        $ordem = $ordem === 'asc' ? 'ASC' : 'DESC';

        $page = isset($params['page']) ? (int) $params['page'] : 1;
        $itemsPerPage = isset($params['itemsPerPage']) ? (int) $params['itemsPerPage'] : 10;
        $offset = isset($params['offset']) ? (int) $params['offset'] : ($page - 1) * $itemsPerPage;

        $where = '';

        if ($params) {
            foreach ($params as $key => $value) {
                if (in_array($key, $camposPermitidos) && $value !== null) {
                    switch ($key) {
                        case 'nome':
                            $where .= " AND {$key} LIKE :{$key}";
                            break;
                        default:
                            $where .= " AND {$key} = :{$key}";
                            break;
                    }
                }
            }
        }

        if ($where) {
            $where = ' WHERE ' . substr($where, 4);
        }

        $stmt = $this->pdo->prepare("SELECT * FROM {$this->tabela} {$where} ORDER BY {$colunaOrdenacao} {$ordem} LIMIT :offset, :porPagina");
        $stmt2 = $this->pdo->prepare("SELECT COUNT(*) AS total FROM {$this->tabela} {$where}");

        if ($params) {
            foreach ($params as $key => $value) {
                if (in_array($key, $camposPermitidos) && $value !== null) {
                    if ($key === 'nome') {
                        $stmt->bindValue(':nome', "%{$value}%", PDO::PARAM_STR);
                        $stmt2->bindValue(':nome', "%{$value}%", PDO::PARAM_STR);
                    } else {
                        $stmt->bindValue(":{$key}", $value, PDO::PARAM_STR);
                        $stmt2->bindValue(":{$key}", $value, PDO::PARAM_STR);
                    }
                }
            }
        }

        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':porPagina', $itemsPerPage, PDO::PARAM_INT);
        $stmt->execute();
        $stmt2->execute();

        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = array_map(fn($row) => $this->mapearParaEntidade($row), $registros);

        return [
            'data' => $data,
            'total' => $stmt2->fetch(PDO::FETCH_ASSOC)['total']
        ];
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
