<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MntHorariosCitas extends Model
{
    protected $table = 'mnt_horarios_citas';

    protected $fillable = [
        'hora_inicio',
        'hora_fin',
        'numero_cita',
        'activo',
    ];
}
