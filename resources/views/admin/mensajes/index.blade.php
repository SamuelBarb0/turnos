@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-600 to-[#3161DD] py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl overflow-hidden">
        <div class="p-6 sm:p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                {{ $esMisMensajes ? 'Mis Mensajes' : 'Todos los Mensajes' }}
            </h1>

            <div class="flex justify-end mb-4">
                <a href="{{ route('admin.mensajes.create') }}" class="bg-[#3161DD] text-white px-4 py-2 rounded-md hover:bg-[#2050C0]">
                    + Nuevo Mensaje
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha de creación</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($mensajes as $mensaje)
                            <tr>
                                <td class="px-6 py-4">{{ $mensaje->title }}</td>
                                <td class="px-6 py-4 capitalize">{{ $mensaje->tipo }}</td>
                                <td class="px-6 py-4">{{ $mensaje->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.mensajes.edit', $mensaje->id) }}" class="text-[#3161DD] hover:underline">Editar</a>
                                    <form action="{{ route('admin.mensajes.destroy', $mensaje->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar este mensaje?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay mensajes disponibles.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
