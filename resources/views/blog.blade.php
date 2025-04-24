@extends('layouts.app')

@section('content')
@php
$footerSeccion = null;
@endphp

@php
    // Obtener la configuración del blog
    $blogSettings = \App\Models\BlogSettings::first();
    $blogTitle = $blogSettings->title ?? 'Blog de Agendux';
    $blogDescription = $blogSettings->description ?? 'Amplía tus conocimientos sobre gestión de citas y organización personal con los mejores consejos de Agendux.';
    $blogBgColor = $blogSettings->background_color ?? '#f9fafb';
    
    // Convertir el color HEX a una clase de Tailwind si es posible
    $bgClass = 'bg-gray-50'; // Valor predeterminado
    if ($blogBgColor === '#f9fafb') {
        $bgClass = 'bg-gray-50';
    } elseif ($blogBgColor === '#111827') {
        $bgClass = 'bg-gray-900';
    }
    // Puedes añadir más conversiones según sea necesario
@endphp

<div class="{{ $bgClass }} dark:bg-gray-900 py-12" style="background-color: {{ $blogBgColor }};">
    <!-- Blog Header -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white font-adineue mb-4">{{ $blogTitle }}</h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
            {{ $blogDescription }}
        </p>
    </div>

    <!-- Featured Posts Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($articulos as $articulo)
            <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform duration-300 hover:shadow-xl hover:-translate-y-1">
            <a href="{{ route('blog.show', $articulo->slug) }}" class="block">
    @if($articulo->imagen)
        <img src="{{ $articulo->imagen }}" alt="{{ $articulo->titulo }}" class="w-full h-48 object-cover">
    @else
        <img src="/api/placeholder/640/360" alt="{{ $articulo->titulo }}" class="w-full h-48 object-cover">
    @endif
