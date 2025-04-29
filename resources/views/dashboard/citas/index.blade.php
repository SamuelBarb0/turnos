@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Citas Programadas</h1>
        <a href="{{ route('citas.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            + Nueva Cita
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        @forelse($citas as $cita)
            <div class="border-b py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-semibold">{{ $cita->titulo }}</h2>
                        <p class="text-sm text-gray-500">{{ $cita->fecha_de_la_cita->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        @if($cita->estado === 'confirmada')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Confirmada</span>
                        @elseif($cita->estado === 'pendiente')
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                        @elseif($cita->estado === 'cancelada')
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Cancelada</span>
                        @endif
                    </div>
                </div>
                <div class="flex space-x-2 mt-2">
                    <a href="{{ route('citas.show', $cita) }}" class="text-blue-600 hover:text-blue-800 text-sm">Ver</a>
                    <a href="{{ route('citas.edit', $cita) }}" class="text-green-600 hover:text-green-800 text-sm">Editar</a>
                    <form action="{{ route('citas.destroy', $cita) }}" method="POST" onsubmit="return confirm('¿Seguro de eliminar la cita?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Eliminar</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-600">No hay citas programadas todavía.</p>
        @endforelse
    </div>
</div>
@endsection
