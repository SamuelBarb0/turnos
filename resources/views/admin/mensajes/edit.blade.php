@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-600 to-[#3161DD] py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl overflow-hidden">
        <div class="p-6 sm:p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Editar Mensaje</h1>

            <form action="{{ route('mensajes.update', $mensaje->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Título</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $mensaje->title) }}"
                           class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-[#3161DD] focus:border-[#3161DD]" 
                           maxlength="60" required>
                </div>

                <div class="mb-6">
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-2">Contenido</label>
                    <textarea id="body" name="body" rows="5" 
                              class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-[#3161DD] focus:border-[#3161DD]" 
                              maxlength="900" required>{{ old('body', $mensaje->body) }}</textarea>
                </div>

                <div class="mb-6">
                    <label for="template" class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                    <select id="template" name="template" 
                            class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-[#3161DD] focus:border-[#3161DD]">
                        <option value="personalizado" {{ $mensaje->tipo == 'personalizado' ? 'selected' : '' }}>Personalizado</option>
                        <option value="confirmacion" {{ $mensaje->tipo == 'confirmacion' ? 'selected' : '' }}>Confirmación</option>
                        <option value="recordatorio" {{ $mensaje->tipo == 'recordatorio' ? 'selected' : '' }}>Recordatorio</option>
                        <option value="cancelacion" {{ $mensaje->tipo == 'cancelacion' ? 'selected' : '' }}>Cancelación</option>
                    </select>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-[#3161DD] text-white px-6 py-2 rounded-md hover:bg-[#2050C0]">
                        Actualizar Mensaje
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
