<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

     use HasFactory;

        protected $table = 'orders';
        protected $fillable = ['cliente_id','voucher_type','voucher_number','voucher_pdf','created_at','updated_at','is_deleted','','is_actived'];

}
