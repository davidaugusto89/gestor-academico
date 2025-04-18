<?php

namespace App\Domain\Usuario;

use App\Core\BaseRepository;
use PDO;

/**
 * Implementação do repositório de usuários, responsável por interações com a tabela "usuarios".
 *
 * @package App\Domain\Usuario
 */
class RepositoryImpl extends BaseRepository implements Repository
{
    /**
     * Construtor da classe.
     *
     * @param PDO $pdo Instância de conexão com o banco de dados.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->tabela = 'usuarios';
    }

    /**
     * Insere um novo usuário no banco de dados.
     *
     * @param Entity $usuario Entidade de usuário a ser criada.
     * @return void
     */
    public function criar(Entity $usuario): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO usuarios (nome, email, senha, papel)
            VALUES (:nome, :email, :senha, :papel)
        ");

        $stmt->execute([
            ':nome'  => $usuario->getNome(),
            ':email' => $usuario->getEmail(),
            ':senha' => $usuario->getSenha(),
            ':papel' => $usuario->getPapel()
        ]);
    }

    /**
     * Busca um usuário pelo e-mail.
     *
     * @param string $email E-mail do usuário.
     * @return Entity|null Retorna a entidade se encontrada, ou null.
     */
    public function buscarPorEmail(string $email): ?Entity
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Entity(
            $row['nome'],
            $row['email'],
            $row['senha'],
            (int) $row['id'],
            $row['papel']
        );
    }

    /**
     * Verifica se existe um usuário com o e-mail informado.
     *
     * @param string $email E-mail a ser verificado.
     * @param int|null $ignorarId ID do usuário a ser ignorado na verificação (opcional).
     * @return bool Retorna true se o e-mail já existir, false caso contrário.
     */
    public function existePorEmail(string $email, ?int $ignorarId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";

        if ($ignorarId) {
            $sql .= " AND id != :id";
        }

        $stmt = $this->pdo->prepare($sql);
        $params = [
            ':email' => $email
        ];

        if ($ignorarId) {
            $params[':id'] = $ignorarId;
        }

        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Mapeia um array de dados para uma entidade de usuário.
     *
     * @param array $dados Dados retornados do banco de dados.
     * @return Entity
     */
    protected function mapearParaEntidade(array $dados): object
    {
        return new Entity(
            $dados['nome'],
            $dados['email'],
            $dados['senha'],
            (int) $dados['id'],
            $dados['papel']
        );
    }
}
