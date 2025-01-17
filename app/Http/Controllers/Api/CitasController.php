<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MntCitas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CitasController extends Controller
{
    public function index(Request $request)
    {
        $id_estado = $request->input('id_estado', '');

        if ($id_estado != "") {
            $doctores = MntCitas::with('doctor', 'paciente', 'estado')->where('id_estado', $id_estado)->paginate(10);
        } else {
            $doctores = MntCitas::with('doctor', 'paciente', 'estado')->paginate(10);
        }

        return response()->json($doctores);
    }


    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_paciente' => 'required|integer|exists:mnt_pacientes,id',
                'id_doctor' => 'required|integer|exists:mnt_doctores,id',
                'id_estado' => 'required|integer|exists:ctl_estado_cita,id',
                'fecha_hora' => 'required|date|after:today',
                'descripcion' => 'required|nullable|string|max:255',
            ], [
                'id_paciente.required' => 'El ID del paciente es obligatorio.',
                'id_paciente.integer' => 'El ID del paciente debe ser un número entero.',
                'id_paciente.exists' => 'El paciente seleccionado no existe.',
                'id_doctor.required' => 'El ID del doctor es obligatorio.',
                'id_doctor.integer' => 'El ID del doctor debe ser un número entero.',
                'id_doctor.exists' => 'El doctor seleccionado no existe.',
                'id_estado.required' => 'El estado es obligatorio.',
                'id_estado.integer' => 'El estado debe ser un número entero.',
                'id_estado.exists' => 'El estado seleccionado no existe.',
                'fecha_hora.required' => 'La fecha y hora son obligatorias.',
                'fecha_hora.date' => 'La fecha y hora deben ser una fecha válida.',
                'fecha_hora.after' => 'La fecha y hora deben ser posteriores a la fecha actual.',
                'descripcion.string' => 'La descripción debe ser una cadena de texto.',
                'descripcion.max' => 'La descripción no puede tener más de 255 caracteres.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Datos inválidos',
                    'messages' => $validator->errors()
                ], 400);
            }

            $cita = MntCitas::create([
                'id_paciente' => $request->id_paciente,
                'id_doctor' => $request->id_doctor,
                'id_estado' => $request->id_estado,
                'fecha_hora' => $request->fecha_hora,
                'descripcion' => $request->descripcion,
            ]);

            return response()->json([
                'message' => 'Cita creada exitosamente.',
                'data' => $cita,
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error al crear la cita',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id_paciente' => 'required|integer|exists:mnt_pacientes,id',
                'id_doctor' => 'required|integer|exists:mnt_doctores,id',
                'id_estado' => 'required|integer|exists:ctl_estado_cita,id',
                'fecha_hora' => 'required|date|after:today',
                'descripcion' => 'nullable|string|max:255',
            ], [
                'id_paciente.required' => 'El ID del paciente es obligatorio.',
                'id_paciente.integer' => 'El ID del paciente debe ser un número entero.',
                'id_paciente.exists' => 'El paciente seleccionado no existe.',
                'id_doctor.required' => 'El ID del doctor es obligatorio.',
                'id_doctor.integer' => 'El ID del doctor debe ser un número entero.',
                'id_doctor.exists' => 'El doctor seleccionado no existe.',
                'id_estado.required' => 'El estado es obligatorio.',
                'id_estado.integer' => 'El estado debe ser un número entero.',
                'id_estado.exists' => 'El estado seleccionado no existe.',
                'fecha_hora.required' => 'La fecha y hora son obligatorias.',
                'fecha_hora.date' => 'La fecha y hora deben ser una fecha válida.',
                'fecha_hora.after' => 'La fecha y hora deben ser posteriores a la fecha actual.',
                'descripcion.string' => 'La descripción debe ser una cadena de texto.',
                'descripcion.max' => 'La descripción no puede tener más de 255 caracteres.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Datos inválidos',
                    'messages' => $validator->errors()
                ], 400);
            }

            $cita = MntCitas::find($id);

            if (!$cita) {
                return response()->json([
                    'error' => 'Cita no encontrada'
                ], 404);
            }

            $cita->update([
                'id_paciente' => $request->id_paciente,
                'id_doctor' => $request->id_doctor,
                'id_estado' => $request->id_estado,
                'fecha_hora' => $request->fecha_hora,
                'descripcion' => $request->descripcion,
            ]);

            return response()->json([
                'message' => 'Cita actualizada exitosamente.',
                'data' => $cita,
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar la cita',
                'message' => $e->getMessage()
            ], 500);
        }
    }


}
