<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property string|null $category
 * @property string|null $question
 * @property string|null $answer
 * @property bool $status
 * @property int|null $order
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder|Faq newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Faq newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Faq query()
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereOrder($value)
 * @mixin \Eloquent
 */
class Faq extends Model
{
    protected $table = 'faq';
    public $timestamps = false;

    protected $fillable = [
        'category',
        'question',
        'answer',
        'status',
        'order',
        'created_at',
        'interface_id'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Relasi ke model Interface (pastikan ada model InterfaceModel).
     */
    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }

    /**
     * Scope: FAQ Aktif dan Sesuai Interface
     */
    public function scopeAktif($query, $interfaceId = 8)
    {
        return $query->where('status', true)
                     ->where('interface_id', $interfaceId)
                     ->orderBy('order');
    }
}
