@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-md p-8 text-center">
        <div class="mb-6">
            <svg class="h-16 w-16 text-yellow-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Pago Pendiente</h1>
        <p class="text-gray-600 dark:text-gray-300 mb-6">Tu pago está siendo procesado. Esto puede tomar algunos minutos.</p>
        
        <div class="bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 p-4 mb-6 text-left">
            <h3 class="font-medium text-yellow-800 dark:text-yellow-300 mb-1">¿Qué significa esto?</h3>
            <p class="text-sm text-yellow-700 dark:text-yellow-200">
                Si elegiste un método de pago como transferencia o pago en efectivo, tu pago estará pendiente hasta que se confirme.
                Recibirás una notificación cuando el pago se complete correctamente.
            </p>
        </div>
        
        <a href="{{ route('home') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg transition-colors">
            Volver al inicio
        </a>
    </div>
</div>
@endsection