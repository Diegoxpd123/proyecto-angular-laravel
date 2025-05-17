<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{

     use HasFactory;

        protected $table = 'details';
        protected $fillable = ['order_id','book_id','price','quantity','created_at','updated_at','is_deleted','','is_actived'];


}
