<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CtlDias extends Model
{
    protected $table = 'ctl_dias';

    protected $fillable = [
        'nombre',
        'activo',
    ];
}
