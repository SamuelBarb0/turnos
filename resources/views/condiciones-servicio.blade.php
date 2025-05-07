@extends('layouts.app')

@section('title', 'Condiciones de Servicio')

@section('content')

<!-- HERO -->
<section class="relative py-24 bg-[#F8F9FC]">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl font-bold mb-4 text-gray-900">Condiciones de Servicio</h1>
            <p class="text-lg text-gray-600">Lea detenidamente los términos y condiciones que rigen el uso de nuestros servicios.</p>
        </div>
    </div>
</section>

<!-- CONTENIDO -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-sm p-8">
            <div class="prose prose-lg max-w-none">
                {!! $condiciones->contenido ?? '<p class="text-gray-500 text-center py-8">Todavía no se han definido las condiciones de servicio.</p>' !!}
            </div>
        </div>
    </div>
</section>

@endsection