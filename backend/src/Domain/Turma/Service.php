<?php

namespace App\Domain\Turma;

use App\Domain\Turma\Exceptions\TurmaJaExisteException;
use App\Core\Exceptions\NotFoundException;

class Service
{
    public function __construct(
        private Repository $repositorio,
        private Validator $validator
    ) {}

    public function criar(DTO $dto): void
    {
        $this->validator->validar($dto);

        if ($this->repositorio->existeComMesmoNome($dto->getNome())) {
            throw new TurmaJaExisteException("Turma '{$dto->getNome()}' já existe.");
        }

        $entity = new Entity(
            $dto->getNome(),
            $dto->getDescricao()
        );

        $this->repositorio->criar($entity);
    }

    public function listarTodos(array $params, string $ordem = 'nome:asc'): array
    {
        return $this->repositorio->listarTodos($params, $ordem, Filter::camposPermitidos());
    }

    public function buscarPorId(int $id): Entity
    {
        $entity = $this->repositorio->buscarPorId($id);
        if (!$entity) {
            throw new NotFoundException("Turma {$id} não encontrada.");
        }

        return $entity;
    }

    public function buscarPorNome(string $nome): array
    {
        return $this->repositorio->buscarPorNome($nome);
    }


    public function atualizar(int $id, DTO $dados): void
    {
        $this->validator->validar($dados);

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

    public function remover(int $id): void
    {
        $entity = $this->repositorio->buscarPorId($id);
        if (!$entity) {
            throw new NotFoundException("Turma {$id} não encontrada.");
        }

        $this->repositorio->remover($id);
    }
}
