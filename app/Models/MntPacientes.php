<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MntPacientes extends Model
{
    use HasFactory;

    protected $table = 'mnt_pacientes';

    protected $fillable = [
        'nombre',
        'apellido',
        'edad',
        'fecha_nacimiento',
        'telefono',
        'email',
    ];

    public $timestamps = true;

    // Relación con las citas (si aplica)
    // public function citas()
    // {
    //     return $this->hasMany(MntCita::class, 'paciente_id'); // Asume que existe una tabla 'citas' y una relación con 'paciente_id'
    // }
}
