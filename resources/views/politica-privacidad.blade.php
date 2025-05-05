@extends('layouts.app')

@section('title', 'Política de Privacidad - Agendux')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-3xl space-y-8">

    <h1 class="text-4xl font-bold text-gray-900">Política de Privacidad</h1>

    <p class="text-gray-600">Última actualización: {{ $politica->updated_at ? $politica->updated_at->format('d') . ' de ' . $politica->updated_at->translatedFormat('F') . ', ' . $politica->updated_at->format('Y') : now()->format('d') . ' de ' . now()->translatedFormat('F') . ', ' . now()->format('Y') }}</p>

    <div class="prose prose-lg max-w-none text-gray-800">
        {!! $politica->contenido !!}
    </div>

</div>
@endsection
