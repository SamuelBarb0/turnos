@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">
                {{ __('Detalles del Pago') }}
            </h1>
            <div>
                <a href="{{ route('admin.payments.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Volver
                </a>
            </div>
        </div>

        <!-- Estado del pago -->
        <div class="mb-6">
            <span class="px-4 py-2 inline-flex text-md leading-5 font-semibold rounded-full {{ $payment->status_class }} text-white">
                {{ ucfirst($payment->status) }}
            </span>
        </div>
        
        <!-- Información principal -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Información del Pago</h3>
                
                <div class="grid grid-cols-1 gap-3">
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">ID:</span>
                        <span class="font-medium">{{ $payment->id }}</span>
                    </div>
                    
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">Fecha:</span>
                        <span class="font-medium">{{ $payment->formatted_date }}</span>
                    </div>
                    
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">Título:</span>
                        <span class="font-medium">{{ $payment->title }}</span>
                    </div>
                    
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">Monto:</span>
                        <span class="font-medium">{{ $payment->formatted_amount }}</span>
                    </div>
                    
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">Usuario:</span>
                        <span class="font-medium">
                            @if ($payment->user)
                                {{ $payment->user->name }} ({{ $payment->user->email }})
                            @else
                                <span class="text-gray-400">Sin usuario</span>
                            @endif
                        </span>
                    </div>
                    
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">Estado:</span>
                        <span class="font-medium">{{ ucfirst($payment->status) }}</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Información de MercadoPago</h3>
                
                <div class="grid grid-cols-1 gap-3">
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">Preference ID:</span>
                        <span class="font-medium">{{ $payment->preference_id }}</span>
                    </div>
                    
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">Payment ID:</span>
                        <span class="font-medium">{{ $payment->payment_id ?? 'No disponible' }}</span>
                    </div>
                    
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">Referencia Externa:</span>
                        <span class="font-medium">{{ $payment->external_reference }}</span>
                    </div>
                    
                    @if($payment->related_type && $payment->related_id)
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">Relacionado con:</span>
                        <span class="font-medium">
                            {{ class_basename($payment->related_type) }} #{{ $payment->related_id }}
                            @if($payment->related)
                                @if(method_exists($payment->related, 'getTitle'))
                                    ({{ $payment->related->getTitle() }})
                                @elseif(property_exists($payment->related, 'title'))
                                    ({{ $payment->related->title }})
                                @endif
                            @endif
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Detalles de Pago completos -->
        @if($payment->payment_details)
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Detalles Completos del Pago</h3>
            
            <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto">
                <pre class="text-sm">{{ json_encode(json_decode($payment->payment_details), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
        @endif
    </div>
@endsection