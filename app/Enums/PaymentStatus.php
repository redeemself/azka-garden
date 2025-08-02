<?php

namespace App\Enums;

/**
 * Payment Status Enum
 *
 * Created: 2025-08-02 00:44:34
 * Author: gerrymulyadi709
 */
enum PaymentStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';

    /**
     * Get a human-readable label for the status
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Menunggu Pembayaran',
            self::PROCESSING => 'Sedang Diproses',
            self::COMPLETED => 'Pembayaran Selesai',
            self::FAILED => 'Gagal',
            self::CANCELLED => 'Dibatalkan',
            self::REFUNDED => 'Dana Dikembalikan',
        };
    }

    /**
     * Get the color class for this status
     */
    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'bg-yellow-100 text-yellow-800',
            self::PROCESSING => 'bg-blue-100 text-blue-800',
            self::COMPLETED => 'bg-green-100 text-green-800',
            self::FAILED => 'bg-red-100 text-red-800',
            self::CANCELLED => 'bg-gray-100 text-gray-800',
            self::REFUNDED => 'bg-purple-100 text-purple-800',
        };
    }

    /**
     * Get icon name for this status
     */
    public function icon(): string
    {
        return match ($this) {
            self::PENDING => 'clock',
            self::PROCESSING => 'refresh',
            self::COMPLETED => 'check-circle',
            self::FAILED => 'x-circle',
            self::CANCELLED => 'ban',
            self::REFUNDED => 'credit-card',
        };
    }
}
