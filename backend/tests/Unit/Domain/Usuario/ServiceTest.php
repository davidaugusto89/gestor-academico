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
}
