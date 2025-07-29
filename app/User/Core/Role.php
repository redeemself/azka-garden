<?php
namespace App\User\Core;

enum Role: string
{
    case CUSTOMER = 'CUSTOMER';
    case ADMIN = 'ADMIN';
    case DEVELOPER = 'DEVELOPER';
}
