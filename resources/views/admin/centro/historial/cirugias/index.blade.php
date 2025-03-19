@extends('layouts.admin-centro')

@section('title', 'Listado de Cirugías')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-700 p-6">
            <h2 class="text-white text-2xl font-semibold">Listado de Cirugías</h2>
        </div>
        <div class="p-6">
            <!-- Buscador DNI -->
            <form method="GET" action="{{ route('cirugias.index') }}" class="mb-6 flex">
                <input type="text" name="dni" value="{{ $dni }}" placeholder="Buscar por DNI"
                    class="flex-grow px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-r-lg hover:bg-green-500 transition">Buscar</button>
            </form>

            @if ($paciente)
            <h3 class="mb-4 text-lg font-semibold">Paciente: {{ $paciente->primer_nombre }} {{ $paciente->primer_apellido }}</h3>
            @if ($paciente->historialClinico->isNotEmpty())
            <a href="{{ route('cirugias.create', $paciente->historialClinico->first()->id_historial) }}"
                class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-500 transition mb-4">Nueva Cirugía</a>
            @else
            <p class="text-red-500 mb-4">El paciente no tiene un historial clínico.</p>
            @endif

            @if ($cirugias->isEmpty())
            <p class="text-gray-500 mb-4">No hay cirugías registradas para este paciente.</p>
            @else
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left border-b-2 border-gray-300">Tipo</th>
                        <th class="px-4 py-2 text-left border-b-2 border-gray-300">Fecha</th>
                        <th class="px-4 py-2 text-left border-b-2 border-gray-300">Cirujano</th>
                        <th class="px-4 py-2 text-left border-b-2 border-gray-300">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cirugias as $cirugia)
                    <tr class="border-b border-gray-300">
                        <td class="px-4 py-2">{{ $cirugia->tipo_cirugia }}</td>
                        <td class="px-4 py-2">{{ $cirugia->fecha_cirugia }}</td>
                        <td class="px-4 py-2">{{ $cirugia->cirujano }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('cirugias.edit', [$cirugia->id_historial, $cirugia->id_cirugia]) }}"
                                class="inline-block px-3 py-1 bg-yellow-500 text-white rounded-lg hover:bg-yellow-400 transition">Editar</a>
                            <button class="btn-delete px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-400 transition"
                                data-url="{{ route('cirugias.destroy', [$cirugia->id_historial, $cirugia->id_cirugia]) }}">Eliminar</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
            @elseif ($dni)
            <p class="text-red-500 mb-4">No se encontró ningún paciente con el DNI ingresado.</p>
            @else
            <p class="text-gray-500 mb-4">Ingrese un DNI para buscar un paciente.</p>
            @endif

            @if ($paciente && $paciente->historialClinico->isNotEmpty())
            <a href="{{ route('historial.show', $paciente->historialClinico->first()->id_historial) }}"
                class="inline-block px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-400 transition mt-4">Regresar al Historial</a>
            @endif
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            if (!confirm('¿Confirma eliminar esta cirugía?')) return;

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
                        alert('Cirugía eliminada exitosamente.');
                        location.reload();
                    } else {
                        alert('Error al eliminar la cirugía.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar la cirugía.');
                });
        });
    });
</script>
@endsection