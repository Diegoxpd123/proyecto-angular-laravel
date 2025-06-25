<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'clienteid',
        'total',
    ];

    /**
     * Relación: Una orden pertenece a un cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'clienteid');
    }
    public function details()
    {
        return $this->hasMany(Detail::class, 'orderid');
    }
}
