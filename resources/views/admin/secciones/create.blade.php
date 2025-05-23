@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Crear Nueva Sección para "{{ $pagina->title }}"</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <form action="{{ route('admin.paginas.secciones.store', $pagina) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label for="seccion" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Sección</label>
                    <input type="text" id="seccion" name="seccion" value="{{ old('seccion') }}" 
                           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-agendux-blue focus:border-transparent
                           @error('seccion') border-red-500 @else border-gray-300 @enderror" 
                           required>
                    @error('seccion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="imagen" class="block text-sm font-medium text-gray-700 mb-1">Imagen de Fondo (Opcional)</label>
                    <div class="mt-1 flex items-center">
                        <input type="file" id="imagen" name="imagen"
                               class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-agendux-blue focus:border-transparent
                               @error('imagen') border-red-500 @else border-gray-300 @enderror">
                    </div>
                    @error('imagen')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="orden" class="block text-sm font-medium text-gray-700 mb-1">Orden</label>
                    <input type="number" id="orden" name="orden" value="{{ old('orden', 0) }}" 
                           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-agendux-blue focus:border-transparent
                           @error('orden') border-red-500 @else border-gray-300 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Número para ordenar las secciones (menor a mayor)</p>
                    @error('orden')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-start space-x-3">
                    <a href="{{ route('admin.paginas.secciones.index', $pagina) }}" 
                       class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition duration-300">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-2 rounded-lg transition duration-300 flex items-center">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection