<?php

namespace App\Domain\Usuario;

interface Repository
{
    public function criar(Entity $usuario): void;

    public function buscarPorEmail(string $email): ?Entity;

    public function buscarPorId(int $id): ?Entity;

    public function existePorEmail(string $email): bool;
}
