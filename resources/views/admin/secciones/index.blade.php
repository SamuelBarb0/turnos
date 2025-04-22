@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <div class="mb-4 md:mb-0">
            <h1 class="text-2xl font-bold text-gray-800">Secciones de la página "{{ $pagina->title }}"</h1>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.paginas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-300 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Volver a Páginas
            </a>
            <a href="{{ route('admin.paginas.secciones.create', $pagina) }}" class="bg-agendux-blue hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-300 flex items-center">
                <i class="fas fa-plus mr-2"></i> Nueva Sección
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sección</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orden</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($secciones as $seccion)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $seccion->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $seccion->seccion }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($seccion->ruta_image)
                                <img src="{{ asset('storage/' . $seccion->ruta_image) }}" alt="{{ $seccion->seccion }}" class="h-12 w-auto object-cover rounded">
                            @else
                                <span class="text-gray-500 text-sm">Sin imagen</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $seccion->orden }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.paginas.secciones.edit', [$pagina, $seccion]) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs flex items-center transition duration-300">
                                    <i class="fas fa-edit mr-1"></i> Editar
                                </a>
                                <a href="{{ route('admin.secciones.contenidos.index', $seccion) }}" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs flex items-center transition duration-300">
                                    <i class="fas fa-list mr-1"></i> Contenidos
                                </a>
                                <form action="{{ route('admin.paginas.secciones.destroy', [$pagina, $seccion]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs flex items-center transition duration-300" onclick="return confirm('¿Estás seguro?')">
                                        <i class="fas fa-trash mr-1"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No hay secciones definidas aún.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection