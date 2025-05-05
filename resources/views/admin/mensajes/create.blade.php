@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-600 to-[#3161DD] py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl overflow-hidden">
        <div class="p-6 sm:p-8 space-y-10">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Crear Nuevo Mensaje</h1>

            <form action="{{ route('admin.mensajes.store') }}" method="POST">
                @csrf

                <!-- Selección tipo -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-4">Selecciona el Tipo de Mensaje</label>
                    <input type="hidden" name="template" id="selectedTemplate" value="">

                    <div class="grid grid-cols-2 gap-6">

                        <!-- Oferta -->
                        <div class="cursor-pointer relative border-4 border-transparent rounded-lg hover:border-blue-300 selected-template" data-template="oferta">
                            <img src="{{ asset('img/m1.png') }}" alt="Oferta" class="rounded-lg w-full max-h-40 object-contain p-2 bg-gray-50">
                            <div class="absolute top-2 right-2 hidden checkmark bg-green-500 text-white rounded-full w-6 h-6 flex items-center justify-center">✔</div>
                            <p class="text-center mt-2 font-semibold">Oferta Promocional</p>
                        </div>

                        <!-- Recordatorio -->
                        <div class="cursor-pointer relative border-4 border-transparent rounded-lg hover:border-blue-300 selected-template" data-template="recordatorio">
                            <img src="{{ asset('img/m2.png') }}" alt="Recordatorio" class="rounded-lg w-full max-h-40 object-contain p-2 bg-gray-50">
                            <div class="absolute top-2 right-2 hidden checkmark bg-green-500 text-white rounded-full w-6 h-6 flex items-center justify-center">✔</div>
                            <p class="text-center mt-2 font-semibold">Recordatorio de Cita</p>
                        </div>

                    </div>
                </div>

                <!-- Formularios dinámicos -->
                <div id="formOferta" class="hidden space-y-6">
                    <h2 class="text-xl font-semibold">Detalles de la Oferta</h2>

                    <input type="text" id="ofertaTitulo" placeholder="Título" class="w-full border-gray-300 rounded-md py-2 px-3">
                    <input type="text" id="ofertaCodigo" placeholder="Código promocional" class="w-full border-gray-300 rounded-md py-2 px-3">
                    <input type="number" id="ofertaDescuento" placeholder="Descuento (%)" class="w-full border-gray-300 rounded-md py-2 px-3">
                    <input type="url" id="ofertaLink" placeholder="Link de compra" class="w-full border-gray-300 rounded-md py-2 px-3">
                </div>

                <div id="formRecordatorio" class="hidden space-y-6">
                    <h2 class="text-xl font-semibold">Detalles del Recordatorio</h2>

                    <input type="text" id="recordatorioTitulo" placeholder="Título del Evento" class="w-full border-gray-300 rounded-md py-2 px-3">
                    <input type="datetime-local" id="recordatorioFecha" class="w-full border-gray-300 rounded-md py-2 px-3">
                    <textarea id="recordatorioNotas" placeholder="Notas adicionales" class="w-full border-gray-300 rounded-md py-2 px-3"></textarea>
                </div>

                <!-- Vista previa estilo WhatsApp Business (Tarjeta confirmación) -->
                <div class="mt-10 space-y-4">
                    <h3 class="text-lg font-semibold">Vista Previa del Mensaje</h3>
                    <div id="preview" class="space-y-3 bg-white border border-gray-300 rounded-xl p-6 max-w-lg mx-auto shadow-sm transition-all duration-500 opacity-0 translate-y-4 text-gray-700">

                        <p class="text-xs text-gray-400">Selecciona un tipo de mensaje para previsualizar.</p>

                    </div>
                </div>

                <div class="flex justify-end mt-10">
                    <button type="submit" class="bg-[#3161DD] text-white px-6 py-2 rounded-md hover:bg-[#2050C0]">
                        Guardar Mensaje
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const options = document.querySelectorAll('.selected-template');
    const input = document.getElementById('selectedTemplate');
    const formOferta = document.getElementById('formOferta');
    const formRecordatorio = document.getElementById('formRecordatorio');
    const preview = document.getElementById('preview');
    const userName = "{{ auth()->user()->name }}";

    function getCurrentTime() {
        const now = new Date();
        return now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
    }

    function updatePreview() {
        const template = input.value;

        preview.classList.remove('opacity-0', 'translate-y-4');

        if (template === "oferta") {
            const title = document.getElementById('ofertaTitulo').value;
            const code = document.getElementById('ofertaCodigo').value;
            const discount = document.getElementById('ofertaDescuento').value;
            const link = document.getElementById('ofertaLink').value;

            preview.innerHTML = `
                <p class="font-bold text-gray-900">Oferta Especial: ${title}</p>
                <p>👤 ${userName}</p>
                <p>Usa el código <b>${code}</b> para obtener <b>${discount}% de descuento</b>.</p>
                <p><a href="${link}" target="_blank" class="text-blue-600 underline">Comprar ahora</a></p>
                <p class="text-xs text-gray-400 mt-2">${getCurrentTime()}</p>
            `;
        } else if (template === "recordatorio") {
            const title = document.getElementById('recordatorioTitulo').value;
            const fecha = document.getElementById('recordatorioFecha').value;
            const notas = document.getElementById('recordatorioNotas').value;

            preview.innerHTML = `
                <p class="font-bold text-gray-900">Recordatorio de cita médica con Dr. ${userName}</p>
                <p>${notas}</p>
                <p>🗓 <b>${fecha}</b></p>
                <p class="text-xs text-gray-400 mt-2">${getCurrentTime()}</p>
                <div class="flex space-x-4 mt-4">
                    <button class="bg-gray-200 text-gray-700 px-4 py-1 rounded-md text-sm">No</button>
                    <button class="bg-blue-500 text-white px-4 py-1 rounded-md text-sm">Sí</button>
                </div>
            `;
        } else {
            preview.classList.add('opacity-0', 'translate-y-4');
            preview.innerHTML = `<p class="text-xs text-gray-400">Selecciona un tipo de mensaje para previsualizar.</p>`;
        }
    }

    options.forEach(option => {
        option.addEventListener('click', function() {
            options.forEach(o => {
                o.classList.remove('border-blue-600', 'bg-blue-50');
                o.querySelector('.checkmark').classList.add('hidden');
            });

            this.classList.add('border-blue-600', 'bg-blue-50');
            this.querySelector('.checkmark').classList.remove('hidden');

            const template = this.dataset.template;
            input.value = template;

            if (template === 'oferta') {
                formOferta.classList.remove('hidden');
                formRecordatorio.classList.add('hidden');
            } else if (template === 'recordatorio') {
                formRecordatorio.classList.remove('hidden');
                formOferta.classList.add('hidden');
            }

            updatePreview();
        });
    });

    document.querySelectorAll('input, textarea').forEach(input => {
        input.addEventListener('input', updatePreview);
    });
});
</script>
@endsection
