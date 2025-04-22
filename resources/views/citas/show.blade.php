<!-- resources/views/citas/show.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Detalles de la Cita</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('citas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:bg-gray-300 dark:focus:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver
                    </a>
                    <a href="{{ route('citas.edit', $cita->id_cita) }}" class="inline-flex items-center px-4 py-2 bg-[#3161DD] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#2050C0] focus:bg-[#2050C0] active:bg-[#1040A0] focus:outline-none focus:ring-2 focus:ring-[#3161DD] focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar
                    </a>
                </div>
            </div>

            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $cita->titulo }}</h1>
                
                <div class="flex items-center mt-2">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400 mr-2">Estado:</span>
                    @if ($cita->estado == 'mensaje_enviado')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                            <span class="w-2 h-2 inline-block bg-yellow-500 rounded-full mr-1"></span>
                            Mensaje enviado
                        </span>
                    @elseif ($cita->estado == 'confirmada')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                            <span class="w-2 h-2 inline-block bg-green-500 rounded-full mr-1"></span>
                            Confirmada
                        </span>
                    @elseif ($cita->estado == 'cancelada')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                            <span class="w-2 h-2 inline-block bg-red-500 rounded-full mr-1"></span>
                            Cancelada
                        </span>
                    @elseif ($cita->estado == 'pendiente')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                            <span class="w-2 h-2 inline-block bg-gray-500 rounded-full mr-1"></span>
                            Pendiente
                        </span>
                    @endif
                    
                    @if ($cita->estado == 'pendiente')
                        <a href="{{ route('citas.preparar.mensaje', $cita->id_cita) }}" class="ml-3 inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 text-xs font-medium rounded-md hover:bg-yellow-200 dark:hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 dark:focus:ring-offset-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Enviar mensaje
                        </a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Fecha de la Cita</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $cita->fecha_de_la_cita->format('d/m/Y H:i') }}
                    </p>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Fecha de Solicitud</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $cita->fecha_solicitud->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Descripción</h3>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <p class="text-gray-800 dark:text-gray-200 whitespace-pre-line">{{ $cita->descripcion ?? 'Sin descripción.' }}</p>
                </div>
            </div>

            @if($cita->mensaje_enviado)
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Estado de Confirmación</h3>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <div class="flex items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 {{ $cita->mensaje_enviado ? 'text-green-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-800 dark:text-gray-200">Mensaje enviado al cliente</span>
                    </div>
                    
                    @if($cita->respuesta_cliente)
                        <div class="flex items-start mt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            <div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Respuesta del cliente:</span>
                                <p class="text-gray-800 dark:text-gray-200 bg-blue-50 dark:bg-blue-900 p-2 rounded mt-1">"{{ $cita->respuesta_cliente }}"</p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center mt-3 text-yellow-600 dark:text-yellow-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Esperando respuesta del cliente</span>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            @if($cita->recordatorios && count($cita->recordatorios) > 0)
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Recordatorios</h3>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <ul class="space-y-2">
                        @foreach($cita->recordatorios as $recordatorio)
                            <li class="flex items-center space-x-2 text-gray-800 dark:text-gray-200">
                                @if($recordatorio['method'] == 'email')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span>Email - </span>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <span>Notificación - </span>
                                @endif
                                
                                @if($recordatorio['minutes'] == 10)
                                    <span>10 minutos antes</span>
                                @elseif($recordatorio['minutes'] == 30)
                                    <span>30 minutos antes</span>
                                @elseif($recordatorio['minutes'] == 60)
                                    <span>1 hora antes</span>
                                @elseif($recordatorio['minutes'] == 1440)
                                    <span>1 día antes</span>
                                @else
                                    <span>{{ $recordatorio['minutes'] }} minutos antes</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if($cita->google_event_id)
            <div class="mb-6">
                <div class="bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500 p-4 rounded">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 dark:text-blue-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-blue-700 dark:text-blue-300">
                            Esta cita está sincronizada con Google Calendar.
                            <a href="https://calendar.google.com/calendar/event?eid={{ base64_encode($cita->google_event_id) }}" target="_blank" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline">
                                Ver en Google Calendar
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                <form action="{{ route('citas.destroy', $cita->id_cita) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" onclick="return confirm('¿Estás seguro de que deseas eliminar esta cita?')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Eliminar Cita
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection