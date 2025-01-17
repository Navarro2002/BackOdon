<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CtlEstadoCita;
use Illuminate\Http\Request;

class CatalogosController extends Controller
{
    public function listadoEstadoCita() {
        $estado = CtlEstadoCita::get();
        return $estado;
    }
}
