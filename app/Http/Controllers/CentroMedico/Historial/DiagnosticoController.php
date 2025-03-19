<?php

namespace App\Http\Controllers\CentroMedico\Historial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Diagnostico;
use App\Models\Paciente;
use App\Models\HistorialClinico;
use Illuminate\Support\Facades\Log;

class DiagnosticoController extends Controller
{
    public function index(Request $request)
    {
        $dni = $request->get('dni');
        $paciente = null;
        $diagnosticos = collect();

        if ($dni) {
            $paciente = Paciente::where('dni', $dni)->with('historialClinico.diagnostico')->first();

            if ($paciente && $paciente->historialClinico->isNotEmpty()) {
                $diagnosticos = $paciente->historialClinico->flatMap->diagnostico;
                Log::info('Paciente encontrado:', ['id' => $paciente->id_paciente, 'nombre' => $paciente->primer_nombre]);
            } else {
                Log::info('Paciente no encontrado para DNI: ' . $dni);
            }
        }

        return view('admin.centro.historial.diagnosticos.index', compact('paciente', 'diagnosticos', 'dni'));
    }

    public function create($idHistorial)
    {
        $historial = HistorialClinico::with('paciente')->findOrFail($idHistorial);
        $paciente = $historial->paciente;

        return view('admin.centro.historial.diagnosticos.create', compact('historial', 'paciente'));
    }

    public function store(Request $request, $idHistorial)
    {
        $request->validate([
            'descripcion' => 'required|string',
            'fecha_creacion' => 'required|date',
        ]);

        $diagnostico = Diagnostico::create([
            'id_historial' => $idHistorial,
            'descripcion' => $request->descripcion,
            'fecha_creacion' => $request->fecha_creacion,
        ]);

        Log::info('Diagnóstico creado:', $diagnostico->toArray());

        return redirect()->route('diagnosticos.index', ['dni' => $diagnostico->historialClinico->paciente->dni])
            ->with('success', 'Diagnóstico registrado exitosamente.');
    }

    public function edit($idHistorial, $idDiagnostico)
    {
        $diagnostico = Diagnostico::where('id_historial', $idHistorial)->findOrFail($idDiagnostico);
        $paciente = $diagnostico->historialClinico->paciente;

        return view('admin.centro.historial.diagnosticos.edit', compact('diagnostico', 'paciente'));
    }

    public function update(Request $request, $idHistorial, $idDiagnostico)
    {
        $request->validate([
            'descripcion' => 'required|string',
            'fecha_creacion' => 'nullable|date',
        ]);

        $diagnostico = Diagnostico::where('id_historial', $idHistorial)->findOrFail($idDiagnostico);
        $data = $request->only(['descripcion']);
        if ($request->filled('fecha_creacion')) {
            $data['fecha_creacion'] = $request->fecha_creacion;
        }
        $diagnostico->update($data);

        Log::info('Diagnóstico actualizado:', $diagnostico->toArray());

        return redirect()->route('diagnosticos.index', ['dni' => $diagnostico->historialClinico->paciente->dni])
            ->with('success', 'Diagnóstico actualizado exitosamente.');
    }

    public function destroy($idHistorial, $idDiagnostico)
    {
        $diagnostico = Diagnostico::where('id_historial', $idHistorial)->findOrFail($idDiagnostico);
        $dni = $diagnostico->historialClinico->paciente->dni;
        $diagnostico->delete();

        Log::info('Diagnóstico eliminado:', ['id' => $idDiagnostico, 'historial_id' => $idHistorial]);

        return response()->json(['success' => true, 'message' => 'Diagnóstico eliminado exitosamente.', 'dni' => $dni]);
    }
}
