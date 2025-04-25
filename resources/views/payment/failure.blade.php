@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-md p-8 text-center">
        <div class="mb-6">
            <svg class="h-16 w-16 text-red-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Pago Fallido</h1>
        <p class="text-gray-600 dark:text-gray-300 mb-6">Lo sentimos, hubo un problema al procesar tu pago.</p>
        
        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg mb-6 text-left">
            <h3 class="font-medium text-gray-900 dark:text-white mb-2">Posibles causas:</h3>
            <ul class="list-disc pl-5 text-sm text-gray-600 dark:text-gray-300 space-y-1">
                <li>Fondos insuficientes en la tarjeta</li>
                <li>Datos de la tarjeta incorrectos</li>
                <li>La tarjeta fue rechazada por el banco emisor</li>
                <li>Problemas de conexión durante el proceso de pago</li>
            </ul>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('home') }}" class="inline-block bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-6 rounded-lg transition-colors">
                Volver al inicio
            </a>
            <a href="#" onclick="history.back(); return false;" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg transition-colors">
                Intentar nuevamente
            </a>
        </div>
    </div>
</div>
@endsection