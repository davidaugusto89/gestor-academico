<?php

namespace App\Domain\Turma;

use App\Domain\Turma\Exceptions\TurmaJaExisteException;
use App\Core\Exceptions\NotFoundException;

class Service
{
    public function __construct(
        private Repository $repository,
        private Validator  $validator
    ) {}

    public function criar(DTO $dto): void
    {
        $this->validator->validar($dto);

        if ($this->repository->existeComMesmoNome($dto->nome)) {
            throw new TurmaJaExisteException("Turma '{$dto->nome}' já existe.");
        }

        $entity = new Entity(
            null,
            $dto->nome,
            $dto->descricao
        );

        $this->repository->salvar($entity);
    }

    public function atualizar(int $id, DTO $dto): void
    {
        $this->validator->validar($dto);

        $entity = $this->repository->buscarPorId($id);
        if (!$entity) {
            throw new NotFoundException("Turma {$id} não encontrada.");
        }

        if ($this->repository->existeComMesmoNome($dto->nome)) {
            throw new TurmaJaExisteException("Turma '{$dto->nome}' já existe.");
        }

        $this->repository->atualizar($id, [
            'nome'      => $dto->nome,
            'descricao' => $dto->descricao,
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

    /**
     * @return Entity[]
     */
    public function listarTodos(string $colunaOrdenacao = 'nome'): array
    {
        $rows = $this->repository->listarTodos($colunaOrdenacao);
        return array_map(
            fn(array $row) => new Entity(
                $row['id'],
                $row['nome'],
                $row['descricao']
            ),
            $rows
        );
    }

    public function buscarPorId(int $id): Entity
    {
        $entity = $this->repository->buscarPorId($id);
        if (!$entity) {
            throw new NotFoundException("Turma {$id} não encontrada.");
        }

        return $entity;
    }

    /**
     * @return Entity[]
     */
    public function buscarPorNome(string $nome): array
    {
        return $this->repository->buscarPorNome($nome);
    }

    public function contar(): int
    {
        return $this->repository->contar();
    }
}
