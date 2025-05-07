@extends('layouts.app')

@section('content')
<div class="relative">
    @php
    // Encontrar todas las secciones
    $heroSeccion = null;
    $paraQuienSeccion = null;
    $comoFuncionaSeccion = null;
    $preciosSeccion = null;

    foreach($pagina->secciones()->orderBy('orden')->get() as $seccion) {
    $sectionName = strtolower($seccion->seccion);
    if ($seccion->orden == 1 || str_contains($sectionName, 'hero') || str_contains($sectionName, 'principal')) {
    $heroSeccion = $seccion;
    } elseif (str_contains($sectionName, 'quien') || $seccion->orden == 2) {
    $paraQuienSeccion = $seccion;
    } elseif (str_contains($sectionName, 'funciona') || str_contains($sectionName, 'como') || str_contains($sectionName, 'explicacion') || $seccion->orden == 3) {
    $comoFuncionaSeccion = $seccion;
    } elseif (str_contains($sectionName, 'precio') || str_contains($sectionName, 'plan') || $seccion->orden == 4) {
    $preciosSeccion = $seccion;
    } elseif (str_contains($sectionName, 'footer') || str_contains($sectionName, 'pie') || $seccion->orden == 5) {
    $footerSeccion = $seccion;
    }
    }
    @endphp

    <!-- SECCIÓN 1: HERO -->
    @if($heroSeccion)
    @php
    $bgStyle = $heroSeccion->ruta_image 
    ? "background-image: url('" . asset($heroSeccion->ruta_image) . "'); background-size: cover; background-position: center;" 
    : "";
    $hasBg = $heroSeccion->ruta_image ? 'bg-opacity-70 bg-gray-900 text-white' : '';

    // Obtener contenidos del hero
    $h1Content = '';
    $h2Content = '';
    $pContent = '';
    $buttons = [];

    foreach($heroSeccion->contenidos()->orderBy('orden')->get() as $contenido) {
    if($contenido->etiqueta == 'h1') {
    $h1Content = $contenido->contenido;
    } elseif($contenido->etiqueta == 'h2') {
    $h2Content = $contenido->contenido;
    } elseif($contenido->etiqueta == 'p') {
    $pContent = $contenido->contenido;
    } elseif($contenido->etiqueta == 'button') {
    $buttonParts = explode('|', $contenido->contenido);
    $buttons[] = [
    'text' => $buttonParts[0] ?? 'Click aquí',
    'type' => isset($buttonParts[1]) ? strtolower($buttonParts[1]) : 'primary',
    'url' => $buttonParts[2] ?? '#'
    ];
    }
    }

    // Asegurar que hay al menos un botón
    if(empty($buttons)) {
    $buttons[] = [
    'text' => 'Comenzar ahora',
    'type' => 'primary',
    'url' => route('register')
    ];
    }
    @endphp

    <section class="relative {{ $hasBg }}" style="{{ $bgStyle }}">
        @if($heroSeccion->ruta_image)
        <div class="absolute inset-0 bg-black bg-opacity-50 z-0"></div>
        @endif

        <div class="relative z-10 container mx-auto px-4 py-20 md:py-28">
            <div class="max-w-4xl mx-auto text-center">
                @if($h1Content)
                <h1 class="text-4xl md:text-5xl font-bold mb-4 {{ $heroSeccion->ruta_image ? 'text-white' : 'text-gray-900 dark:text-white' }}">
                    {!! $h1Content !!}
                </h1>
                @endif

                @if($h2Content)
                <h2 class="text-2xl md:text-3xl font-semibold mb-4 {{ $heroSeccion->ruta_image ? 'text-white' : 'text-[#3161DD] dark:text-[#7a98e9]' }}">
                    {!! $h2Content !!}
                </h2>
                @endif

                @if($pContent)
                <p class="text-lg md:text-xl mb-8 {{ $heroSeccion->ruta_image ? 'text-gray-200' : 'text-gray-600 dark:text-gray-300' }}">
                    {!! $pContent !!}
                </p>
                @endif

                @if(!empty($buttons))
                <div class="flex flex-wrap justify-center gap-4">
                    @foreach($buttons as $index => $button)
                    <a href="{{ $button['url'] }}" class="px-6 py-3 rounded-lg font-medium transition-all transform hover:scale-105 {{ $button['type'] == 'primary' ? 'bg-[#3161DD] hover:bg-[#2651c0] text-white' : 'bg-white hover:bg-gray-100 text-[#3161DD] border border-gray-300' }}">
                        {{ $button['text'] }}
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    <!-- SECCIÓN 2: PARA QUIÉN ES -->
    @if($paraQuienSeccion)
    @php
    // Obtener contenidos de "Para quién es"
    $title = '';
    $subtitle = '';
    $description = '';
    $listItems = [];
    $ctaButton = null;

    foreach($paraQuienSeccion->contenidos()->orderBy('orden')->get() as $contenido) {
    if($contenido->etiqueta == 'h3') {
    $title = $contenido->contenido;
    } elseif($contenido->etiqueta == 'h2') {
    $subtitle = $contenido->contenido;
    } elseif($contenido->etiqueta == 'p') {
    $description = $contenido->contenido;
    } elseif($contenido->etiqueta == 'li') {
    $listItems[] = $contenido->contenido;
    } elseif($contenido->etiqueta == 'button') {
    $buttonParts = explode('|', $contenido->contenido);
    $ctaButton = [
    'text' => $buttonParts[0] ?? 'Crea tu cuenta gratis',
    'url' => $buttonParts[2] ?? route('register')
    ];
    }
    }

    // Valores por defecto si no hay contenido
    if(empty($title)) $title = '¿PARA QUIÉN ES?';
    if(empty($subtitle)) $subtitle = 'Ideal para profesionales que dependen de citas';
    if(empty($description)) $description = 'Simplifica la gestión de citas y ahorra horas automatizando procesos por WhatsApp';

    if(empty($listItems)) {
    $listItems = [
    'Médicos, odontólogos, clínicas',
    'Estéticas, peluquerías, barberías',
    'Psicólogos, terapeutas, nutricionistas',
    'Reuniones por Google Meet/Zoom',
    'Servicio de autos, limpieza, reparaciones',
    'Y más...'
    ];
    }

    if(!$ctaButton) {
    $ctaButton = [
    'text' => 'Crea tu cuenta gratis',
    'url' => route('register')
    ];
    }
    @endphp

    <section id="para-quien" class="py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                <!-- Columna izquierda -->
                <div>
                    <h3 class="text-[#3161DD] font-medium uppercase mb-3">{{ $title }}</h3>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ $subtitle }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        {{ $description }}
                    </p>

                    <!-- Botón CTA para móviles -->
                    <div class="md:hidden mb-8">
                        <a href="{{ $ctaButton['url'] }}" class="inline-flex items-center justify-center px-6 py-3 bg-[#3161DD] hover:bg-[#2651c0] rounded-lg text-white font-medium transition-colors w-full">
                            {{ $ctaButton['text'] }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Columna derecha con lista de items -->
                <div>
                    <ul class="space-y-4">
                        @foreach($listItems as $item)
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-700 dark:text-gray-200">{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>

                    <!-- Botón CTA (visible en tablet/desktop) -->
                    <div class="hidden md:block mt-8">
                        <a href="{{ $ctaButton['url'] }}" class="inline-flex items-center justify-center px-6 py-3 bg-[#3161DD] hover:bg-[#2651c0] rounded-lg text-white font-medium transition-colors">
                            {{ $ctaButton['text'] }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- SECCIÓN 3: CÓMO FUNCIONA / EXPLICACIÓN -->
    @if($comoFuncionaSeccion)
    @php
    // Obtener contenidos de "Cómo funciona"
    $title = '';
    $description = '';
    $steps = [];
    $ctaButton = null;

    // Variables para rastrear el estado de los pasos
    $inStep = false;
    $currentStepTitle = '';
    $currentStepDesc = '';
    $stepNumber = 1;

    foreach($comoFuncionaSeccion->contenidos()->orderBy('orden')->get() as $contenido) {
    if($contenido->etiqueta == 'h2' && empty($title)) {
    $title = $contenido->contenido;
    } elseif($contenido->etiqueta == 'p' && empty($description)) {
    $description = $contenido->contenido;
    } elseif($contenido->etiqueta == 'h3' && preg_match('/^\d+\.?\s*(.+)$/', $contenido->contenido, $matches)) {
    // Si era un paso anterior, guardarlo
    if($inStep && !empty($currentStepTitle)) {
    $steps[] = [
    'number' => count($steps) + 1,
    'title' => $currentStepTitle,
    'desc' => $currentStepDesc
    ];
    }

    // Iniciar un nuevo paso
    $inStep = true;
    $currentStepTitle = $matches[1] ?? $contenido->contenido;
    $currentStepDesc = '';
    } elseif($contenido->etiqueta == 'p' && $inStep) {
    $currentStepDesc = $contenido->contenido;

    // Guardar este paso
    $steps[] = [
    'number' => count($steps) + 1,
    'title' => $currentStepTitle,
    'desc' => $currentStepDesc
    ];

    // Reiniciar para el siguiente paso
    $inStep = false;
    $currentStepTitle = '';
    $currentStepDesc = '';
    } elseif($contenido->etiqueta == 'button') {
    $buttonParts = explode('|', $contenido->contenido);
    $ctaButton = [
    'text' => $buttonParts[0] ?? 'Comenzar ahora',
    'url' => $buttonParts[2] ?? route('register')
    ];
    }
    }

    // Si quedó un paso pendiente, guardarlo
    if($inStep && !empty($currentStepTitle)) {
    $steps[] = [
    'number' => count($steps) + 1,
    'title' => $currentStepTitle,
    'desc' => $currentStepDesc
    ];
    }

    // Valores por defecto
    if(empty($title)) $title = 'Cómo funciona nuestra plataforma';
    if(empty($description)) $description = 'Simple, intuitivo y efectivo. Conoce cómo nuestra plataforma te ayuda a gestionar tus citas.';

    if(empty($steps)) {
    $steps = [
    ['number' => 1, 'title' => 'Configura tu agenda', 'desc' => 'Define tus horarios disponibles, servicios y duración de las citas para que tus clientes puedan reservar.'],
    ['number' => 2, 'title' => 'Recibe reservas', 'desc' => 'Tus clientes pueden reservar en línea o tú puedes agendar manualmente sus citas en el sistema.'],
    ['number' => 3, 'title' => 'Automatiza recordatorios', 'desc' => 'El sistema envía automáticamente recordatorios por WhatsApp, reduciendo las inasistencias y mejorando la experiencia.']
    ];
    }

    if(!$ctaButton) {
    $ctaButton = [
    'text' => 'Empieza ahora',
    'url' => route('register')
    ];
    }
    @endphp

    <section id="como-funciona" class="py-20 bg-gray-50 dark:bg-gray-800 text-center relative">
        <!-- Formas geométricas disruptivas (usando los mismos estilos de la sección de servicios) -->
        <div class="shape-disruptor shape-1"></div>
        <div class="shape-disruptor shape-2"></div>

        <div class="container mx-auto px-4">
            <span class="services-tag scroll-reveal">PROCESO</span>
            <h2 class="services-title scroll-reveal">{{ $title }}</h2>
            <p class="services-description scroll-reveal delay-1">
                {{ $description }}
            </p>

            <div class="services-grid">
                @foreach($steps as $index => $step)
                <div class="service-card scroll-reveal delay-{{ min($index + 1, 3) }}">
                    <div class="w-20 h-20 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center border border-[#3161DD]">
                        <span class="text-3xl font-bold text-[#3161DD]">{{ $step['number'] }}</span>
                    </div>
                    <h3>{{ $step['title'] }}</h3>
                    <p>{{ $step['desc'] }}</p>
                    <span class="more-info">
                        Paso {{ $step['number'] }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    </span>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ $ctaButton['url'] }}" class="px-8 py-4 bg-[#3161DD] hover:bg-[#2651c0] text-white font-medium rounded-lg inline-block transition-colors shadow-md hover:shadow-lg">
                    {{ $ctaButton['text'] }}
                </a>
            </div>
        </div>
    </section>

    <style>
        /* Estilos específicos para la sección Cómo Funciona, manteniendo la coherencia con el resto del sitio */
        #como-funciona .shape-disruptor {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, #3161DD 0%, #7a98e9 100%);
            filter: blur(80px);
            opacity: 0.15;
            z-index: 0;
        }

        #como-funciona .shape-1 {
            top: 10%;
            left: 5%;
            width: 400px;
            height: 400px;
        }

        #como-funciona .shape-2 {
            bottom: 10%;
            right: 5%;
            width: 350px;
            height: 350px;
            background: linear-gradient(45deg, #3161DD 0%, #7a98e9 100%);
        }

        /* Estilos para las tarjetas basados en los estilos de las tarjetas de servicio,
      pero adaptados a la paleta de colores de la sección Cómo Funciona */
        #como-funciona .service-card {
            background-color: white;
            border-radius: 1rem;
            padding: 2rem 1.5rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(49, 97, 221, 0.08);
            box-shadow: 0 10px 30px -15px rgba(0, 0, 0, 0.15);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100%;
        }

        #como-funciona .service-card .w-20 {
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        #como-funciona .service-card h3 {
            color: var(--gray-900, #111827);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            transition: color 0.3s ease;
        }

        #como-funciona .service-card p {
            color: var(--gray-600, #4B5563);
            margin-bottom: 1.5rem;
            transition: color 0.3s ease;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* Efecto hover */
        #como-funciona .service-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 40px -20px rgba(0, 0, 0, 0.2);
            border-color: rgba(49, 97, 221, 0.2);
        }

        #como-funciona .service-card:hover h3 {
            color: #3161DD;
        }

        #como-funciona .service-card:hover .w-20 {
            transform: scale(1.1);
        }

        /* Efecto de línea diagonal */
        #como-funciona .service-card::after {
            content: "";
            position: absolute;
            top: -100%;
            left: -100%;
            width: 120%;
            height: 120%;
            background: linear-gradient(45deg,
                    transparent 0%,
                    rgba(49, 97, 221, 0.03) 30%,
                    rgba(49, 97, 221, 0.1) 50%,
                    rgba(49, 97, 221, 0.03) 70%,
                    transparent 100%);
            transition: transform 0.7s ease;
            transform: rotate(45deg) translateY(-100%);
            z-index: 0;
        }

        #como-funciona .service-card:hover::after {
            transform: rotate(45deg) translateY(100%);
        }

        /* Nuevo enlace "Más información" */
        #como-funciona .service-card .more-info {
            color: #3161DD;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            margin-top: auto;
            transition: all 0.3s ease;
            opacity: 0.7;
            transform: translateY(5px);
            padding-top: 0.5rem;
            font-size: 0.95rem;
        }

        #como-funciona .service-card:hover .more-info {
            opacity: 1;
            transform: translateY(0);
        }

        #como-funciona .service-card .more-info svg {
            width: 18px;
            height: 18px;
            margin-left: 4px;
        }

        /* Estilo para la etiqueta PROCESO */
        #como-funciona .services-tag {
            display: inline-block;
            background-color: rgba(49, 97, 221, 0.15);
            border: 1px solid rgba(49, 97, 221, 0.3);
            border-radius: 50px;
            color: #3161DD;
            font-size: 1rem;
            padding: 10px 20px;
            margin-bottom: 1.8rem;
            font-weight: 500;
        }

        /* Título de sección */
        #como-funciona .services-title {
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--gray-900, #111827);
            margin-bottom: 1.5rem;
            position: relative;
        }

        #como-funciona .services-title::after {
            content: "";
            display: block;
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, #3161DD, rgba(49, 97, 221, 0.3));
            border-radius: 2px;
            margin: 1rem auto 0;
        }

        /* Descripción */
        #como-funciona .services-description {
            max-width: 800px;
            margin: 0 auto 3rem;
            color: var(--gray-600, #4B5563);
            font-size: 1.2rem;
            line-height: 1.6;
        }

        /* Grid responsivo para las tarjetas */
        #como-funciona .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Modo oscuro */
        .dark #como-funciona .service-card {
            background-color: var(--gray-700, #374151);
            border-color: rgba(122, 152, 233, 0.08);
        }

        .dark #como-funciona .service-card h3 {
            color: white;
        }

        .dark #como-funciona .service-card p {
            color: rgba(255, 255, 255, 0.7);
        }

        .dark #como-funciona .services-title {
            color: white;
        }

        .dark #como-funciona .services-description {
            color: rgba(255, 255, 255, 0.8);
        }
    </style>
    @endif


