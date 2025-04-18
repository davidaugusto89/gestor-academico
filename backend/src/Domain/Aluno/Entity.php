<?php

namespace App\Domain\Aluno;

/**
 * Entidade que representa um aluno no sistema.
 *
 * @property int|null $id
 * @property string $nome
 * @property string $nascimento
 * @property string $cpf
 * @property string $email
 * @property string $senha
 */
class Entity implements \JsonSerializable
{
    /**
     * @param string $nome
     * @param string $nascimento
     * @param string $cpf
     * @param string $email
     * @param string $senha
     * @param int|null $id
     */
    public function __construct(
        private string $nome,
        private string $nascimento,
        private string $cpf,
        private string $email,
        private string $senha,
        private ?int $id = null
    ) {}

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /** @return string */
    public function getNome(): string
    {
        return $this->nome;
    }

    /** @return string */
    public function getNascimento(): string
    {
        return $this->nascimento;
    }

    /** @return string */
    public function getCpf(): string
    {
        return $this->cpf;
    }

    /** @return string */
    public function getEmail(): string
    {
        return $this->email;
    }

    /** @return string */
    public function getSenha(): string
    {
        return $this->senha;
    }

    /** @param string $nome */
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    /** @param string $nascimento */
    public function setNascimento(string $nascimento): void
    {
        $this->nascimento = $nascimento;
    }

    /** @param string $cpf */
    public function setCpf(string $cpf): void
    {
        $this->cpf = $cpf;
    }

    /** @param string $email */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /** @param string $senha */
    public function setSenha(string $senha): void
    {
        $this->senha = $senha;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'id'         => $this->id,
            'nome'       => $this->nome,
            'nascimento' => $this->nascimento,
            'cpf'        => $this->cpf,
            'email'      => $this->email
        ];
    }
}
