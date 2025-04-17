<?php

namespace App\Domain\Turma;

use App\Domain\Turma\Exceptions\TurmaJaExisteException;
use App\Core\Exceptions\NotFoundException;

class Service
{
    public function __construct(
        private Repository $repository,
        private Validator $validator
    ) {}

    public function criar(DTO $dto): void
    {
        $this->validator->validar($dto);

        if ($this->repository->existeComMesmoNome($dto->getNome())) {
            throw new TurmaJaExisteException("Turma '{$dto->getNome()}' já existe.");
        }

        $entity = new Entity(
            $dto->getNome(),
            $dto->getDescricao()
        );

        $this->repository->criar($entity);
    }


    public function listarTodos(string $colunaOrdenacao = 'nome'): array
    {
        return $this->repository->listarTodos($colunaOrdenacao);
    }

    public function buscarPorId(int $id): Entity
    {
        $entity = $this->repository->buscarPorId($id);
        if (!$entity) {
            throw new NotFoundException("Turma {$id} não encontrada.");
        }

        return $entity;
    }

    public function buscarPorNome(string $nome): array
    {
        return $this->repository->buscarPorNome($nome);
    }


    public function atualizar(int $id, DTO $dados): void
    {
        $this->validator->validar($dados);

        $entity = $this->repository->buscarPorId($id);
        if (!$entity) {
            throw new NotFoundException("Turma {$id} não encontrada.");
        }

        if ($this->repository->existeComMesmoNome($dados->getNome(), $id)) {
            throw new TurmaJaExisteException("Turma '{$dados->getNome()}' já existe.");
        }

        $entity->setNome($dados->getNome());
        $entity->setDescricao($dados->getDescricao());

        $this->repository->atualizar($id, [
            'nome'       => $entity->getNome(),
            'descricao'  => $entity->getDescricao(),
        ]);
    }

    public function remover(int $id): void
    {
        $entity = $this->repository->buscarPorId($id);
        if (!$entity) {
            throw new NotFoundException("Turma {$id} não encontrada.");
        }

        $this->repository->remover($id);
    }
}