</a>
                <div class="p-6">
                    <div class="text-xs font-semibold text-[#3161DD] uppercase tracking-wide mb-1">{{ $articulo->categoria }}</div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                        <a href="{{ route('blog.show', $articulo->slug) }}" class="hover:text-[#3161DD] transition-colors">
                            {{ $articulo->titulo }}
                        </a>
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $articulo->resumen }}</p>
                    <div class="flex items-center">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $articulo->autor }}</p>
                            <div class="flex text-sm text-gray-500 dark:text-gray-400">
                                <time>{{ \Carbon\Carbon::parse($articulo->fecha_publicacion)->format('d F, Y') }}</time>
                                <span class="mx-1">&middot;</span>
                                <span>{{ $articulo->tiempo_lectura }} min de lectura</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('blog.show', $articulo->slug) }}" class="inline-flex items-center text-[#3161DD] hover:text-[#2050C0] transition-colors">
                            Leer más
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <!-- Post 1 (Ejemplo estático) -->
            <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform duration-300 hover:shadow-xl hover:-translate-y-1">
                <img src="/api/placeholder/640/360" alt="Calendario y organización" class="w-full h-48 object-cover">
                <div class="p-6">
                    <div class="text-xs font-semibold text-[#3161DD] uppercase tracking-wide mb-1">Guía</div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Cómo optimizar tu agenda de trabajo diaria</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">En este tutorial te enseñamos cómo organizar mejor tu día a día y aumentar tu productividad con Agendux.</p>
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img src="/api/placeholder/40/40" alt="Avatar" class="h-10 w-10 rounded-full">
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Carlos Mendoza</p>
                            <div class="flex text-sm text-gray-500 dark:text-gray-400">
                                <time>15 Abril, 2025</time>
                                <span class="mx-1">&middot;</span>
                                <span>6 min de lectura</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Post 2 (Ejemplo estático) -->
            <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform duration-300 hover:shadow-xl hover:-translate-y-1">
                <img src="/api/placeholder/640/360" alt="Integración con WhatsApp" class="w-full h-48 object-cover">
                <div class="p-6">
                    <div class="text-xs font-semibold text-[#3161DD] uppercase tracking-wide mb-1">Tutorial</div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Cómo configurar recordatorios automáticos</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">En este tutorial te enseñamos cómo configurar recordatorios automáticos para tus citas y reuniones importantes.</p>
                    <div class="flex items-center">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Laura Jiménez</p>
                            <div class="flex text-sm text-gray-500 dark:text-gray-400">
                                <time>3 Abril, 2025</time>
                                <span class="mx-1">&middot;</span>
                                <span>4 min de lectura</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Post 3 (Ejemplo estático) -->
            <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform duration-300 hover:shadow-xl hover:-translate-y-1">
                <img src="/api/placeholder/640/360" alt="Integración con Google Calendar" class="w-full h-48 object-cover">
                <div class="p-6">
                    <div class="text-xs font-semibold text-[#3161DD] uppercase tracking-wide mb-1">Guía</div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Cómo integrar Google Calendar con Agendux</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">En este tutorial te enseñamos cómo integrar tu calendario de Google con Agendux para sincronizar todos tus eventos.</p>
                    <div class="flex items-center">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Martín García</p>
                            <div class="flex text-sm text-gray-500 dark:text-gray-400">
                                <time>28 Marzo, 2025</time>
                                <span class="mx-1">&middot;</span>
                                <span>5 min de lectura</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Footer exactamente como el compartido -->
@if($footerSeccion)
@php
    // Obtener contenidos de "Footer"
    $footerColumns = [];
    $copyright = date('Y') . ' Todos los derechos reservados';
    $currentColumn = null;
    
    foreach($footerSeccion->contenidos()->orderBy('orden')->get() as $contenido) {
        if($contenido->etiqueta == 'h3') {
            // Si hay una columna anterior, guardarla
            if($currentColumn) {
                $footerColumns[] = $currentColumn;
            }
            
            // Iniciar nueva columna
            $currentColumn = [
                'title' => $contenido->contenido,
                'links' => []
            ];
        } elseif($contenido->etiqueta == 'ul' && $currentColumn) {
            // Procesar links
            $linkTexts = preg_split('/\r\n|\r|\n/', $contenido->contenido);
            foreach($linkTexts as $linkText) {
                // Buscar formato [texto](url)
                if(preg_match('/\[([^\]]+)\]\(([^\)]+)\)/', $linkText, $matches)) {
                    $currentColumn['links'][] = [
                        'text' => $matches[1],
                        'url' => $matches[2]
                    ];
                } else {
                    $currentColumn['links'][] = [
                        'text' => trim($linkText),
                        'url' => '#'
                    ];
                }
            }
        } elseif($contenido->etiqueta == 'p' && strpos($contenido->contenido, '©') !== false) {
            $copyright = $contenido->contenido;
        }
    }
    
    // Guardar la última columna si existe
    if($currentColumn) {
        $footerColumns[] = $currentColumn;
    }
    
    // Valores por defecto
    if(empty($footerColumns)) {
        $footerColumns = [
            [
                'title' => 'Empresa',
                'links' => [
                    ['text' => 'Sobre nosotros', 'url' => '#'],
                    ['text' => 'Características', 'url' => '#caracteristicas'],
                    ['text' => 'Precios', 'url' => '#precios']
                ]
            ],
            [
                'title' => 'Soporte',
                'links' => [
                    ['text' => 'Ayuda', 'url' => '#'],
                    ['text' => 'Contacto', 'url' => '#'],
                    ['text' => 'FAQ', 'url' => '#']
                ]
            ],
            [
                'title' => 'Legal',
                'links' => [
                    ['text' => 'Términos y Condiciones', 'url' => '#'],
                    ['text' => 'Política de Privacidad', 'url' => '#'],
                    ['text' => 'Cookies', 'url' => '#']
                ]
            ]
        ];
    }
@endphp

<footer class="bg-gray-900 text-gray-400 py-12">
    <div class="container mx-auto px-4">
        <!-- Links organizados horizontalmente -->
        <div class="flex flex-wrap justify-center mb-12">
            @foreach($footerColumns as $column)
                <div class="px-8 py-4">
                    <h4 class="text-lg font-semibold text-white mb-4">{{ $column['title'] }}</h4>
                    <ul class="space-y-2">
                        @foreach($column['links'] as $link)
                            <li><a href="{{ $link['url'] }}" class="hover:text-white transition-colors">{{ $link['text'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
        
        <!-- Línea divisoria -->
        <div class="border-t border-gray-800 mx-auto max-w-4xl"></div>
        
        <!-- Logo y redes sociales -->
        <div class="flex flex-col md:flex-row justify-between items-center max-w-4xl mx-auto mt-8 mb-8">
            <div class="mb-6 md:mb-0">
            <a href="">
                            <x-application-logo class="block h-10 w-auto text-[#3161DD]" />
                        </a>
            </div>
            
            <div class="flex space-x-4">
                <a href="#" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                    </svg>
                </a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                    </svg>
                </a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path>
                    </svg>
                </a>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="text-center">
            <p>{!! $copyright !!}</p>
        </div>
    </div>
</footer>
@endif
@endsection