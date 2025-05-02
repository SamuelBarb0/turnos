<!-- resources/views/citas/mensaje.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Preparar Mensaje para el Cliente</h2>
                <a href="{{ route('citas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:bg-gray-300 dark:focus:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver
                </a>
            </div>

            <div class="bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-500 p-4 mb-6 rounded">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500 dark:text-yellow-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-yellow-700 dark:text-yellow-300">
                        Cuando envíes este mensaje, la cita pasará al estado "Mensaje Enviado" y se marcará en amarillo en el calendario.
                    </p>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-2">Detalles de la Cita</h3>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-4">
                    <p class="text-gray-700 dark:text-gray-300 mb-1"><span class="font-semibold">Título:</span> {{ $cita->titulo }}</p>
                    <p class="text-gray-700 dark:text-gray-300 mb-1"><span class="font-semibold">Fecha y hora:</span> {{ $cita->fecha_de_la_cita->format('d/m/Y H:i') }}</p>
                    <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold">Descripción:</span> {{ $cita->descripcion ?? 'Sin descripción' }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('citas.enviar.mensaje', $cita->id_cita) }}">
                @csrf

                <div class="mb-6">
                    <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Número de Teléfono del Cliente</label>

                    <div x-data="{ bandera: '/flags/co.png' }" class="mb-6">
                        <label for="prefijo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">País y código</label>
                        <div class="flex items-center space-x-3">

                            <!-- Bandera real -->
                            <img :src="bandera" class="w-8 h-6" alt="Bandera">

                            <!-- Select de países -->
                            <select name="prefijo" id="prefijo" @change="bandera = $event.target.selectedOptions[0].dataset.flag"
                                class="rounded bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                                <option value="+1" data-flag="/flags/us.png">Estados Unidos (+1)</option>
                                <option value="+52" data-flag="/flags/mx.png">México (+52)</option>
                                <option value="+57" data-flag="/flags/co.png" selected>Colombia (+57)</option>
                                <option value="+58" data-flag="/flags/ve.png">Venezuela (+58)</option>
                                <option value="+51" data-flag="/flags/pe.png">Perú (+51)</option>
                                <option value="+54" data-flag="/flags/ar.png">Argentina (+54)</option>
                                <option value="+55" data-flag="/flags/br.png">Brasil (+55)</option>
                                <option value="+56" data-flag="/flags/cl.png">Chile (+56)</option>
                                <option value="+507" data-flag="/flags/pa.png">Panamá (+507)</option>
                                <option value="+53" data-flag="/flags/cu.png">Cuba (+53)</option>
                                <option value="+593" data-flag="/flags/ec.png">Ecuador (+593)</option>
                                <option value="+502" data-flag="/flags/gt.png">Guatemala (+502)</option>
                                <option value="+504" data-flag="/flags/hn.png">Honduras (+504)</option>
                                <option value="+505" data-flag="/flags/ni.png">Nicaragua (+505)</option>
                                <option value="+506" data-flag="/flags/cr.png">Costa Rica (+506)</option>
                                <option value="+509" data-flag="/flags/ht.png">Haití (+509)</option>
                                <option value="+592" data-flag="/flags/gy.png">Guyana (+592)</option>
                                <option value="+595" data-flag="/flags/py.png">Paraguay (+595)</option>
                                <option value="+598" data-flag="/flags/uy.png">Uruguay (+598)</option>
                                <option value="+597" data-flag="/flags/sr.png">Surinam (+597)</option>
                                <option value="+599" data-flag="/flags/bq.png">Caribe Neerlandés (+599)</option>
                                <option value="+876" data-flag="/flags/jm.png">Jamaica (+876)</option>
                            </select>

                            <!-- Teléfono -->
                            <input type="text" name="telefono" id="telefono"
                                class="rounded bg-gray-50 border border-gray-300 text-gray-900 text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="1234567890" required>
                        </div>

                        @error('telefono')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    @error('telefono')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="mensaje" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mensaje para el Cliente</label>
                    <textarea name="mensaje" id="mensaje" rows="5" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md @error('mensaje') border-red-500 @enderror" required>{{ $mensajePredeterminado }}</textarea>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Este mensaje se enviará al cliente. Debe incluir instrucciones claras para confirmar o cancelar la cita.
                    </p>
                    @error('mensaje')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('citas.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Enviar Mensaje
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection