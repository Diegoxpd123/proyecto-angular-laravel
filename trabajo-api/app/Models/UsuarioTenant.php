<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsuarioTenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tenant_user'; // nombre de la tabla en la migración

    protected $fillable = [
        'tenant_id',
        'user_id',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
