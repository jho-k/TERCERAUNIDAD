@extends('layouts.admin-centro')

@section('title', 'Listado de Vacunas')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white shadow-lg rounded-xl">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 border-b pb-4">
        <h2 class="text-2xl font-semibold text-gray-800">Listado de Vacunas</h2>
    </div>


    <div class="p-8">
        <div class="mb-6 flex space-x-4 max-w-md">
            <form method="GET" action="{{ route('vacunas.index') }}" class="mb-6 flex flex-wrap gap-4">
                <input type="text" name="dni" value="{{ $dni }}" placeholder="Buscar por DNI"
                    class="lex-1 p-3 border border-gray-950 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                <button type="submit"
                    class="px-4 py-2 bg-amber-600 text-white rounded-lg shadow-md hover:bg-amber-700 focus:outline-none focus:ring-4 focus:ring-amber-300 transition duration-300">
                    Buscar
                </button>
            </form>
        </div>

        @if ($paciente)
        <h3 class="mb-4 font-semibold">Paciente: {{ $paciente->primer_nombre }} {{ $paciente->primer_apellido }}</h3>
        @if ($paciente->historialClinico->isNotEmpty())
        <div class="flex gap-2 mb-4">
            <a href="{{ route('vacunas.create', $paciente->historialClinico->first()->id_historial) }}" class="bg-amber-700 text-white px-4 py-2 rounded-lg shadow-md hover:bg-amber-800 transition">+ Nueva Vacuna</a>
            <a href="{{ route('historial.show', $paciente->historialClinico->first()->id_historial) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-700 transition">Volver al Historial</a>
        </div>
        @else
        <p class="text-red-600 mb-4">El paciente no tiene un historial clínico.</p>
        @endif

        @if ($vacunas->isEmpty())
        <p class="text-gray-500 mb-4">No hay vacunas registradas para este paciente.</p>
        @else

        <!-- Tabla de Servicios -->

        <div class="overflow-x-auto">
            <table class="min-w-full bg-amber-100 border border-amber-600 rounded-lg shadow-md">
                <thead class="bg-amber-600 text-white">
                    <tr>
                        <th class="px-6 py-3 text-center border border-amber-700">Nombre</th>
                        <th class="px-6 py-3 text-center border border-amber-700">Fecha Aplicación</th>
                        <th class="px-6 py-3 text-center border border-amber-700">Dosis</th>
                        <th class="px-6 py-3 text-center border border-amber-700">Próxima Dosis</th>
                        <th class="px-6 py-3 text-center border border-amber-700">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vacunas as $vacuna)
                    <tr class="border border-amber-500 hover:bg-amber-200 transition">
                        <td class="px-6 py-4 text-center border border-amber-500">{{ $vacuna->nombre_vacuna }}</td>
                        <td class="px-6 py-4 text-center border border-amber-500">{{ $vacuna->fecha_aplicacion }}</td>
                        <td class="px-6 py-4 text-center border border-amber-500">{{ $vacuna->dosis }}</td>
                        <td class="px-6 py-4 text-center border border-amber-500">{{ $vacuna->proxima_dosis ?? 'No programada' }}</td>
                        <td class="px-3 py-1 rounded-full text-white text-sm font-bold">
                            <a href="{{ route('vacunas.edit', [$vacuna->id_historial, $vacuna->id_vacuna]) }}"
                                class="bg-blue-700 text-white px-3 py-2 rounded-md hover:bg-blue-800 transition">
                                Editar</a>
                            <button
                                class="bg-red-700 text-white px-3 py-2 rounded-md hover:bg-blue-800 transition"
                                data-url="{{ route('vacunas.destroy', [$vacuna->id_historial, $vacuna->id_vacuna]) }}">
                                Eliminar</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        @elseif ($dni)
        <p class="text-red-600">No se encontró ningún paciente con el DNI ingresado.</p>
        @else
        <p class="text-gray-500">Ingrese un DNI para buscar un paciente.</p>
        @endif
    </div>
</div>

<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            if (!confirm('¿Confirma eliminar esta vacuna?')) return;

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
                        alert('Vacuna eliminada exitosamente.');
                        window.location.href = '{{ route('vacunas.index') }}?dni=' + data.dni;
                    } else {
                        alert('Error al eliminar la vacuna.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar la vacuna.');
                });
        });
    });
</script>
@endsection