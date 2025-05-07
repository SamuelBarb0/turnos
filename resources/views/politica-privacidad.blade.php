@extends('layouts.app')

@section('content')

<!-- HERO -->
<section class="relative py-24 bg-[#F8F9FC]">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl font-bold mb-4 text-gray-900">Política de Privacidad</h1>
            <p class="text-lg text-gray-600">Información sobre cómo recopilamos, utilizamos y protegemos tus datos personales.</p>
            <p class="text-sm text-gray-500 mt-4">Última actualización: {{ $politica->updated_at ? $politica->updated_at->format('d') . ' de ' . $politica->updated_at->translatedFormat('F') . ', ' . $politica->updated_at->format('Y') : now()->format('d') . ' de ' . now()->translatedFormat('F') . ', ' . now()->format('Y') }}</p>
        </div>
    </div>
</section>

<!-- CONTENIDO -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-sm p-8">
            <div class="prose prose-lg max-w-none">
                {!! $politica->contenido ?? '<p class="text-gray-500 text-center py-8">Todavía no se ha definido la política de privacidad.</p>' !!}
            </div>
        </div>
    </div>
</section>

@endsection