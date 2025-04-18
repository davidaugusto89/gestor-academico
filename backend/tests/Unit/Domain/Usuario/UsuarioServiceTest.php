<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Usuario\Service;
use App\Domain\Usuario\Entity;
use App\Domain\Usuario\DTO;
use App\Domain\Usuario\Repository;
use App\Domain\Usuario\Validator;
use App\Domain\Usuario\Exceptions\UsuarioInvalidoException;
use App\Domain\Usuario\Exceptions\UsuarioJaExisteException;
use App\Core\Exceptions\NotFoundException;
use App\Support\PasswordManager;

class UsuarioServiceTest extends TestCase
{
    private Repository $repositorio;
    private Validator $validator;
    private Service $service;

    protected function setUp(): void
    {
        $this->repositorio = $this->createMock(Repository::class);
        $this->validator = $this->createMock(Validator::class);
        $this->service = new Service($this->repositorio, $this->validator);
    }

    public function testAutenticarComCredenciaisValidas()
    {
        $senhaHash = PasswordManager::gerarHash('Senha@123');
        $usuario = new Entity('Admin', 'admin@fiap.com', $senhaHash, 1, 'admin');

        $this->repositorio->method('buscarPorEmail')->willReturn($usuario);

        $resultado = $this->service->autenticar('admin@fiap.com', 'Senha@123');

        $this->assertInstanceOf(Entity::class, $resultado);
    }

    public function testAutenticarComCredenciaisInvalidasLancaExcecao()
    {
        $this->expectException(UsuarioInvalidoException::class);
        $this->repositorio->method('buscarPorEmail')->willReturn(null);

        $this->service->autenticar('x@email.com', 'SenhaErrada');
    }

    public function testBuscarPorIdComSucesso()
    {
        $usuario = new Entity('Nome', 'email@email.com', 'hash', 1, 'admin');
        $this->repositorio->method('buscarPorId')->willReturn($usuario);

        $resultado = $this->service->buscarPorId(1);

        $this->assertInstanceOf(Entity::class, $resultado);
    }

    public function testBuscarPorIdNaoEncontradoLancaExcecao()
    {
        $this->expectException(UsuarioJaExisteException::class);
        $this->repositorio->method('buscarPorId')->willReturn(null);

        $this->service->buscarPorId(99);
    }

    public function testBuscarPorEmailComSucesso()
    {
        $usuario = new Entity('Nome', 'email@email.com', 'hash', 1, 'admin');
        $this->repositorio->method('buscarPorEmail')->willReturn($usuario);

        $resultado = $this->service->buscarPorEmail('email@email.com');

        $this->assertInstanceOf(Entity::class, $resultado);
    }

    public function testBuscarPorEmailNaoEncontradoLancaExcecao()
    {
        $this->expectException(NotFoundException::class);
        $this->repositorio->method('buscarPorEmail')->willReturn(null);

        $this->service->buscarPorEmail('inexistente@email.com');
    }

    public function testAtualizarEmailDuplicadoLancaExcecao()
    {
        $this->expectException(UsuarioJaExisteException::class);

        $dto = DTO::fromArray([
            'nome' => 'João',
            'email' => 'joao@email.com',
            'senha' => 'Senha@123',
            'papel' => 'admin'
        ]);

        $this->repositorio->method('existePorEmail')->willReturn(true);

        $this->service->atualizar(1, $dto);
    }

    public function testRemoverUsuarioComSucesso()
    {
        $usuario = new Entity('Fulano', 'fulano@email.com', 'hash', 10, 'admin');

        $this->repositorio->method('buscarPorId')->willReturn($usuario);
        $this->repositorio->expects($this->once())->method('remover')->with(10);

        $this->service->remover(10);
    }

    public function testRemoverUsuarioInexistenteLancaExcecao()
    {
        $this->expectException(NotFoundException::class);
        $this->repositorio->method('buscarPorId')->willReturn(null);

        $this->service->remover(404);
    }
}
