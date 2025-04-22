@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Crear Nuevo Contenido para "{{ $seccion->seccion }}"</h1>
        <p class="text-gray-500 mt-1">Página: {{ $pagina->title }}</p>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <form action="{{ route('admin.secciones.contenidos.store', $seccion) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="etiqueta" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Etiqueta</label>
                    <select id="etiqueta" name="etiqueta" required
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-agendux-blue focus:border-transparent
                            @error('etiqueta') border-red-500 @else border-gray-300 @enderror">
                        <option value="" selected disabled>Selecciona el tipo de etiqueta</option>
                        <option value="h1" {{ old('etiqueta') == 'h1' ? 'selected' : '' }}>Título principal (H1)</option>
                        <option value="h2" {{ old('etiqueta') == 'h2' ? 'selected' : '' }}>Subtítulo (H2)</option>
                        <option value="h3" {{ old('etiqueta') == 'h3' ? 'selected' : '' }}>Subtítulo secundario (H3)</option>
                        <option value="p" {{ old('etiqueta') == 'p' ? 'selected' : '' }}>Párrafo (P)</option>
                        <option value="button" {{ old('etiqueta') == 'button' ? 'selected' : '' }}>Botón (BUTTON)</option>
                        <option value="a" {{ old('etiqueta') == 'a' ? 'selected' : '' }}>Enlace (A)</option>
                        <option value="ul" {{ old('etiqueta') == 'ul' ? 'selected' : '' }}>Lista (UL/LI)</option>
                    </select>
                    @error('etiqueta')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="contenido" class="block text-sm font-medium text-gray-700 mb-1">Contenido</label>
                    <textarea id="contenido" name="contenido" rows="5" required
                              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-agendux-blue focus:border-transparent
                              @error('contenido') border-red-500 @else border-gray-300 @enderror">{{ old('contenido') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Para botones y enlaces, incluye la URL con el prefijo "url:"</p>
                    @error('contenido')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="orden" class="block text-sm font-medium text-gray-700 mb-1">Orden</label>
                    <input type="number" id="orden" name="orden" value="{{ old('orden', 0) }}" 
                           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-agendux-blue focus:border-transparent
                           @error('orden') border-red-500 @else border-gray-300 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Número para ordenar los elementos (menor a mayor)</p>
                    @error('orden')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-start space-x-3">
                    <a href="{{ route('admin.secciones.contenidos.index', $seccion) }}" 
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