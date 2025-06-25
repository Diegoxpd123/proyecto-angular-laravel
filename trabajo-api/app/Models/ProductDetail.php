<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_detail';

    protected $fillable = [
        'productoid',
        'talla',
        'stock',
        'cantidadmayor',
        'precio',
        'preciomayor',
        'preciosale',
        'issale',
    ];

    /**
     * Relación: un detalle pertenece a un producto
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'productoid');
    }
}
