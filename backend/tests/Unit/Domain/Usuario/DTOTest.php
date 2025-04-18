<?php

namespace Tests\Unit\Domain\Usuario;

use PHPUnit\Framework\TestCase;
use App\Domain\Usuario\DTO;

class UsuarioDTOTest extends TestCase
{
    public function testFromArrayCreatesDTOWithCorrectValues(): void
    {
        $dados = [
            'nome' => ' João ',
            'email' => ' joao@email.com ',
            'senha' => ' 123456 ',
            'papel' => ' admin '
        ];

        $dto = DTO::fromArray($dados);

        $this->assertInstanceOf(DTO::class, $dto);
        $this->assertEquals('João', $dto->getNome());
        $this->assertEquals('joao@email.com', $dto->getEmail());
        $this->assertEquals('123456', $dto->getSenha());
        $this->assertEquals('admin', $dto->getPapel());
    }

    public function testFromArrayHandlesMissingFields(): void
    {
        $dto = DTO::fromArray([]);

        $this->assertEquals('', $dto->getNome());
        $this->assertEquals('', $dto->getEmail());
        $this->assertEquals('', $dto->getSenha());
        $this->assertEquals('', $dto->getPapel());
    }
}
