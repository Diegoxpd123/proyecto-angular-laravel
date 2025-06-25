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
        Schema::create('product_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('productoid')->constrained('products')->onDelete('cascade');
            $table->string('talla', 100);
            $table->integer('stock');
            $table->integer('cantidadmayor')->nullable();
            $table->float('precio');
            $table->float('preciomayor')->nullable();
            $table->float('preciosale')->nullable();
            $table->integer('issale');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
