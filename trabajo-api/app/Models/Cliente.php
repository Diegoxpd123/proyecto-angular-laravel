<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clientes';

    protected $fillable = [
        'tipo_documento',
        'numero_documento',
        'nombre',
        'telefono',
        'email',
        'direccion',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'clienteid');
    }
}
