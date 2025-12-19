<?php

namespace App\Enums;

enum UserRole: string
{
    case TENANT = 'Tenant';
    case HOST = 'Host';
    case ADMIN = 'Admin';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }
}
