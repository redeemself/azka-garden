<?php
namespace App\User\Util;

class DateUtil
{
    public static function formatDate(\DateTime $date, string $format = 'Y-m-d H:i:s'): string
    {
        return $date->format($format);
    }

    public static function now(): \DateTime
    {
        return new \DateTime();
    }
}
