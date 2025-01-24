<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CtlDiasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dias = [
            ['nombre' => 'Lunes', 'activo' => true],
            ['nombre' => 'Martes', 'activo' => true],
            ['nombre' => 'Miércoles', 'activo' => true],
            ['nombre' => 'Jueves', 'activo' => true],
            ['nombre' => 'Viernes', 'activo' => true],
            ['nombre' => 'Sábado', 'activo' => true],
            ['nombre' => 'Domingo', 'activo' => true],
        ];

        DB::table('ctl_dias')->insert($dias);    }
}
