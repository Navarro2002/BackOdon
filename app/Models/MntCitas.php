<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MntCitas extends Model
{
    protected $table = 'mnt_citas';

    protected $fillable = [
        'id_paciente',
        'id_doctor',
        'id_estado',
        'fecha_hora',
        'descripcion',
    ];

    public $timestamps = true;

    public function doctor()
    {
        return $this->belongsTo(MntDoctores::class, 'id_doctor');
    }

    public function paciente()
    {
        return $this->belongsTo(MntPacientes::class, 'id_paciente');
    }

    public function estado()
    {
        return $this->belongsTo(CtlEstadoCita::class, 'id_estado');
    }
}
