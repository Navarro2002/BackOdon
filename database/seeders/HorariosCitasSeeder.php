<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HorariosCitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $horaInicio = Carbon::createFromTime(8, 0); 
        $horaFin = Carbon::createFromTime(16, 0);  
        $intervalo = 30; 
        $numeroCita = 1;

        $horarios = [];

        while ($horaInicio->lt($horaFin)) {
            $horarios[] = [
                'hora_inicio' => $horaInicio->format('H:i'),
                'hora_fin' => $horaInicio->copy()->addMinutes($intervalo)->format('H:i'),
                'numero_cita' => $numeroCita,
                'activo' => true,
                'created_at' => now(), 
                'updated_at' => now(),
            ];

            $horaInicio->addMinutes($intervalo);
            $numeroCita++;
        }

        DB::table('mnt_horarios_citas')->insert($horarios);    }
}
