<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoCitaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ctl_estado_cita')->insert([
            [
                'id' => 1,
                'nombre' => 'Pendiente',
            ],
            [
                'id' => 2,
                'nombre' => 'Completada',
            ],
            [
                'id' => 3,
                'nombre' => 'Cancelada',
            ],
        ]);
        }
}
