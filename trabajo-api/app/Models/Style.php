<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Style extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'styles';


    protected $fillable = [
        'name',
        'descripcion',
        'genreid',
    ];

    /**
     * Relación: un estilo pertenece a un género
     */
    public function genre()
    {
        return $this->belongsTo(Genre::class, 'genreid');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'styleid');
    }
}
