<?php

namespace App\Domain\Aluno;

use App\Domain\Aluno\Exceptions\AlunoJaExisteException;
use App\Core\Exceptions\NotFoundException;
use App\Support\PasswordManager;
use App\Domain\Aluno\Filter;

/**
 * Serviço responsável pelas regras de negócio relacionadas ao aluno.
 */
class Service
{
    public function __construct(
        private readonly Repository $repositorio
    ) {}

    /**
     * Cria um novo aluno após validar os dados e verificar duplicidade.
     *
     * @param DTO $dados Dados do aluno a ser criado.
     * @throws AlunoJaExisteException Se e-mail ou CPF já estiverem cadastrados.
     */
    public function criar(DTO $dados): void
    {
        Validator::validar($dados, null);

        if ($this->repositorio->emailOuCpfExiste($dados->getEmail(), $dados->getCpf())) {
            throw new AlunoJaExisteException();
        }

        $senhaHash = PasswordManager::gerarHash($dados->getSenha());

        $aluno = new Entity(
            $dados->getNome(),
            $dados->getNascimento(),
            $dados->getCpf(),
            $dados->getEmail(),
            $senhaHash
        );

        $this->repositorio->criar($aluno);
    }

    /**
     * Retorna uma lista paginada de alunos com filtros aplicados.
     *
     * @param array $params Parâmetros de filtro e paginação.
     * @param string $ordem Ordenação no formato "coluna:direcao".
     * @return array Lista de alunos e total.
     */
    public function listarTodos(array $params, string $ordem): array
    {
        return $this->repositorio->listarTodos($params, $ordem, Filter::camposPermitidos());
    }

    /**
     * Busca um aluno pelo ID.
     *
     * @param int $id Identificador do aluno.
     * @return Entity Aluno encontrado.
     * @throws NotFoundException Se o aluno não for encontrado.
     */
    public function buscarPorId(int $id): Entity
    {
        $aluno = $this->repositorio->buscarPorId($id);

        if (!$aluno) {
            throw new NotFoundException("Aluno {$id} não encontrado.");
        }

        return $aluno;
    }

    /**
     * Busca alunos por nome (mínimo 3 caracteres).
     *
     * @param string $nome Nome do aluno.
     * @return array Lista de alunos encontrados.
     * @throws NotFoundException Se o nome for muito curto.
     */
    public function buscarPorNome(string $nome): array
    {
        if (strlen($nome) < 3) {
            throw new NotFoundException('Nome deve ter pelo menos 3 caracteres.');
        }

        return $this->repositorio->buscarPorNome($nome);
    }

    /**
     * Atualiza os dados de um aluno existente.
     *
     * @param int $id ID do aluno a ser atualizado.
     * @param DTO $dados Novos dados do aluno.
     * @throws AlunoJaExisteException Se CPF ou e-mail já estiverem em uso por outro aluno.
     * @throws NotFoundException Se o aluno não existir.
     */
    public function atualizar(int $id, DTO $dados): void
    {
        $emailOuCpfExiste = $this->repositorio->emailOuCpfExiste($dados->getEmail(), $dados->getCpf(), $id);

        if ($emailOuCpfExiste) {
            throw new AlunoJaExisteException();
        }

        Validator::validar($dados, $id);

        $aluno = $this->repositorio->buscarPorId($id);

        if (!$aluno) {
            throw new NotFoundException("Aluno {$id} não encontrado.");
        }

        $aluno->setNome($dados->getNome());
        $aluno->setNascimento($dados->getNascimento());
        $aluno->setCpf($dados->getCpf());
        $aluno->setEmail($dados->getEmail());

        if (!empty($dados->getSenha())) {
            $aluno->setSenha(PasswordManager::gerarHash($dados->getSenha()));
        }

        $this->repositorio->atualizar($aluno->getId(), [
            'nome'       => $aluno->getNome(),
            'nascimento' => $aluno->getNascimento(),
            'cpf'        => $aluno->getCpf(),
            'email'      => $aluno->getEmail(),
            'senha'      => $aluno->getSenha()
        ]);
    }

    /**
     * Remove um aluno pelo ID.
     *
     * @param int $id ID do aluno.
     * @throws NotFoundException Se o aluno não for encontrado.
     */
    public function remover(int $id): void
    {
        $aluno = $this->repositorio->buscarPorId($id);

        if (!$aluno) {
            throw new NotFoundException("Aluno {$id} não encontrado.");
        }

        $this->repositorio->remover($id);
    }
}
