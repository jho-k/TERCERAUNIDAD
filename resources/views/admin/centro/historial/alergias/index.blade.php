@extends('layouts.admin-centro')

@section('title', 'Listado de Alergias')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white shadow-lg rounded-xl">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 border-b pb-4">
        <h2 class="text-2xl font-semibold text-gray-800">Listado de Alergias</h2>
        @if ($paciente)
        <a href="{{ route('historial.show', $paciente->historialClinico->first()->id_historial) }}"
            class="bg-sky-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-sky-700 transition">
            Regresar a Historial
        </a>
        @endif
    </div>

    <div class="p-8">
        <div class="mb-6 flex space-x-4 max-w-md">
            <form method="GET" action="{{ route('alergias.index') }}" class="mb-6 flex flex-wrap gap-4">
                <input type="text" name="dni" placeholder="Ingrese DNI" value="{{ $dni }}"
                    class="lex-1 p-3 border border-gray-950 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition duration-300">
                    Buscar
                </button>
            </form>
        </div>

        @if ($paciente)
        <div class="mb-6 flex justify-between items-center">
            <p class="text-lg font-semibold">Paciente: {{ $paciente->primer_nombre }} {{ $paciente->primer_apellido }}</p>
            <a href="{{ route('alergias.create', $paciente->id_paciente) }}"
                class="px-4 py-2 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 transition duration-300">
                Nueva Alergia
            </a>
        </div>

        @if ($alergias->isEmpty())
        <div class="bg-blue-100 text-blue-800 p-4 rounded-lg">No hay alergias registradas.</div>
        @else

        <!-- Tabla de Servicios -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-green-100 border border-green-600 rounded-lg shadow-md">
                <thead class="bg-green-600 text-white">
                    <tr>
                        <th class="px-6 py-3 text-center border border-green-700">Tipo</th>
                        <th class="px-6 py-3 text-center border border-green-700">Descripción</th>
                        <th class="px-6 py-3 text-center border border-green-700">Severidad</th>
                        <th class="px-6 py-3 text-center border border-green-700">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($alergias as $alergia)
                    <tr class="border border-green-500 hover:bg-green-200 transition">
                        <td class="px-6 py-4 text-center border border-green-500">{{ $alergia->tipo }}</td>
                        <td class="px-6 py-4 text-center border border-green-500">{{ $alergia->descripcion }}</td>
                        <td class="px-6 py-4 text-center border border-green-500">
                            <span class="px-3 py-1 rounded-full text-white text-sm font-bold"
                                style="background-color: {{ $alergia->severidad === 'Alta' ? '#e74c3c' : ($alergia->severidad === 'Media' ? '#f39c12' : '#2ecc71') }};">
                                {{ $alergia->severidad }}
                            </span>
                        </td>
                        <td class="px-6 py-4 flex flex-wrap justify-center gap-2">
                            <a href="{{ route('alergias.edit', [$paciente->id_paciente, $alergia->id_alergia]) }}"
                                class="bg-blue-700 text-white px-3 py-2 rounded-md hover:bg-blue-800 transition">
                                Editar
                            </a>
                            <form action="{{ route('alergias.destroy', [$paciente->id_paciente, $alergia->id_alergia]) }}" method="POST"
                                onsubmit="return confirm('¿Confirma eliminar esta alergia?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-700 text-white px-3 py-2 rounded-md hover:bg-blue-800 transition">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        @else
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg">No se encontró ningún paciente con el DNI ingresado.</div>
        @endif
    </div>
</div>
@endsection