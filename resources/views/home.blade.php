@extends('layouts.app')

@section('content')
<div class="relative">
    @php
    // Encontrar todas las secciones
    $heroSeccion = null;
    $paraQuienSeccion = null;
    $comoFuncionaSeccion = null;
    $preciosSeccion = null;
    $footerSeccion = null;
    
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
            $bgStyle = $heroSeccion->ruta_image ? "background-image: url('" . asset('storage/' . $heroSeccion->ruta_image) . "'); background-size: cover; background-position: center;" : "";
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
                        <h2 class="text-2xl md:text-3xl font-semibold mb-4 {{ $heroSeccion->ruta_image ? 'text-white' : 'text-indigo-600 dark:text-indigo-400' }}">
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
                                <a href="{{ $button['url'] }}" class="px-6 py-3 rounded-lg font-medium transition-all transform hover:scale-105 {{ $button['type'] == 'primary' ? 'bg-indigo-600 hover:bg-indigo-700 text-white' : 'bg-white hover:bg-gray-100 text-indigo-600 border border-gray-300' }}">
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
                        <h3 class="text-indigo-600 font-medium uppercase mb-3">{{ $title }}</h3>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                            {{ $subtitle }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-300 mb-6">
                            {{ $description }}
                        </p>
                        
                        <!-- Botón CTA para móviles -->
                        <div class="md:hidden mb-8">
                            <a href="{{ $ctaButton['url'] }}" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 rounded-lg text-white font-medium transition-colors w-full">
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
                            <a href="{{ $ctaButton['url'] }}" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 rounded-lg text-white font-medium transition-colors">
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
    
    <section id="como-funciona" class="py-16 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">{{ $title }}</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    {{ $description }}
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-{{ min(count($steps), 3) }} gap-8 max-w-5xl mx-auto">
                @foreach($steps as $step)
                    <div class="bg-white dark:bg-gray-700 rounded-xl p-8 text-center shadow-lg border border-gray-200 dark:border-gray-600 transform transition-all hover:-translate-y-2 hover:shadow-xl">
                        <div class="w-20 h-20 bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="text-3xl font-bold">{{ $step['number'] }}</span>
                        </div>
                        <h3 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">{{ $step['title'] }}</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            {{ $step['desc'] }}
                        </p>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ $ctaButton['url'] }}" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg inline-block transition-colors shadow-md hover:shadow-lg">
                    {{ $ctaButton['text'] }}
                </a>
            </div>
        </div>
    </section>
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
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden flex-1 max-w-md transition-transform hover:-translate-y-1 {{ $plan['featured'] ? 'border-2 border-indigo-500' : '' }}">
                            @if($plan['featured'])
                                <div class="bg-indigo-500 text-white text-center py-2">
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
                                <a href="{{ $plan['buttonUrl'] }}" class="block w-full py-3 px-6 text-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                                    {{ $plan['buttonText'] }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    
    <!-- FOOTER -->
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