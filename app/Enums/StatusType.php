<?php

namespace App\Enums;

enum StatusType : string
{
    case NEW = 'New';
    case PENDING = 'Pending';
    case REJECTED = 'Rejected';
    case APPROVED = 'Approved';
    case SUSPENDED = 'Suspended';
    case DELETED = 'Deleted';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }
}
