<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mnt_dias_citas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_dia');
            $table->integer('dia')->default(0); 
            $table->integer('mes')->default(0); 
            $table->integer('anio')->default(0);
            $table->integer('cantidad_citas')->default(0); // Total de citas programadas
            $table->integer('citas_disponibles')->default(0); // Citas disponibles
            $table->integer('intervalo_minutos'); // Intervalo en minutos entre citas
            $table->boolean('activo')->default(true); // Día activo o no
            $table->timestamps();
            $table->foreign('id_dia')->references('id')->on('ctl_dias');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mnt_dias_citas');
    }
};
