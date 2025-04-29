@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                {{ $esMisMensajes ? 'Mis Plantillas de Mensajes' : 'Todas las Plantillas de Mensajes' }}
            </h1>
            <p class="text-sm text-gray-500">
                {{ $esMisMensajes ? 'Solo ves los mensajes que tú has creado.' : 'Lista completa de todas las plantillas creadas en el sistema.' }}
            </p>
        </div>
        <a href="{{ route('admin.mensajes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            + Nueva Plantilla
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        @forelse($mensajes as $mensaje)
            <div class="border-b py-4 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold">{{ $mensaje->title }}</h2>
                    <p class="text-sm text-gray-500">{{ ucfirst($mensaje->tipo) }}</p>
                    <p class="text-gray-700 mt-2">{{ Str::limit($mensaje->body, 100) }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.mensajes.edit', $mensaje->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">Editar</a>
                    <form action="{{ route('admin.mensajes.destroy', $mensaje->id) }}" method="POST" onsubmit="return confirm('¿Seguro de eliminar esta plantilla?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Eliminar</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-600 text-center py-4">
                {{ $esMisMensajes ? 'No tienes plantillas aún. ¡Crea tu primera plantilla!' : 'No hay plantillas registradas.' }}
            </p>
        @endforelse
    </div>
</div>
@endsection
