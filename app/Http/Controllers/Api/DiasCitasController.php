<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MntCitas;
use App\Models\MntDiasCitas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DiasCitasController extends Controller
{
    public function crear(Request $request){

        try {
            $fecha = Carbon::parse($request->fecha);
            $diaCita = $fecha->day;
            $mesCita = $fecha->month;
            $anioCita = $fecha->year;

            $doctorNoDisponible = MntCitas::where('fecha', $request->fecha)
                ->where('id_horario_cita', $request->id_horario_cita)
                ->where('id_doctor', $request->id_doctor)
                ->exists();

            if ($doctorNoDisponible) {
                return response()->json([
                    'message' => 'Cita no disponible.',
                ], 400);
            }

            $buscarCita = MntDiasCitas::where('dia', $diaCita)
                ->where('mes', $mesCita)
                ->where('anio', $anioCita)
                ->first();
            if ($buscarCita) {
                $buscarCita->citas_disponibles  = $buscarCita->citas_disponibles - 1;
                $buscarCita->save();
            }

            $data = new MntDiasCitas;
            $data->id_dia = $request->id_dia;
            $data->dia = $diaCita;
            $data->mes = $mesCita;
            $data->anio = $anioCita;
            $data->cantidad_citas = 16;
            $data->citas_disponibles = 15;
            $data->intervalo_minutos = 30;
            $data->activo = true;
            $data->save();

            // Crear cita 
            $newCita = new MntCitas;
            $newCita->id_paciente = $request->id_paciente;
            $newCita->id_doctor = $request->id_doctor;
            $newCita->id_estado = 1;
            $newCita->id_horario_cita = $request->id_horario_cita;
            $newCita->id_dia_cita = $buscarCita  ? $buscarCita->id : $data->id;
            $newCita->fecha = $request->fecha;
            $newCita->descripcion = $request->descripcion;
            $newCita->activo = true;
            $newCita->save();
                } catch (\Exception $e) {
        
        }
        

        return response()->json([
            'message' => 'Cita creada exitosamente.',
            'data' => $newCita,
        ], 201);
    }
}
