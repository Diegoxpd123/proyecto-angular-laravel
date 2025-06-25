<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Detail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'details';

    protected $fillable = [
        'orderid',
        'name',
        'image1',
        'image2',
        'image3',
        'talla',
        'precio',
        'cantidad',
    ];

    /**
     * Relación: Un detalle pertenece a una orden
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'orderid');
    }
}
