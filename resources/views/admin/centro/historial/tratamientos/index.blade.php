@extends('layouts.admin-centro')

@section('title', 'Listado de Tratamientos')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white shadow-lg rounded-xl">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-purple-500 to-purple-800 p-5">
            <h2 class="text-white text-lg sm:text-xl font-semibold m-0">Listado de Tratamientos</h2>
        </div>

        <div class="p-5">
            <!-- Buscador por DNI -->
            <form method="GET" action="{{ route('tratamientos.index') }}" class="mb-5 flex flex-wrap gap-3">
                <input
                    type="text"
                    name="dni"
                    value="{{ $dni }}"
                    placeholder="Buscar por DNI"
                    class="flex-1 min-w-[200px] p-2 border border-gray-300 rounded-md">
                <button
                    type="submit"
                    class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                    Buscar
                </button>
            </form>

            @if ($paciente)
            <h3 class="mb-5 text-lg font-semibold">Paciente: {{ $paciente->primer_nombre }} {{ $paciente->primer_apellido }}</h3>

            @if ($paciente->historialClinico->isNotEmpty())
            <a href="{{ route('tratamientos.create', $paciente->historialClinico->first()->id_historial) }}"
                class="inline-block px-5 py-2 bg-purple-700 text-white rounded-md mb-5 hover:bg-purple-800 transition">
                Nuevo Tratamiento
            </a>
            @else
            <p class="text-red-500">El paciente no tiene un historial clínico.</p>
            @endif

            @if ($tratamientos->isEmpty())
            <p class="text-gray-500">No hay tratamientos registrados para este paciente.</p>
            @else

            <!-- Tabla de Pacientes -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-purple-100 border border-purple-600 rounded-lg shadow-md">
                    <thead class="bg-purple-600 text-white">
                        <tr class="bg-purple-600">
                            <th class="px-6 py-3 text-left border border-purple-700 text-center">Fecha</th>
                            <th class="px-6 py-3 text-left border border-purple-700 text-center">Descripción</th>
                            <th class="px-6 py-3 text-left border border-purple-700 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tratamientos as $tratamiento)
                        <tr class="border border-purple-500 hover:bg-purple-200 transition">
                            <td class="px-6 py-4 border border-purple-500 text-center">{{ $tratamiento->fecha_creacion }}</td>
                            <td class="px-6 py-4 border border-purple-500 text-center">{{ $tratamiento->descripcion }}</td>
                            <td class="px-6 py-4 border border-purple-500 text-center">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('tratamientos.edit', [$tratamiento->id_historial, $tratamiento->id_tratamiento]) }}"
                                        class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                                        Editar
                                    </a>
                                    <button class="btn-delete px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition"
                                        data-url="{{ route('tratamientos.destroy', [$tratamiento->id_historial, $tratamiento->id_tratamiento]) }}">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
            @elseif ($dni)
            <p class="text-red-500">No se encontró ningún paciente con el DNI ingresado.</p>
            @else
            <p class="text-gray-500">Ingrese un DNI para buscar un paciente.</p>
            @endif

            @if ($paciente && $paciente->historialClinico->isNotEmpty())
            <a href="{{ route('historial.show', $paciente->historialClinico->first()->id_historial) }}"
                class="inline-block px-5 py-2 bg-purple-600 text-white rounded-md mt-5 hover:bg-purple-700 transition">
                Regresar al Historial
            </a>
            @endif
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            if (!confirm('¿Confirma eliminar este tratamiento?')) return;

            const url = this.getAttribute('data-url');
            fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Tratamiento eliminado exitosamente.');
                        location.reload();
                    } else {
                        alert('Error al eliminar el tratamiento.');
                    }
                })
                .catch(() => {
                    alert('Error al eliminar el tratamiento.');
                });
        });
    });
</script>
@endsection