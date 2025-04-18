<?php

namespace App\Domain\Usuario;

use App\Domain\Usuario\Exceptions\UsuarioJaExisteException;
use App\Domain\Usuario\Exceptions\UsuarioInvalidoException;
use App\Support\PasswordManager;
use App\Core\Exceptions\NotFoundException;

/**
 * Serviço responsável pelas regras de negócios dos usuários.
 */
class Service
{
    /**
     * @param Repository $repositorio Repositório de persistência dos usuários.
     */
    public function __construct(
        private readonly Repository $repositorio,
    ) {}

    /**
     * Autentica um usuário com base no e-mail e senha fornecidos.
     *
     * @param string $email E-mail do usuário.
     * @param string $senha Senha do usuário.
     * @return Entity Retorna a entidade do usuário autenticado.
     * @throws UsuarioInvalidoException Lança exceção se as credenciais forem inválidas.
     */
    public function autenticar(string $email, string $senha): Entity
    {
        $usuario = $this->repositorio->buscarPorEmail($email);

        if (!$usuario || !PasswordManager::verificar($senha, $usuario->getSenha())) {
            throw new UsuarioInvalidoException("Credenciais inválidas.");
        }

        return $usuario;
    }

    /**
     * Cria um novo usuário no sistema.
     *
     * @param DTO $dados Dados do usuário a ser criado.
     * @throws UsuarioJaExisteException Lança exceção se o e-mail já estiver cadastrado.
     */
    public function criar(DTO $dados): void
    {
        Validator::validar($dados);

        if ($this->repositorio->existePorEmail($dados->getEmail())) {
            throw new UsuarioJaExisteException('E-mail já cadastrado.');
        }

        $senhaHash = PasswordManager::gerarHash($dados->getSenha());

        $usuario = new Entity(
            $dados->getNome(),
            $dados->getEmail(),
            $senhaHash,
            null,
            $dados->getPapel()
        );

        $this->repositorio->criar($usuario);
    }

    /**
     * Lista todos os usuários com filtros e ordenação.
     *
     * @param array $params Parâmetros para filtragem e paginação.
     * @param string $ordem Ordem da listagem (por padrão, ordena por nome).
     * @return array Lista de usuários.
     */
    public function listarTodos(array $params, string $ordem): array
    {
        return $this->repositorio->listarTodos($params, $ordem, Filter::camposPermitidos());
    }

    /**
     * Busca um usuário pelo seu ID.
     *
     * @param int $id ID do usuário.
     * @return Entity Retorna a entidade do usuário.
     * @throws UsuarioJaExisteException Lança exceção se o usuário não for encontrado.
     */
    public function buscarPorId(int $id): Entity
    {
        $usuario = $this->repositorio->buscarPorId($id);

        if (!$usuario) {
            throw new UsuarioJaExisteException();
        }

        return $usuario;
    }

    /**
     * Busca um usuário pelo seu e-mail.
     *
     * @param string $email E-mail do usuário.
     * @return Entity|null Retorna a entidade do usuário ou nulo se não encontrado.
     * @throws NotFoundException Lança exceção se o usuário não for encontrado.
     */
    public function buscarPorEmail(string $email): ?Entity
    {
        $usuario = $this->repositorio->buscarPorEmail($email);

        if (!$usuario) {
            throw new NotFoundException("Usuário {$email} não encontrado.");
        }

        return $usuario;
    }

    /**
     * Atualiza os dados de um usuário.
     *
     * @param int $id ID do usuário.
     * @param DTO $dados Novos dados para o usuário.
     * @throws UsuarioJaExisteException Lança exceção se o e-mail já estiver cadastrado.
     * @throws NotFoundException Lança exceção se o usuário não for encontrado.
     */
    public function atualizar(int $id, DTO $dados): void
    {
        $emailExiste = $this->repositorio->existePorEmail($dados->getEmail(), $id);

        if ($emailExiste) {
            throw new UsuarioJaExisteException();
        }

        Validator::validar($dados, $id);

        $usuario = $this->repositorio->buscarPorId($id);

        if (!$usuario) {
            throw new NotFoundException("Usuário {$id} não encontrado.");
        }

        $usuario->setNome($dados->getNome());
        $usuario->setEmail($dados->getEmail());

        if (!empty($dados->getSenha())) {
            $usuario->setSenha(PasswordManager::gerarHash($dados->getSenha()));
        }

        $this->repositorio->atualizar($usuario->getId(), [
            'nome'       => $usuario->getNome(),
            'email'      => $usuario->getEmail(),
            'senha'      => $usuario->getSenha()
        ]);
    }

    /**
     * Remove um usuário do sistema.
     *
     * @param int $id ID do usuário a ser removido.
     * @throws NotFoundException Lança exceção se o usuário não for encontrado.
     */
    public function remover(int $id): void
    {
        $usuario = $this->repositorio->buscarPorId($id);

        if (!$usuario) {
            throw new NotFoundException("Usuário {$id} não encontrado.");
        }

        $this->repositorio->remover($id);
    }
}
