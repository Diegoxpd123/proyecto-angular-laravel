<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tenancy\Identification\Contracts\Tenant as TenantContract;
use Tenancy\Identification\Concerns\AllowsTenantIdentification;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Model implements TenantContract
{
    use AllowsTenantIdentification, SoftDeletes;

    use HasFactory;

    protected $fillable = [
        'tipo_documento',
        'nro_documento',
        'plan_ventaid',
        'razonsocial',
        'nombre_contacto',
        'logo',
        'slug',
        'direccion',
        'contacto',
        'telefono',
        'email',
    ];

    protected $connection = 'mysql'; // conexión central

    public function getTenantKeyName(): string
    {
        return 'id'; // clave primaria de la tabla
    }

    public function getTenantKey(): string
    {
        return (string) $this->getAttribute($this->getTenantKeyName());
    }

    // Relaciones
    public function plan_venta()
    {
        return $this->belongsTo(PlanVenta::class, 'plan_ventaid');
    }
}
