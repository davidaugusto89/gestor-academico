<?php

namespace App\Domain\Turma;

/**
 * Entidade de domínio que representa uma Turma.
 */
class Entity implements \JsonSerializable
{
    private ?int $id;
    private string $nome;
    private string $descricao;
    private ?int $totalAlunos = null;

    /**
     * @param string $nome
     * @param string $descricao
     * @param int|null $id
     * @param int|null $totalAlunos
     */
    public function __construct(string $nome, string $descricao, ?int $id = null, ?int $totalAlunos = 0)
    {
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->id = $id;
        $this->totalAlunos = $totalAlunos;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @return string
     */
    public function getDescricao(): string
    {
        return $this->descricao;
    }

    /**
     * @param string $nome
     */
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao(string $descricao): void
    {
        $this->descricao = $descricao;
    }

    /**
     * @return int|null
     */
    public function getTotalAlunos(): ?int
    {
        return $this->totalAlunos;
    }

    /**
     * @param int $totalAlunos
     */
    public function setTotalAlunos(int $totalAlunos): void
    {
        $this->totalAlunos = $totalAlunos;
    }

    /**
     * Dados serializáveis em JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'total_alunos' => $this->totalAlunos
        ];
    }
}
