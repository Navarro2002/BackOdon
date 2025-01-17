<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CtlEstadoCita extends Model
{
    protected $table = 'ctl_estado_cita';

    protected $fillable = [
        'nombre',
    ];

    public $timestamps = true;
}
