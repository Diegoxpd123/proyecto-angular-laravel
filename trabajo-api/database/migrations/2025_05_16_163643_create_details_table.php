<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('book_id');
            $table->float('price');
            $table->integer('quantity');
            $table->date('created_at');
            $table->date('updated_at');
            $table->integer('is_deleted');
            $table->integer('is_actived');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('details');
    }
};
