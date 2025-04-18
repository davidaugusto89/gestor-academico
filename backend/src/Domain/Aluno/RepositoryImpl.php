<?php

namespace App\Domain\Aluno;

use App\Core\BaseRepository;
use PDO;

/**
 * Implementação concreta do repositório de alunos.
 */
class RepositoryImpl extends BaseRepository implements Repository
{
    /**
     * @param PDO $pdo Conexão PDO para acesso ao banco de dados.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->tabela = 'alunos';
    }

    /**
     * Insere um novo aluno no banco.
     *
     * @param Entity $aluno Instância da entidade Aluno.
     */
    public function criar(Entity $aluno): void
    {
        $sql = "INSERT INTO {$this->tabela} (nome, nascimento, cpf, email, senha)
                VALUES (:nome, :nascimento, :cpf, :email, :senha)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'nome'       => $aluno->getNome(),
            'nascimento' => $aluno->getNascimento(),
            'cpf'        => $aluno->getCpf(),
            'email'      => $aluno->getEmail(),
            'senha'      => $aluno->getSenha()
        ]);
    }

    /**
     * Verifica se já existe um aluno com o e-mail ou CPF informado.
     *
     * @param string $email
     * @param string $cpf
     * @param int|null $ignorarId ID do aluno a ser ignorado (em edição)
     * @return bool Verdadeiro se já existir aluno com e-mail ou CPF.
     */
    public function emailOuCpfExiste(string $email, string $cpf, ?int $ignorarId = null): bool
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->tabela} WHERE (email = :email OR cpf = :cpf)";

        if ($ignorarId) {
            $sql .= " AND id != :id";
        }

        $stmt = $this->pdo->prepare($sql);

        $params = [
            'email' => $email,
            'cpf' => $cpf
        ];

        if ($ignorarId) {
            $params['id'] = $ignorarId;
        }

        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total'] > 0;
    }

    /**
     * Busca alunos pelo nome usando LIKE.
     *
     * @param string $nome Nome a ser buscado.
     * @return Entity[] Lista de alunos encontrados.
     */
    public function buscarPorNome(string $nome): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->tabela} WHERE nome LIKE :nome ORDER BY nome ASC");
        $stmt->execute(['nome' => "%$nome%"]);

        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => $this->mapearParaEntidade($row), $registros);
    }

    /**
     * Converte um array de dados em uma instância da entidade Aluno.
     *
     * @param array $dados
     * @return Entity
     */
    protected function mapearParaEntidade(array $dados): object
    {
        return new Entity(
            $dados['nome'],
            $dados['nascimento'],
            $dados['cpf'],
            $dados['email'],
            $dados['senha'],
            (int) $dados['id']
        );
    }
}
