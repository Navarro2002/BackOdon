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
        Schema::create('mnt_citas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_paciente');
            $table->unsignedBigInteger('id_doctor');
            $table->unsignedBigInteger('id_estado');
            $table->dateTime('fecha_hora');
            $table->string('descripcion');
            $table->timestamps();
            $table->foreign('id_paciente')->references('id')->on('mnt_pacientes');
            $table->foreign('id_doctor')->references('id')->on('mnt_doctores');
            $table->foreign('id_estado')->references('id')->on('ctl_estado_cita');
            $table->boolean('activo')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mnt_citas');
    }
};
