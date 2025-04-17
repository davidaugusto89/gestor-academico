<?php

namespace App\Domain\Usuario;

enum Papel: string
{
    case ADMIN = 'admin';
    case USER = 'user';
}
