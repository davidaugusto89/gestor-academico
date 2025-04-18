<?php

namespace Tests\Unit\Domain\Usuario;

use PHPUnit\Framework\TestCase;
use App\Domain\Usuario\Entity;
use App\Domain\Usuario\Papel;

class UsuarioEntityTest extends TestCase
{
    public function testEntityAttributesAreSetCorrectly(): void
    {
        $entity = new Entity('Maria', 'maria@email.com', 'senha123', 1, Papel::ADMIN->value);

        $this->assertEquals(1, $entity->getId());
        $this->assertEquals('Maria', $entity->getNome());
        $this->assertEquals('maria@email.com', $entity->getEmail());
        $this->assertEquals('senha123', $entity->getSenha());
        $this->assertEquals(Papel::ADMIN->value, $entity->getPapel());
    }

    public function testSettersUpdateValuesCorrectly(): void
    {
        $entity = new Entity('João', 'joao@email.com', 'senha', null);

        $entity->setNome('Carlos');
        $entity->setEmail('carlos@email.com');
        $entity->setSenha('novaSenha');
        $entity->setPapel('admin');

        $this->assertEquals('Carlos', $entity->getNome());
        $this->assertEquals('carlos@email.com', $entity->getEmail());
        $this->assertEquals('novaSenha', $entity->getSenha());
        $this->assertEquals(Papel::ADMIN->value, $entity->getPapel());
    }

    public function testSetPapelDefaultsToUserIfInvalid(): void
    {
        $entity = new Entity('Ana', 'ana@email.com', 'senha');

        $entity->setPapel('qualquer_coisa');

        $this->assertEquals(Papel::USER->value, $entity->getPapel());
    }

    public function testJsonSerializeReturnsExpectedArray(): void
    {
        $entity = new Entity('Lucas', 'lucas@email.com', '1234', 42, Papel::ADMIN->value);

        $expected = [
            'id' => 42,
            'nome' => 'Lucas',
            'email' => 'lucas@email.com',
            'papel' => Papel::ADMIN->value,
        ];

        $this->assertEquals($expected, $entity->jsonSerialize());
    }
}