<!-- SECCIÓN 4: PRECIOS -->
@if($preciosSeccion)
    @php
    // Obtener contenidos de "Precios"
    $title = '';
    $description = '';
    $plans = [];

    // Variables para rastrear el estado de los planes
    $currentPlan = [
    'title' => '',
    'price' => '',
    'period' => '',
    'featured' => false,
    'features' => [],
    'buttonUrl' => '',
    'buttonText' => ''
    ];
    $inPlan = false;

    foreach($preciosSeccion->contenidos()->orderBy('orden')->get() as $contenido) {
    if($contenido->etiqueta == 'h2' && empty($title)) {
    $title = $contenido->contenido;
    } elseif($contenido->etiqueta == 'p' && empty($description)) {
    $description = $contenido->contenido;
    } elseif($contenido->etiqueta == 'h3') {
    // Si estábamos en un plan, guardarlo
    if($inPlan && !empty($currentPlan['title'])) {
    $plans[] = $currentPlan;
    }

    // Iniciar nuevo plan
    $inPlan = true;
    $currentPlan = [
    'title' => $contenido->contenido,
    'price' => '',
    'period' => '',
    'featured' => strpos(strtolower($contenido->contenido), 'recomendado') !== false,
    'features' => [],
    'buttonUrl' => route('register'),
    'buttonText' => 'Comenzar'
    ];
    } elseif($contenido->etiqueta == 'p' && $inPlan && empty($currentPlan['price'])) {
    // Extraer precio y período
    $priceParts = explode('/', $contenido->contenido);
    $currentPlan['price'] = $priceParts[0] ?? '';
    $currentPlan['period'] = isset($priceParts[1]) ? str_replace(')', '', str_replace('(', '', $priceParts[1])) : 'mes';
    } elseif($contenido->etiqueta == 'ul' && $inPlan) {
    // Extraer características
    $features = preg_split('/\r\n|\r|\n/', $contenido->contenido);
    foreach($features as $feature) {
    $feature = trim($feature);
    if(!empty($feature)) {
    // Eliminar enlaces
    $feature = preg_replace('/\[([^\]]+)\]\([^\)]+\)/', '$1', $feature);
    $currentPlan['features'][] = $feature;
    }
    }
    } elseif($contenido->etiqueta == 'button' && $inPlan) {
    $buttonParts = explode('|', $contenido->contenido);
    $currentPlan['buttonText'] = $buttonParts[0] ?? 'Comenzar';
    $currentPlan['buttonUrl'] = $buttonParts[2] ?? route('register');

    // Guardar el plan y reiniciar
    $plans[] = $currentPlan;
    $inPlan = false;
    $currentPlan = [
    'title' => '',
    'price' => '',
    'period' => '',
    'featured' => false,
    'features' => [],
    'buttonUrl' => '',
    'buttonText' => ''
    ];
    }
    }

    // Si quedó un plan sin guardar
    if($inPlan && !empty($currentPlan['title'])) {
    $plans[] = $currentPlan;
    }

    // Valores por defecto
    if(empty($title)) $title = 'Planes a tu medida';
    if(empty($description)) $description = 'Elige el plan que mejor se adapte a tus necesidades';

    if(empty($plans)) {
    $plans = [
    [
    'title' => 'Plan Mensual',
    'price' => '$19.99',
    'period' => 'mes',
    'featured' => false,
    'features' => [
    'Hasta 100 citas mensuales',
    'Recordatorios por WhatsApp',
    'Calendario personalizado',
    'Soporte básico'
    ],
    'buttonUrl' => route('register'),
    'buttonText' => 'Comenzar'
    ],
    [
    'title' => 'Plan Anual',
    'price' => '$14.99',
    'period' => 'mes',
    'featured' => true,
    'features' => [
    'Citas ilimitadas',
    'Recordatorios por WhatsApp y Email',
    'Integración con Google Calendar',
    'Soporte prioritario 24/7',
    'Analíticas avanzadas'
    ],
    'buttonUrl' => route('register'),
    'buttonText' => 'Ahorrar ahora'
    ]
    ];
    }
    @endphp

    <section id="precios" class="py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">{{ $title }}</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    {{ $description }}
                </p>
            </div>

            <div class="flex flex-col md:flex-row justify-center gap-8">
                @foreach($plans as $plan)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden flex-1 max-w-md transition-transform hover:-translate-y-1 {{ $plan['featured'] ? 'border-2 border-[#3161DD]' : '' }}">
                    @if($plan['featured'])
                    <div class="bg-[#3161DD] text-white text-center py-2">
                        <span class="font-medium">Recomendado</span>
                    </div>
                    @endif
                    <div class="p-8">
                        <h3 class="text-xl font-semibold text-center mb-2">{{ $plan['title'] }}</h3>
                        <div class="text-center mb-6">
                            <span class="text-4xl font-bold">{{ $plan['price'] }}</span>
                            <span class="text-gray-500"> / {{ $plan['period'] }}</span>
                            @if(strtolower($plan['period']) == 'mes' && $plan['featured'])
                            <p class="text-sm text-gray-500 mt-1">Facturado anualmente (${{ floatval(preg_replace('/[^0-9.]/', '', $plan['price'])) * 12 }})</p>
                            @endif
                        </div>
                        <ul class="space-y-3 mb-8">
                            @foreach($plan['features'] as $feature)
                            <li class="flex items-center">
                                <svg class="h-5 w-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>{{ $feature }}</span>
                            </li>
                            @endforeach
                        </ul>
                        
                        <!-- Alerta de autenticación (inicialmente oculta) -->
                        <div id="auth-alert-{{ $loop->index }}" class="p-4 mb-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-md hidden">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Debes estar logueado para comprar.</strong> Necesitamos asociar tu pago con tu cuenta de usuario.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Botón de compra -->
                        @auth
                            <button 
                                onclick="pagarPlan('{{ $plan['title'] }}', '{{ floatval(preg_replace('/[^0-9.]/', '', $plan['price'])) }}')" 
                                class="block w-full py-3 px-6 text-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                                {{ $plan['buttonText'] }}
                            </button>
                        @else
                            <button 
                                onclick="mostrarAlertaAuth({{ $loop->index }})" 
                                class="block w-full py-3 px-6 text-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                                {{ $plan['buttonText'] }}
                            </button>
                            
                            <a href="{{ route('login') }}" 
                               id="login-button-{{ $loop->index }}"
                               class="mt-3 block w-full py-3 px-6 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition-colors hidden">
                                Iniciar Sesión
                            </a>
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    @endif
    @endsection