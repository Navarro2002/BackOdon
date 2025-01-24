<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MntDoctores;
use App\Models\MntPacientes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DoctoresController extends Controller
{
    public function index(Request $request)
    {
        // Verificar si la extensión 'unaccent' existe
        DB::statement("DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_extension WHERE extname = 'unaccent') THEN
        CREATE EXTENSION unaccent;
    END IF;
    END $$;");

        $query = MntDoctores::query();

        // Filtro combinado por nombre o apellido insensible a mayúsculas, minúsculas y tildes
        if ($request->has('search') && $request->search) {
            $searchTerms = explode(' ', trim(mb_strtolower($request->search, 'UTF-8')));

            // Usamos unaccent() para quitar tildes y LOWER para hacer la búsqueda insensible a mayúsculas/minúsculas
            $query->where(function ($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->orWhereRaw("unaccent(nombre) ILIKE unaccent(?)", ['%' . $term . '%'])
                        ->orWhereRaw("unaccent(apellido) ILIKE unaccent(?)", ['%' . $term . '%'])
                        ->orWhereRaw("unaccent(nombre || ' ' || apellido) ILIKE unaccent(?)", ['%' . $term . '%']);
                }
            });
        }

        // Obtener los resultados paginados
        $doctores = $query->orderBy('id', 'asc')->paginate(10);

        return response()->json($doctores);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'especialidad' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:mnt_doctores,email',
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string' => 'El campo nombre debe ser una cadena de texto.',
            'nombre.max' => 'El campo nombre no debe exceder los 255 caracteres.',
            'apellido.required' => 'El campo apellido es obligatorio.',
            'apellido.string' => 'El campo apellido debe ser una cadena de texto.',
            'apellido.max' => 'El campo apellido no debe exceder los 255 caracteres.',
            'especialidad.required' => 'El campo especialidad es obligatorio.',
            'especialidad.string' => 'El campo especialidad debe ser una cadena de texto.',
            'especialidad.max' => 'El campo especialidad no debe exceder los 255 caracteres.',
            'telefono.string' => 'El campo teléfono debe ser una cadena de texto.',
            'telefono.max' => 'El campo teléfono no debe exceder los 15 caracteres.',
            'email.email' => 'El campo correo electrónico debe ser una dirección válida.',
            'email.unique' => 'El correo electrónico ya está registrado en el sistema.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Datos inválidos.',
                'messages' => $validator->errors(),
            ], 400);
        }

        try {
            $doctor = MntDoctores::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'especialidad' => $request->especialidad,
                'telefono' => $request->telefono,
                'email' => $request->email,
            ]);

            return response()->json([
                'message' => 'Doctor creado exitosamente.',
                'data' => $doctor,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al crear el doctor.',
                'exception' => $e->getMessage(), // Esto es útil para depuración
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'especialidad' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:mnt_doctores,email,' . $id,
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string' => 'El campo nombre debe ser una cadena de texto.',
            'nombre.max' => 'El campo nombre no debe exceder los 255 caracteres.',
            'apellido.required' => 'El campo apellido es obligatorio.',
            'apellido.string' => 'El campo apellido debe ser una cadena de texto.',
            'apellido.max' => 'El campo apellido no debe exceder los 255 caracteres.',
            'especialidad.required' => 'El campo especialidad es obligatorio.',
            'especialidad.string' => 'El campo especialidad debe ser una cadena de texto.',
            'especialidad.max' => 'El campo especialidad no debe exceder los 255 caracteres.',
            'telefono.string' => 'El campo teléfono debe ser una cadena de texto.',
            'telefono.max' => 'El campo teléfono no debe exceder los 15 caracteres.',
            'email.email' => 'El campo correo electrónico debe ser una dirección válida.',
            'email.unique' => 'El correo electrónico ya está registrado en el sistema.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Datos inválidos.',
                'messages' => $validator->errors(),
            ], 400);
        }

        try {
            $doctor = MntDoctores::find($id);

            if (!$doctor) {
                return response()->json([
                    'error' => 'Doctor no encontrado.',
                ], 404);
            }

            $doctor->update([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'especialidad' => $request->especialidad,
                'telefono' => $request->telefono,
                'email' => $request->email,
            ]);

            return response()->json([
                'message' => 'Doctor actualizado exitosamente.',
                'data' => $doctor,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al actualizar el doctor.',
                'exception' => $e->getMessage(), // Útil para depuración
            ], 500);
        }
    }

    public function cambiarEstado($id)
    {
        try {
            $doctor = MntDoctores::find($id);

            if (!$doctor) {
                return response()->json([
                    'error' => 'Doctor no encontrado.'
                ], 404);
            }

            $doctor->activo = !$doctor->activo;

            $doctor->save();

            $message = $doctor->activo ? 'Doctor activado correctamente.' : 'Doctor desactivado correctamente.';

            return response()->json([
                'message' => $message,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al intentar cambiar el estado del doctor.',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    public function detalle($id) {
        $detalle = MntDoctores::where('id', $id)->first();
        return response()->json($detalle);
    }

}
