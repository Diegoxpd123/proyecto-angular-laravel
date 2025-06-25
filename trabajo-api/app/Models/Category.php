<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'descripcion',
        'styleid',
    ];

    /**
     * Relación: una categoría pertenece a un estilo
     */
    public function style()
    {
        return $this->belongsTo(Style::class, 'styleid');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'categoriaid');
    }
}
