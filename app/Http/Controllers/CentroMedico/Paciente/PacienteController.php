<?php

namespace App\Http\Controllers\CentroMedico\Paciente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paciente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Services\ReniecService;

class PacienteController extends Controller
{

    protected $reniecService;

    public function __construct(ReniecService $reniecService)
    {
        $this->reniecService = $reniecService;
    }
    public function buscarPorDni(Request $request)
    {
        $request->validate([
            'dni' => 'required|digits:8',
        ]);

        $dni = $request->input('dni');
        $datos = $this->reniecService->consultarDni($dni);

        if (!$datos || isset($datos['error'])) {
            return response()->json(['error' => 'DNI no encontrado.'], 404);
        }

        // Separar nombres
        $nombresSeparados = explode(' ', $datos['nombres'], 2);

        return response()->json([
            'dni' => $datos['numeroDocumento'],
            'primer_nombre' => $nombresSeparados[0] ?? '',
            'segundo_nombre' => $nombresSeparados[1] ?? '',
            'primer_apellido' => $datos['apellidoPaterno'],
            'segundo_apellido' => $datos['apellidoMaterno'],
        ], 200);
    }



    // Listar pacientes del centro médico del usuario autenticado
    public function index()
    {
        $pacientes = Paciente::where('id_centro', Auth::user()->id_centro)->get();
        return view('admin.centro.paciente.index', compact('pacientes'));
    }

    // Mostrar formulario de creación de paciente
    public function create()
    {
        return view('admin.centro.paciente.create');
    }

    // Almacenar un nuevo paciente
    public function store(Request $request)
    {
        $this->validatePaciente($request);

        // Crear el paciente con los datos validados
        Paciente::create([
            'id_centro' => Auth::user()->id_centro,
            'primer_nombre' => $request->primer_nombre,
            'segundo_nombre' => $request->segundo_nombre,
            'primer_apellido' => $request->primer_apellido,
            'segundo_apellido' => $request->segundo_apellido,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'dni' => $request->dni,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'grupo_sanguineo' => $request->grupo_sanguineo,
            'nombre_contacto_emergencia' => $request->nombre_contacto_emergencia,
            'telefono_contacto_emergencia' => $request->telefono_contacto_emergencia,
            'relacion_contacto_emergencia' => $request->relacion_contacto_emergencia,
            
        ]);

        return redirect()->route('pacientes.index')->with('success', 'Paciente creado exitosamente.');
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $paciente = Paciente::where('id_centro', Auth::user()->id_centro)->findOrFail($id);
        return view('admin.centro.paciente.edit', compact('paciente'));
    }

    // Actualizar datos de un paciente
    public function update(Request $request, $id)
    {
        $paciente = Paciente::where('id_centro', Auth::user()->id_centro)->findOrFail($id);

        // Validación y actualización de los datos del paciente
        $this->validatePaciente($request, $paciente);

        $paciente->update([
            'primer_nombre' => $request->primer_nombre,
            'segundo_nombre' => $request->segundo_nombre,
            'primer_apellido' => $request->primer_apellido,
            'segundo_apellido' => $request->segundo_apellido,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'genero' => $request->genero,
            'dni' => $request->dni,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'grupo_sanguineo' => $request->grupo_sanguineo,
            'nombre_contacto_emergencia' => $request->nombre_contacto_emergencia,
            'telefono_contacto_emergencia' => $request->telefono_contacto_emergencia,
            'relacion_contacto_emergencia' => $request->relacion_contacto_emergencia,
            'es_donador' => $request->es_donador,
        ]);

        return redirect()->route('pacientes.index')->with('success', 'Paciente actualizado exitosamente.');
    }

    // Eliminar un paciente
    public function destroy($id)
    {
        $paciente = Paciente::where('id_centro', Auth::user()->id_centro)->findOrFail($id);
        $paciente->delete();

        return redirect()->route('pacientes.index')->with('success', 'Paciente eliminado exitosamente.');
    }

    // Validación común para creación y edición de pacientes
    private function validatePaciente(Request $request, $paciente = null)
    {
        $uniqueDni = 'unique:pacientes,dni' . ($paciente ? ',' . $paciente->id_paciente . ',id_paciente' : '');
        $uniqueEmail = 'unique:pacientes,email' . ($paciente ? ',' . $paciente->id_paciente . ',id_paciente' : '');

        $request->validate([
            'primer_nombre' => 'required|string|max:50',
            'segundo_nombre' => 'nullable|string|max:50',
            'primer_apellido' => 'required|string|max:50',
            'segundo_apellido' => 'nullable|string|max:50',
            'fecha_nacimiento' => 'required|date',
            
            'dni' => ['required', 'string', 'max:20', $uniqueDni],
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string|max:20',
            'email' => ['nullable', 'email', 'max:100', $uniqueEmail],
            'grupo_sanguineo' => 'required|string|max:5',
            'nombre_contacto_emergencia' => 'nullable|string|max:191',
            'telefono_contacto_emergencia' => 'nullable|string|max:20',
            'relacion_contacto_emergencia' => 'nullable|string|max:50',
            
        ]);
    }
}
