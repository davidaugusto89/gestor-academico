<?php

namespace App\Domain\Usuario;

use JsonSerializable;

/**
 * Class Entity
 *
 * Representa a entidade de domínio do Usuário.
 *
 * @package App\Domain\Usuario
 */
class Entity implements JsonSerializable
{
    /**
     * Construtor da entidade de usuário.
     *
     * @param string $nome
     * @param string $email
     * @param string $senha
     * @param int|null $id
     * @param string $papel
     */
    public function __construct(
        private string $nome,
        private string $email,
        private string $senha,
        private ?int $id = null,
        private string $papel = Papel::USER->value,
    ) {}

    /**
     * Retorna o ID do usuário.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retorna o nome do usuário.
     *
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * Retorna o email do usuário.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Retorna a senha do usuário.
     *
     * @return string
     */
    public function getSenha(): string
    {
        return $this->senha;
    }

    /**
     * Retorna o papel do usuário.
     *
     * @return string
     */
    public function getPapel(): string
    {
        return $this->papel;
    }

    /**
     * Define o nome do usuário.
     *
     * @param string $nome
     * @return void
     */
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    /**
     * Define o email do usuário.
     *
     * @param string $email
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Define a senha do usuário.
     *
     * @param string $senha
     * @return void
     */
    public function setSenha(string $senha): void
    {
        $this->senha = $senha;
    }

    /**
     * Define o papel do usuário.
     *
     * @param string $papel
     * @return void
     */
    public function setPapel(string $papel): void
    {
        $this->papel = $papel === Papel::ADMIN->value ? Papel::ADMIN->value : Papel::USER->value;
    }

    /**
     * Retorna a entidade como array para serialização JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'email' => $this->email,
            'papel' => $this->papel,
        ];
    }
}
