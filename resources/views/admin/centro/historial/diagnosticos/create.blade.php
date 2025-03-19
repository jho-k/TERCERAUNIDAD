@extends('layouts.admin-centro')

@section('title', 'Registrar Diagnóstico')

@section('content')
<div class="max-w-3xl mx-auto p-6 w-11/12">
    <div class="bg-white border-2 border-black rounded-lg shadow-lg overflow-hidden">
        <!-- Encabezado -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-700 p-4">
            <h2 class="text-white text-2xl font-bold">Registrar Diagnóstico</h2>
        </div>

        <!-- Contenido del Formulario -->
        <div class="p-6">
            <form action="{{ route('diagnosticos.store', $historial->id_historial) }}" method="POST">
                @csrf

                <!-- Descripción -->
                <div class="mb-4">
                    <label for="descripcion" class="block font-bold mb-1">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="4"
                        class="w-full p-3 border border-black rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 bg-gray-100 resize-y">{{ old('descripcion') }}</textarea>
                </div>

                <!-- Fecha del Diagnóstico -->
                <div class="mb-4">
                    <label for="fecha_creacion" class="block font-bold mb-1">Fecha del Diagnóstico</label>
                    <input type="date" name="fecha_creacion" id="fecha_creacion" value="{{ old('fecha_creacion') }}" required
                        class="w-full p-3 border border-black rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 bg-gray-100">
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-end items-center mt-6 space-x-4">
                    <a href="{{ route('diagnosticos.index', ['dni' => $paciente->dni]) }}"
                        class="px-5 py-2 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg border border-black">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-lg border border-black">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection