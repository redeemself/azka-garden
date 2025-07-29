<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterfaceModel extends Model
{
    protected $table = 'interfaces';
    public $timestamps = false;

    protected $fillable = ['name', 'description'];

    public function methods()
    {
        return $this->hasMany(InterfaceMethod::class, 'interface_id');
    }
}
