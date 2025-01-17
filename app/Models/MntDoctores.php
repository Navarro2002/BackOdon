<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MntDoctores extends Model
{
    protected $table = 'mnt_doctores';

    protected $fillable = [
        'nombre',
        'apellido',
        'especialidad',
        'telefono',
        'email',
    ];

    public $timestamps = true;
}
