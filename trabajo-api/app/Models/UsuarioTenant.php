<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioTenant extends Model
{
    use HasFactory;
    protected $table = 'usuarios_tenants';
    protected $fillable = ['usuarioid', 'tenantid', 'is_active', 'is_deleted', 'created_at', 'updated_at'];
}
