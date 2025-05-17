<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{

        use HasFactory;
        protected $table = 'clientes';
        protected $fillable = ['doc_type','doc_number','first_name','last_name','phone','email','is_active','is_deleted','created_at','updated_at'];
}
