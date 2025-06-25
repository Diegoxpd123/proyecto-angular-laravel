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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_documento');
            $table->string('nro_documento');
            $table->foreignId('plan_ventaid')->constrained('plan_ventas')->onDelete('cascade');
            $table->string('razonsocial');
            $table->string('nombre_contacto');
            $table->string('logo')->nullable();
            $table->string('slug')->unique();
            $table->string('direccion');
            $table->string('contacto')->nullable();
            $table->string('telefono');
            $table->string('email');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
