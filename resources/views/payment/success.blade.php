@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md p-8 text-center">
        <div class="mb-6">
            <svg class="h-16 w-16 text-green-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-900 mb-2">¡Pago Exitoso!</h1>
        <p class="text-gray-600 mb-6">Tu pago ha sido procesado correctamente.</p>
        
        @if(isset($payment_id))
        <div class="bg-gray-100 p-4 rounded-lg mb-6">
            <p class="text-sm text-gray-600">ID de Pago: <span class="font-semibold">{{ $payment_id }}</span></p>
            <p class="text-sm text-gray-600">Estado: <span class="font-semibold">{{ $status }}</span></p>
        </div>
        @endif
        
        <a href="{{ route('home') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg transition-colors">
            Volver al inicio
        </a>
    </div>
</div>
@endsection