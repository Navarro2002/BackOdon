<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up():void
    {
        Schema::create('mnt_horarios_citas', function (Blueprint $table) {
            $table->id();
            $table->time('hora_inicio'); // Hora de inicio de la cita
            $table->time('hora_fin'); // Hora de fin de la cita
            $table->integer('numero_cita'); // Número de la cita
            $table->boolean('activo')->default(true); // Horario activo o no
            $table->timestamps();
        });
    }

    public function down():void
    {
        Schema::dropIfExists('mnt_horarios_citas');
    }
};
