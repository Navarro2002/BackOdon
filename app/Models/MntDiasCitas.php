<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MntDiasCitas extends Model
{
    protected $table = 'mnt_dias_citas';

    protected $fillable = [
        'id_dia',
        'cantidad_citas',
        'dia',
        'mes',
        'anio',
        'citas_disponibles',
        'intervalo_minutos',
        'activo',
    ];

    public $timestamps = true;

    public function doctor()
    {
        return $this->belongsTo(CtlDias::class, 'id_dia');
    }
}
