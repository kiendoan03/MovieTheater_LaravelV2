<?php

namespace App\Enums;

enum UserRole: int
{
    case Admin = 0;
    case Staff = 1;
    case Customer = 2;
}
