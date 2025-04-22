<!-- resources/views/citas/create.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Crear Nueva Cita</h2>
                <a href="{{ route('citas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:bg-gray-300 dark:focus:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver
                </a>
            </div>

            <form method="POST" action="{{ route('citas.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="titulo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título</label>
                        <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md @error('titulo') border-red-500 @enderror" required>
                        @error('titulo')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="fecha_de_la_cita" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha y Hora de la Cita</label>
                        <input type="datetime-local" name="fecha_de_la_cita" id="fecha_de_la_cita" value="{{ old('fecha_de_la_cita') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md @error('fecha_de_la_cita') border-red-500 @enderror" required>
                        @error('fecha_de_la_cita')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md @error('descripcion') border-red-500 @enderror">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Recordatorios</label>
                    <div id="recordatorios-container">
                        <div class="flex flex-wrap md:flex-nowrap items-center mb-2 recordatorio-item gap-2">
                            <div class="w-full md:w-1/2">
                                <select name="recordatorios[0][method]" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md">
                                    <option value="email">Email</option>
                                    <option value="popup">Notificación</option>
                                </select>
                            </div>
                            <div class="w-full md:w-1/2">
                                <select name="recordatorios[0][minutes]" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md">
                                    <option value="10">10 minutos antes</option>
                                    <option value="30">30 minutos antes</option>
                                    <option value="60" selected>1 hora antes</option>
                                    <option value="1440">1 día antes</option>
                                </select>
                            </div>
                            <button type="button" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 remove-recordatorio p-2 rounded-md hover:bg-red-100 dark:hover:bg-red-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button type="button" id="add-recordatorio" class="mt-2 inline-flex items-center px-3 py-1.5 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-700 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Añadir Recordatorio
                    </button>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('citas.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#3161DD] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#2050C0] focus:bg-[#2050C0] active:bg-[#1040A0] focus:outline-none focus:ring-2 focus:ring-[#3161DD] focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let recordatorioCount = 1;
        
        // Agregar recordatorio
        document.getElementById('add-recordatorio').addEventListener('click', function() {
            const container = document.getElementById('recordatorios-container');
            const recordatorioDiv = document.createElement('div');
            recordatorioDiv.className = 'flex flex-wrap md:flex-nowrap items-center mb-2 recordatorio-item gap-2';
            recordatorioDiv.innerHTML = `
                <div class="w-full md:w-1/2">
                    <select name="recordatorios[${recordatorioCount}][method]" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md">
                        <option value="email">Email</option>
                        <option value="popup">Notificación</option>
                    </select>
                </div>
                <div class="w-full md:w-1/2">
                    <select name="recordatorios[${recordatorioCount}][minutes]" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md">
                        <option value="10">10 minutos antes</option>
                        <option value="30">30 minutos antes</option>
                        <option value="60" selected>1 hora antes</option>
                        <option value="1440">1 día antes</option>
                    </select>
                </div>
                <button type="button" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 remove-recordatorio p-2 rounded-md hover:bg-red-100 dark:hover:bg-red-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            `;
            container.appendChild(recordatorioDiv);
            recordatorioCount++;
            
            // Actualizar eventos de quitar
            updateRemoveEvents();
        });
        
        // Función para actualizar eventos de quitar
        function updateRemoveEvents() {
            document.querySelectorAll('.remove-recordatorio').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.recordatorio-item').remove();
                });
            });
        }
        
        // Inicializar eventos de quitar
        updateRemoveEvents();
    });
</script>
@endsection
@endsection