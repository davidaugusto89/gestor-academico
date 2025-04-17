<?php

namespace App\Domain\Aluno;

use App\Domain\Aluno\Exceptions\AlunoJaExisteException;
use App\Core\Exceptions\NotFoundException;
use App\Support\PasswordManager;
use App\Domain\Aluno\Filter;

class Service
{
    public function __construct(
        private readonly Repository $repositorio
    ) {}

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

    public function listarTodos(array $params, string $ordem): array
    {
        return $this->repositorio->listarTodos($params, $ordem, Filter::camposPermitidos());
    }

    public function buscarPorId(int $id): Entity
    {
        $aluno = $this->repositorio->buscarPorId($id);

        if (!$aluno) {
            throw new NotFoundException("Aluno {$id} não encontrado.");
        }

        return $aluno;
    }

    public function buscarPorNome(string $nome): array
    {
        if (strlen($nome) < 3) {
            throw new NotFoundException('Nome deve ter pelo menos 3 caracteres.');
        }

        return $this->repositorio->buscarPorNome($nome);
    }

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

    public function remover(int $id): void
    {
        $aluno = $this->repositorio->buscarPorId($id);

        if (!$aluno) {
            throw new NotFoundException("Aluno {$id} não encontrado.");
        }

        $this->repositorio->remover($id);
    }
}
