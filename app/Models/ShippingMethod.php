<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'is_active',
        'start_date',
        'end_date',
        'sort',
    ];

    /* Scope “aktif hari ini”  */
    public function scopeActive($q)
    {
        $today = now()->toDateString();

        return $q->where('is_active', 1)
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhereDate('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhereDate('end_date', '>=', $today);
            });
    }
}
