@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-600 to-[#3161DD] py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl overflow-hidden">
        <div class="p-6 sm:p-8">
            <h1 class="text-2xl font-adineue font-bold text-gray-800 mb-2">Diseña tu primer mensaje para confirmar citas</h1>
            <div class="w-full h-px bg-gray-200 my-4"></div>
            
            <form action="{{ route('admin.setup.complete') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="template" class="block text-sm font-medium text-gray-700 mb-2">Escoge un punto de partida (opcional)</label>
                    <select id="template" name="template" class="w-full md:w-1/2 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#3161DD] focus:border-[#3161DD]">
                        <option value="">Seleccionar plantilla base</option>
                        <option value="recordatorio_simple">Recordatorio simple</option>
                        <option value="confirmacion">Confirmación de cita</option>
                        <option value="reprogramacion">Reprogramación</option>
                    </select>
                </div>
                
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Título del mensaje</label>
                    <div class="relative">
                        <input type="text" id="title" name="title" 
                               class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#3161DD] focus:border-[#3161DD]" 
                               placeholder="Ej: Recordatorio de cita con Dr. García" 
                               maxlength="60">
                        <div class="absolute bottom-2 right-3 text-xs text-gray-500">
                            <span id="titleCounter">0</span>/60
                        </div>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-2">Cuerpo del mensaje</label>
                    <div class="relative">
                        <textarea id="body" name="body" rows="5" 
                                 class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#3161DD] focus:border-[#3161DD]" 
                                 placeholder="Ej: Tienes una cita programada para mañana a las..." 
                                 maxlength="900"></textarea>
                        <div class="absolute bottom-2 right-3 text-xs text-gray-500">
                            <span id="bodyCounter">0</span>/900
                        </div>
                    </div>
                </div>
                
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Así se verá el mensaje en WhatsApp:</h3>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <div class="mx-auto max-w-sm bg-white rounded-lg shadow-sm p-4">
                            <h4 id="previewTitle" class="font-medium text-gray-900 mb-2">Recordatorio de cita</h4>
                            <p id="previewBody" class="text-gray-700 mb-3">Tienes una cita programada</p>
                            <p class="text-gray-700 text-sm mb-1">El jueves, 24 de abril, 2025 a las 7:38 p. m.</p>
                            <p class="text-gray-500 text-xs italic mb-3">(Fecha de ejemplo)</p>
                            <p class="text-gray-700 mb-3">¿Vas a poder asistir?</p>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" class="py-2 px-4 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 text-center">No</button>
                                <button type="button" class="py-2 px-4 bg-[#3161DD] border border-[#3161DD] rounded-md text-white hover:bg-[#2050C0] text-center">Sí</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between mt-8">
                    <a href="{{ route('admin.setup.welcome') }}" class="flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3161DD]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Atrás
                    </a>
                    <button type="submit" class="flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#3161DD] hover:bg-[#2050C0] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3161DD]">
                        Finalizar configuración
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Script para actualizar los contadores y la vista previa
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('title');
        const bodyInput = document.getElementById('body');
        const titleCounter = document.getElementById('titleCounter');
        const bodyCounter = document.getElementById('bodyCounter');
        const previewTitle = document.getElementById('previewTitle');
        const previewBody = document.getElementById('previewBody');
        
        // Inicializar la vista previa
        updatePreview();
        
        // Actualizar contadores y vista previa cuando se escriba
        titleInput.addEventListener('input', function() {
            titleCounter.textContent = this.value.length;
            updatePreview();
        });
        
        bodyInput.addEventListener('input', function() {
            bodyCounter.textContent = this.value.length;
            updatePreview();
        });
        
        // Función para actualizar la vista previa
        function updatePreview() {
            previewTitle.textContent = titleInput.value || 'Recordatorio de cita';
            previewBody.textContent = bodyInput.value || 'Tienes una cita programada';
        }
        
        // Cargar plantillas predefinidas
        const templateSelect = document.getElementById('template');
        templateSelect.addEventListener('change', function() {
            const value = this.value;
            
            if (value === 'recordatorio_simple') {
                titleInput.value = 'Recordatorio de cita';
                bodyInput.value = 'Tienes una cita programada. Por favor confirma tu asistencia.';
            } else if (value === 'confirmacion') {
                titleInput.value = 'Confirmación de cita';
                bodyInput.value = 'Tu cita ha sido confirmada. Te esperamos en la fecha y hora indicada.';
            } else if (value === 'reprogramacion') {
                titleInput.value = 'Reprogramación de cita';
                bodyInput.value = 'Necesitamos reprogramar tu cita. Por favor confirma si la nueva fecha y hora te convienen.';
            }
            
            // Actualizar contadores
            titleCounter.textContent = titleInput.value.length;
            bodyCounter.textContent = bodyInput.value.length;
            
            // Actualizar vista previa
            updatePreview();
        });
    });
</script>
@endsection