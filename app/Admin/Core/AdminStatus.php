<?php
namespace App\Admin\Core;

enum AdminStatus: string
{
    case ACTIVE = 'ACTIVE';
    case INACTIVE = 'INACTIVE';
    case SUSPENDED = 'SUSPENDED';
    case DELETED = 'DELETED';
}
