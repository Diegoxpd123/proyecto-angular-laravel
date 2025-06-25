<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'categoriaid',
        'name',
        'image1',
        'image2',
        'image3',
    ];

    /**
     * Relación: un producto puede pertenecer a una categoría
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'categoriaid');
    }

    public function detalles()
    {
        return $this->hasMany(ProductDetail::class, 'productoid');
    }
}
