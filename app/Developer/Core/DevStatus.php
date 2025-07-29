<?php
namespace App\Developer\Core;

enum DevStatus: string
{
    case ACTIVE = 'ACTIVE';
    case INACTIVE = 'INACTIVE';
    case ON_LEAVE = 'ON_LEAVE';
    case TERMINATED = 'TERMINATED';
}
