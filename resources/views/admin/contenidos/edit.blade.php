@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Editar Contenido</h1>
        <p class="text-gray-500 mt-1">Sección: {{ $seccion->seccion }} | Página: {{ $pagina->title }}</p>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <form action="{{ route('admin.secciones.contenidos.update', [$seccion, $contenido]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="etiqueta" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Etiqueta</label>
                    <select id="etiqueta" name="etiqueta" required
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-agendux-blue focus:border-transparent
                            @error('etiqueta') border-red-500 @else border-gray-300 @enderror">
                        <option value="" disabled>Selecciona el tipo de etiqueta</option>
                        <option value="h1" {{ old('etiqueta', $contenido->etiqueta) == 'h1' ? 'selected' : '' }}>Título principal (H1)</option>
                        <option value="h2" {{ old('etiqueta', $contenido->etiqueta) == 'h2' ? 'selected' : '' }}>Subtítulo (H2)</option>
                        <option value="h3" {{ old('etiqueta', $contenido->etiqueta) == 'h3' ? 'selected' : '' }}>Subtítulo secundario (H3)</option>
                        <option value="p" {{ old('etiqueta', $contenido->etiqueta) == 'p' ? 'selected' : '' }}>Párrafo (P)</option>
                        <option value="button" {{ old('etiqueta', $contenido->etiqueta) == 'button' ? 'selected' : '' }}>Botón (BUTTON)</option>
                        <option value="a" {{ old('etiqueta', $contenido->etiqueta) == 'a' ? 'selected' : '' }}>Enlace (A)</option>
                        <option value="ul" {{ old('etiqueta', $contenido->etiqueta) == 'ul' ? 'selected' : '' }}>Lista (UL/LI)</option>
                    </select>
                    @error('etiqueta')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="contenido" class="block text-sm font-medium text-gray-700 mb-1">Contenido</label>
                    <textarea id="contenido" name="contenido" rows="5" required
                              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-agendux-blue focus:border-transparent
                              @error('contenido') border-red-500 @else border-gray-300 @enderror">{{ old('contenido', $contenido->contenido) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Para botones y enlaces, incluye la URL con el prefijo "url:"</p>
                    @error('contenido')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="orden" class="block text-sm font-medium text-gray-700 mb-1">Orden</label>
                    <input type="number" id="orden" name="orden" value="{{ old('orden', $contenido->orden) }}" 
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
                            class="px-4 py-2 bg-agendux-blue hover:bg-blue-700 text-white rounded-md transition duration-300">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection