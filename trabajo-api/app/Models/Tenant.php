<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tenancy\Affects\Connections\ConnectionResolver;
use Tenancy\Identification\Contracts\Tenant as TenantContract;
use Tenancy\Identification\Concerns\AllowsTenantIdentification;

class Tenant extends Model implements TenantContract
{
    use AllowsTenantIdentification;

    protected $fillable = ['tid', 'name', 'database'];
    protected $connection = 'mysql';

    public function getTenantKeyName(): string
    {
        return 'tid'; // El campo clave identificadora del tenant
    }

    public function getTenantKey(): string
    {
        return (string) $this->getAttribute($this->getTenantKeyName());
    }
}
