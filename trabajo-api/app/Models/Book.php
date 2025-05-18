<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
     use HasFactory;

        protected $table = 'books';
        protected $fillable = ['isbn','name','stock','price','image','created_at','updated_at','is_deleted','is_actived'];

}
