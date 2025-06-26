<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlanVenta extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'plan_ventas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'dias_prueba',
        'modulos',
        'limite_productos',
    ];

    public function tenants()
    {
        return $this->hasMany(Tenant::class, 'plan_ventaid');
    }
}
