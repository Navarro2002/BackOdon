<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MntPacientes;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PacientesController extends Controller
{
    public function index(Request $request)
    {
        // Crear la extensión unaccent si no existe
        DB::statement("DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_extension WHERE extname = 'unaccent') THEN
        CREATE EXTENSION unaccent;
    END IF;
    END $$;");

        $query = MntPacientes::query();

        if ($request->has('search') && $request->search) {
            // Divide el término de búsqueda en palabras individuales y aplica `unaccent`
            $searchTerms = explode(' ', trim(mb_strtolower($request->search, 'UTF-8')));

            $query->where(function ($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->orWhereRaw("unaccent(nombre) ILIKE unaccent(?)", ['%' . $term . '%'])
                    ->orWhereRaw("unaccent(apellido) ILIKE unaccent(?)", ['%' . $term . '%'])
                    ->orWhereRaw("unaccent(nombre || ' ' || apellido) ILIKE unaccent(?)", ['%' . $term . '%']);
                }
            });
        }

        // Ordena los resultados y realiza la paginación
        $pacientes = $query->orderBy('id', 'asc')->paginate(10);

        return response()->json($pacientes);
    }




    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'edad' => 'required|integer',
                'fecha_nacimiento' => 'required|date',
                'telefono' => 'nullable|string',
                'email' => 'nullable|email|unique:mnt_pacientes,email',
            ], [
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.string' => 'El nombre debe ser una cadena de texto.',
                'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
                'apellido.required' => 'El apellido es obligatorio.',
                'apellido.string' => 'El apellido debe ser una cadena de texto.',
                'apellido.max' => 'El apellido no puede tener más de 255 caracteres.',
                'edad.required' => 'La edad es obligatoria.',
                'edad.integer' => 'La edad debe ser un número entero.',
                'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
                'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida.',
                'telefono.string' => 'El teléfono debe ser una cadena de texto.',
                'email.email' => 'El correo electrónico debe ser una dirección válida.',
                'email.unique' => 'El correo electrónico ya está registrado en el sistema.',
            ]);


            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Datos inválidos',
                    'messages' => $validator->errors()
                ], 400);
            }

            $paciente = MntPacientes::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'edad' => $request->edad,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'telefono' => $request->telefono,
                'email' => $request->email,
            ]);

            return response()->json([
                'message' => 'Paciente creado exitosamente.',
                'data' => $paciente,
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error al crear el paciente',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'edad' => 'required|integer',
                'fecha_nacimiento' => 'required|date',
                'telefono' => 'nullable|string',
                'email' => 'nullable|email|unique:mnt_pacientes,email,' . $id,
            ], [
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.string' => 'El nombre debe ser una cadena de texto.',
                'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
                'apellido.required' => 'El apellido es obligatorio.',
                'apellido.string' => 'El apellido debe ser una cadena de texto.',
                'apellido.max' => 'El apellido no puede tener más de 255 caracteres.',
                'edad.required' => 'La edad es obligatoria.',
                'edad.integer' => 'La edad debe ser un número entero.',
                'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
                'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida.',
                'telefono.string' => 'El teléfono debe ser una cadena de texto.',
                'email.email' => 'El correo electrónico debe ser una dirección válida.',
                'email.unique' => 'El correo electrónico ya está registrado en el sistema.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Datos inválidos',
                    'messages' => $validator->errors()
                ], 400);
            }

            $paciente = MntPacientes::find($id);

            if (!$paciente) {
                return response()->json([
                    'error' => 'Paciente no encontrado'
                ], 404);
            }

            $paciente->update([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'edad' => $request->edad,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'telefono' => $request->telefono,
                'email' => $request->email,
            ]);

            return response()->json([
                'message' => 'Paciente actualizado exitosamente.',
                'data' => $paciente,
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar el paciente',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function cambiarEstado($id)
    {
        try {
            $paciente = MntPacientes::find($id);

            if (!$paciente) {
                return response()->json([
                    'error' => 'Paciente no encontrado'
                ], 404);
            }

            $paciente->activo = !$paciente->activo;
            $paciente->save();

            $message = $paciente->activo ? 'Paciente activado correctamente.' : 'Paciente desactivado correctamente.';

            return response()->json([
                'message' => $message,
                'activo' => $paciente->activo
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error al cambiar el estado del paciente',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function detalle($id)
    {
        $detalle = MntPacientes::where('id', $id)->first();
        return response()->json($detalle);
    }


}
