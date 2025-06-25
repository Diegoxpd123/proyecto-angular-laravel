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
            $table->foreignId('orderid')->constrained('orders')->onDelete('cascade');
            $table->string('name', 100);
            $table->string('image1', 250);
            $table->string('image2', 250);
            $table->string('image3', 250);
            $table->string('talla', 100);
            $table->float('precio');
            $table->integer('cantidad');
            $table->timestamps();
            $table->softDeletes();
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
