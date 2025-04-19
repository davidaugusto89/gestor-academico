<?php

namespace Tests\Unit\Domain\Usuario;

use App\Domain\Usuario\DTO;
use App\Domain\Usuario\Entity;
use App\Domain\Usuario\Service;
use App\Domain\Usuario\Repository;
use App\Domain\Usuario\Validator;
use App\Domain\Usuario\Exceptions\UsuarioJaExisteException;
use App\Domain\Usuario\Exceptions\UsuarioInvalidoException;
use App\Core\Exceptions\NotFoundException;
use App\Support\PasswordManager;
use PHPUnit\Framework\TestCase;

class UsuarioServiceTest extends TestCase
{
    private Repository $repositorio;
    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repositorio = $this->createMock(Repository::class);
        $this->service = new Service($this->repositorio);
    }

    public function testAutenticarComCredenciaisValidas(): void
    {
        $senha = 'Senha123!';
        $senhaHash = PasswordManager::gerarHash($senha);
        $usuario = new Entity('João', 'joao@email.com', $senhaHash);

        $this->repositorio
            ->method('buscarPorEmail')
            ->willReturn($usuario);

        $resultado = $this->service->autenticar('joao@email.com', $senha);

        $this->assertInstanceOf(Entity::class, $resultado);
    }

    public function testAutenticarComCredenciaisInvalidas(): void
    {
        $this->expectException(UsuarioInvalidoException::class);

        $this->repositorio
            ->method('buscarPorEmail')
            ->willReturn(null);

        $this->service->autenticar('email@falso.com', 'senha');
    }

    public function testCriarUsuarioComEmailDuplicado(): void
    {
        $this->expectException(UsuarioJaExisteException::class);

        $dto = DTO::fromArray([
            'nome' => 'Teste',
            'email' => 'teste@email.com',
            'senha' => 'Senha123!',
            'papel' => 'user'
        ]);

        $this->repositorio
            ->method('existePorEmail')
            ->willReturn(true);

        $this->service->criar($dto);
    }

    public function testBuscarPorIdNaoEncontrado(): void
    {
        $this->expectException(UsuarioJaExisteException::class);

        $this->repositorio
            ->method('buscarPorId')
            ->willReturn(null);

        $this->service->buscarPorId(99);
    }

    public function testBuscarPorEmailNaoEncontrado(): void
    {
        $this->expectException(NotFoundException::class);

        $this->repositorio
            ->method('buscarPorEmail')
            ->willReturn(null);

        $this->service->buscarPorEmail('naoexiste@email.com');
    }

    public function testAtualizarUsuarioComEmailExistente(): void
    {
        $this->expectException(UsuarioJaExisteException::class);

        $dto = DTO::fromArray([
            'nome' => 'Novo Nome',
            'email' => 'existente@email.com',
            'senha' => '',
            'papel' => 'user'
        ]);

        $this->repositorio
            ->method('existePorEmail')
            ->willReturn(true);

        $this->service->atualizar(1, $dto);
    }

    public function testRemoverUsuarioNaoEncontrado(): void
    {
        $this->expectException(NotFoundException::class);

        $this->repositorio
            ->method('buscarPorId')
            ->willReturn(null);

        $this->service->remover(999);
    }

    public function testCriarUsuarioComSucesso(): void
    {
        $dadosArray = [
            'nome' => 'Novo Usuário',
            'email' => 'novo@email.com',
            'senha' => 'Senha123!',
            'papel' => 'user'
        ];

        $dto = DTO::fromArray($dadosArray);

        // Mock do repositório
        $this->repositorio
            ->method('existePorEmail')
            ->willReturn(false);

        $this->repositorio
            ->expects($this->once())
            ->method('criar')
            ->with($this->callback(function (Entity $usuario) use ($dadosArray) {
                return $usuario->getNome() === $dadosArray['nome'] &&
                    $usuario->getEmail() === $dadosArray['email'] &&
                    PasswordManager::verificar($dadosArray['senha'], $usuario->getSenha()) &&
                    $usuario->getPapel() === $dadosArray['papel'];
            }));

        $this->service->criar($dto);
    }

    public function testCriarUsuarioComDadosInvalidos(): void
    {
        $this->expectException(UsuarioInvalidoException::class);

        $dto = DTO::fromArray([
            'nome' => '', // Nome inválido
            'email' => 'emailinvalido',
            'senha' => '123',
            'papel' => 'papelinvalido'
        ]);

        $this->repositorio
            ->method('existePorEmail')
            ->willReturn(false);

        $this->service->criar($dto);
    }

    public function testAtualizarUsuarioComSucesso(): void
    {
        $idUsuario = 1;
        $dadosAtualizacao = DTO::fromArray([
            'nome' => 'Novo Nome',
            'email' => 'novo@email.com',
            'senha' => 'NovaSenha123!',
            'papel' => 'admin'
        ]);

        $usuarioExistente = new Entity(
            'Nome Antigo',
            'antigo@email.com',
            'hash_antigo',
            $idUsuario,
            'user'
        );

        // Mock do repositório
        $this->repositorio
            ->method('existePorEmail')
            ->with('novo@email.com', $idUsuario)
            ->willReturn(false);

        $this->repositorio
            ->method('buscarPorId')
            ->with($idUsuario)
            ->willReturn($usuarioExistente);

        $this->repositorio
            ->expects($this->once())
            ->method('atualizar')
            ->with($idUsuario, $this->callback(function ($dadosAtualizados) {
                return $dadosAtualizados['nome'] === 'Novo Nome' &&
                    $dadosAtualizados['email'] === 'novo@email.com' &&
                    PasswordManager::verificar('NovaSenha123!', $dadosAtualizados['senha']);
            }));

        $this->service->atualizar($idUsuario, $dadosAtualizacao);
    }

    public function testAtualizarUsuarioSemAlterarSenha(): void
    {
        $idUsuario = 1;
        $dadosAtualizacao = DTO::fromArray([
            'nome' => 'Novo Nome',
            'email' => 'novo@email.com',
            'senha' => '', // Senha vazia não deve ser atualizada
            'papel' => 'admin'
        ]);

        $usuarioExistente = new Entity(
            'Nome Antigo',
            'antigo@email.com',
            'hash_antigo',
            $idUsuario,
            'user'
        );

        $this->repositorio
            ->method('existePorEmail')
            ->willReturn(false);

        $this->repositorio
            ->method('buscarPorId')
            ->willReturn($usuarioExistente);

        $this->repositorio
            ->expects($this->once())
            ->method('atualizar')
            ->with($idUsuario, [
                'nome' => 'Novo Nome',
                'email' => 'novo@email.com',
                'senha' => 'hash_antigo' // Senha mantém o hash original
            ]);

        $this->service->atualizar($idUsuario, $dadosAtualizacao);
    }

    public function testAtualizarUsuarioComDadosInvalidos(): void
    {
        $this->expectException(UsuarioInvalidoException::class);

        $idUsuario = 1;
        $dadosInvalidos = DTO::fromArray([
            'nome' => '', // Nome inválido
            'email' => 'emailinvalido',
            'senha' => '123' // Senha fraca
        ]);

        $usuarioExistente = new Entity(
            'Nome Válido',
            'email@valido.com',
            'hash_valido',
            $idUsuario,
            'user'
        );

        $this->repositorio
            ->method('existePorEmail')
            ->willReturn(false);

        $this->repositorio
            ->method('buscarPorId')
            ->willReturn($usuarioExistente);

        $this->service->atualizar($idUsuario, $dadosInvalidos);
    }
}
