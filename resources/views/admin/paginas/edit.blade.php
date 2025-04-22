@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Editar Página</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <form action="{{ route('admin.paginas.update', $pagina) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $pagina->title) }}" 
                           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-agendux-blue focus:border-transparent
                           @error('title') border-red-500 @else border-gray-300 @enderror" 
                           required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug (URL)</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $pagina->slug) }}" 
                           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-agendux-blue focus:border-transparent
                           @error('slug') border-red-500 @else border-gray-300 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Si lo dejas en blanco, se generará automáticamente desde el título.</p>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-start space-x-3">
                    <a href="{{ route('admin.paginas.index') }}" 
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