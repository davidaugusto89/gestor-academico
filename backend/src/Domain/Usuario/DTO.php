<?php

namespace App\Domain\Usuario;

/**
 * Class DTO
 *
 * Representa o Data Transfer Object para entidade de Usuário.
 *
 * @package App\Domain\Usuario
 */
class DTO
{
    /** @var string */
    private string $nome;

    /** @var string */
    private string $email;

    /** @var string */
    private string $senha;

    /** @var string */
    private string $papel;

    /**
     * Construtor privado para forçar uso de fromArray().
     */
    private function __construct() {}

    /**
     * Cria um DTO a partir de um array de dados.
     *
     * @param array $dados
     * @return self
     */
    public static function fromArray(array $dados): self
    {
        $dto = new self();
        $dto->nome    = trim($dados['nome'] ?? '');
        $dto->email   = trim($dados['email'] ?? '');
        $dto->senha   = trim($dados['senha'] ?? '');
        $dto->papel   = trim($dados['papel'] ?? '');

        return $dto;
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
     * Retorna o e-mail do usuário.
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
     * Retorna o papel (role) do usuário.
     *
     * @return string
     */
    public function getPapel(): string
    {
        return $this->papel;
    }
}
