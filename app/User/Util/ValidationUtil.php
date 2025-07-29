<?php
namespace App\User\Util;

class ValidationUtil
{
    public static function isEmailValid(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function isPhoneValid(string $phone): bool
    {
        return preg_match('/^\+?[0-9\s\-]+$/', $phone) === 1;
    }
}
