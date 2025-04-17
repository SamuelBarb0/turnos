@extends('layouts.app')

@section('content')
<div class="relative">
    <!-- Fondo topográfico -->
    <div class="absolute inset-0 z-0 overflow-hidden">
        <div class="w-full h-full opacity-10 dark:opacity-20" style="background-color: #f8fafc; background-image: url(\"data:image/svg+xml,%3Csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3Cpattern id='smallGrid' width='60' height='60' patternUnits='userSpaceOnUse'%3E%3Cpath d='M 60 0 L 0 0 0 60' fill='none' stroke='%233161DD' stroke-width='0.2' stroke-opacity='0.5'/%3E%3C/pattern%3E%3C/defs%3E%3Crect width='100%25' height='100%25' fill='url(%23smallGrid)'/%3E%3Cpath d='M103,10 C133,30 183,150 233,150 C283,150 300,110 350,150 C400,190 420,250 500,290 C550,315 580,310 630,270 C680,230 730,180 780,150 C830,120 880,120 930,150 M53,50 C83,70 183,200 233,200 C283,200 300,160 350,200 C400,240 420,300 500,340 C550,365 580,360 630,320 C680,280 730,230 780,200 C830,170 880,170 930,200 M53,100 C83,120 183,250 233,250 C283,250 300,210 350,250 C400,290 420,350 500,390 C550,415 580,410 630,370 C680,330 730,280 780,250 C830,220 880,220 930,250' fill='none' stroke='%233161DD' stroke-width='0.5' stroke-opacity='0.4'/%3E%3Cpath d='M3,10 C33,30 83,150 133,150 C183,150 200,110 250,150 C300,190 320,250 400,290 C450,315 480,310 530,270 C580,230 630,180 680,150 C730,120 780,120 830,150 M3,60 C33,80 83,200 133,200 C183,200 200,160 250,200 C300,240 320,300 400,340 C450,365 480,360 530,320 C580,280 630,230 680,200 C730,170 780,170 830,200' fill='none' stroke='%233161DD' stroke-width='0.5' stroke-opacity='0.2'/%3E%3C/svg%3E\");"></div>
    </div>
    
    <!-- Contenido principal -->
    <div class="relative z-10 py-12 sm:py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-5xl md:text-6xl">
                    <span class="block">Bienvenido a</span>
                    <span class="block text-[#3161DD]">Agendux</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-500 dark:text-gray-300 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    La plataforma que simplifica la gestión de tu agenda y optimiza tu productividad.
                </p>
                <div class="mt-10 sm:flex sm:justify-center">
                    <div class="rounded-md shadow">
                        <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-[#3161DD] hover:bg-[#2050C0] md:py-4 md:text-lg md:px-10">
                            Comenzar ahora
                        </a>
                    </div>
                    <div class="mt-3 sm:mt-0 sm:ml-3">
                        <a href="#features" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-[#3161DD] bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 md:py-4 md:text-lg md:px-10">
                            Explorar funciones
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sección de características -->
    <div id="features" class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base font-semibold text-[#3161DD] tracking-wide uppercase">Características</h2>
                <p class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl">
                    Todo lo que necesitas para mantenerte organizado
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 dark:text-gray-300 mx-auto">
                    Descubre por qué Agendux es la mejor opción para gestionar tu tiempo y aumentar tu productividad.
                </p>
            </div>

            <div class="mt-16">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Característica 1 -->
                    <div class="pt-6">
                        <div class="flow-root bg-gray-50 dark:bg-gray-800 rounded-lg px-6 pb-8">
                            <div class="-mt-6">
                                <div>
                                    <span class="inline-flex items-center justify-center p-3 bg-[#3161DD] rounded-md shadow-lg">
                                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </span>
                                </div>
                                <h3 class="mt-8 text-lg font-medium text-gray-900 dark:text-white tracking-tight">Gestión de calendario</h3>
                                <p class="mt-5 text-base text-gray-500 dark:text-gray-400">
                                    Organiza tus citas y eventos con facilidad. Visualiza tu agenda diaria, semanal o mensual.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Característica 2 -->
                    <div class="pt-6">
                        <div class="flow-root bg-gray-50 dark:bg-gray-800 rounded-lg px-6 pb-8">
                            <div class="-mt-6">
                                <div>
                                    <span class="inline-flex items-center justify-center p-3 bg-[#3161DD] rounded-md shadow-lg">
                                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </span>
                                </div>
                                <h3 class="mt-8 text-lg font-medium text-gray-900 dark:text-white tracking-tight">Gestión de tareas</h3>
                                <p class="mt-5 text-base text-gray-500 dark:text-gray-400">
                                    Crea listas de tareas, establece prioridades y recibe recordatorios para mantener todo bajo control.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Característica 3 -->
                    <div class="pt-6">
                        <div class="flow-root bg-gray-50 dark:bg-gray-800 rounded-lg px-6 pb-8">
                            <div class="-mt-6">
                                <div>
                                    <span class="inline-flex items-center justify-center p-3 bg-[#3161DD] rounded-md shadow-lg">
                                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                    </span>
                                </div>
                                <h3 class="mt-8 text-lg font-medium text-gray-900 dark:text-white tracking-tight">Recordatorios inteligentes</h3>
                                <p class="mt-5 text-base text-gray-500 dark:text-gray-400">
                                    Nunca olvides una cita importante. Configura notificaciones personalizadas para cada evento.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sección CTA -->
    <div class="bg-[#3161DD]">
        <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                <span class="block">¿Listo para organizarte mejor?</span>
                <span class="block">Prueba Agendux hoy mismo</span>
            </h2>
            <p class="mt-4 text-lg leading-6 text-[#C5D5FF]">
                Regístrate ahora y obtén 30 días de prueba gratuita. Sin tarjeta de crédito requerida.
            </p>
            <a href="{{ route('register') }}" class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-[#3161DD] bg-white hover:bg-gray-50 sm:w-auto">
                Comenzar prueba gratuita
            </a>
        </div>
    </div>
</div>
@endsection