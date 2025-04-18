<?php

namespace App\Domain\Turma;

use App\Domain\Turma\Exceptions\TurmaJaExisteException;
use App\Core\Exceptions\NotFoundException;

/**
 * Serviço de aplicação responsável pelas regras de negócio da Turma.
 */
class Service
{
    /**
     * @param Repository $repositorio Repositório de persistência
     */
    public function __construct(
        private Repository $repositorio
    ) {}

    /**
     * Cria uma nova turma após validações.
     *
     * @param DTO $dto Dados da turma
     * @throws TurmaJaExisteException Se nome já existir
     */
    public function criar(DTO $dto): void
    {
        Validator::validar($dto);

        if ($this->repositorio->existeComMesmoNome($dto->getNome())) {
            throw new TurmaJaExisteException("Turma '{$dto->getNome()}' já existe.");
        }

        $entity = new Entity(
            $dto->getNome(),
            $dto->getDescricao()
        );

        $this->repositorio->criar($entity);
    }

    /**
     * Lista todas as turmas com filtros e ordenação.
     *
     * @param array $params
     * @param string $ordem
     * @return array
     */
    public function listarTodos(array $params, string $ordem = 'nome:asc'): array
    {
        return $this->repositorio->listarTodos($params, $ordem, Filter::camposPermitidos());
    }

    /**
     * Retorna uma turma pelo ID ou lança exceção.
     *
     * @param int $id
     * @return Entity
     * @throws NotFoundException
     */
    public function buscarPorId(int $id): Entity
    {
        $entity = $this->repositorio->buscarPorId($id);
        if (!$entity) {
            throw new NotFoundException("Turma {$id} não encontrada.");
        }

        return $entity;
    }

    /**
     * Busca turmas pelo nome.
     *
     * @param string $nome
     * @return array
     */
    public function buscarPorNome(string $nome): array
    {
        return $this->repositorio->buscarPorNome($nome);
    }

    /**
     * Atualiza os dados de uma turma.
     *
     * @param int $id
     * @param DTO $dados
     * @throws TurmaJaExisteException
     * @throws NotFoundException
     */
    public function atualizar(int $id, DTO $dados): void
    {
        Validator::validar($dados);

        $entity = $this->repositorio->buscarPorId($id);
        if (!$entity) {
            throw new NotFoundException("Turma {$id} não encontrada.");
        }

        if ($this->repositorio->existeComMesmoNome($dados->getNome(), $id)) {
            throw new TurmaJaExisteException("Turma '{$dados->getNome()}' já existe.");
        }

        $entity->setNome($dados->getNome());
        $entity->setDescricao($dados->getDescricao());

        $this->repositorio->atualizar($id, [
            'nome'       => $entity->getNome(),
            'descricao'  => $entity->getDescricao(),
        ]);
    }

    /**
     * Remove uma turma pelo ID.
     *
     * @param int $id
     * @throws NotFoundException
     */
    public function remover(int $id): void
    {
        $entity = $this->repositorio->buscarPorId($id);
        if (!$entity) {
            throw new NotFoundException("Turma {$id} não encontrada.");
        }

        $this->repositorio->remover($id);
    }
}
