<?php

namespace App\Domain\Turma;

/**
 * DTO para transferência de dados da entidade Turma.
 */
class DTO
{
    private string $nome;
    private string $descricao;

    private function __construct() {}

    /**
     * Cria um novo DTO a partir de um array de dados.
     *
     * @param array $dados
     * @return self
     */
    public static function fromArray(array $dados): self
    {
        $dto = new self();
        $dto->nome = trim($dados['nome'] ?? '');
        $dto->descricao = trim($dados['descricao'] ?? '');

        return $dto;
    }

    /**
     * Retorna o nome da turma.
     *
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * Retorna a descrição da turma.
     *
     * @return string
     */
    public function getDescricao(): string
    {
        return $this->descricao;
    }
}
