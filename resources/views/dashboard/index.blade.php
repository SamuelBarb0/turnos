@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-10 space-y-10">

    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-500 mt-2">Gestiona tu perfil, mensajes y citas desde un solo lugar.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

        <!-- Sidebar -->
        <aside class="bg-white rounded-2xl shadow-lg p-6 space-y-6">
            <div class="flex items-center space-x-4">
                <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" class="h-12 w-12 rounded-full shadow" alt="Avatar">
                <div>
                    <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>

            <nav class="space-y-3 text-sm">
                <a href="{{ route('dashboard.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg {{ request()->routeIs('dashboard.index') ? 'bg-blue-100 text-blue-600 font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                    <span>👤</span> <span>Mi Perfil</span>
                </a>
                <a href="{{ route('dashboard.mensajes.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg {{ request()->routeIs('dashboard.mensajes.*') ? 'bg-blue-100 text-blue-600 font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                    <span>💬</span> <span>Mensajes</span>
                </a>
                <a href="{{ route('dashboard.citas.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg {{ request()->routeIs('dashboard.citas.*') ? 'bg-blue-100 text-blue-600 font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                    <span>📅</span> <span>Citas</span>
                </a>
            </nav>
        </aside>

        <!-- Main content -->
        <main class="md:col-span-3 space-y-10">

            <!-- Mi Perfil -->
            <section class="bg-white rounded-2xl shadow p-6 space-y-6">
                <h2 class="text-2xl font-semibold text-gray-800">Mi Perfil</h2>

                <form action="{{ route('profile.update') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf @method('PUT')

                    <div>
                        <label class="block text-sm text-gray-600 mb-2">Nombre</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-2">Correo</label>
                        <input type="email" name="email" value="{{ $user->email }}" {{ $user->google_id ? 'readonly' : '' }} class="w-full rounded-lg border-gray-300 focus:ring-blue-500">
                    </div>

                    <div class="md:col-span-2 flex justify-end">
                        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Guardar Cambios</button>
                    </div>
                </form>
            </section>

            <!-- Crear Nueva Plantilla (Redirección) -->
            <section class="bg-white rounded-2xl shadow p-6 space-y-6">
                <h2 class="text-2xl font-semibold text-gray-800">Crear Plantilla</h2>

                <p class="text-gray-500">Puedes crear una nueva plantilla personalizada para tus mensajes.</p>

                <form action="{{ route('dashboard.mensajes.index') }}" method="GET" class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Ir a Crear Plantilla
                    </button>
                </form>
            </section>



            <!-- Calendario de Citas -->
            <section class="bg-white rounded-2xl shadow p-6 space-y-6">
                <h2 class="text-2xl font-semibold text-gray-800">Calendario de Citas</h2>

                <div class="flex items-center space-x-4 mb-4">
    <button class="bg-gray-200 text-gray-700 px-3 py-1 rounded hover:bg-gray-300" onclick="changeCalendarView('dayGridMonth')">Mes</button>
    <button class="bg-gray-200 text-gray-700 px-3 py-1 rounded hover:bg-gray-300" onclick="changeCalendarView('listWeek')">Lista</button>
</div>

<div id="calendarCitas" class="rounded-lg overflow-hidden border"></div>
            </section>

        </main>
    </div>
</div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendarCitas');

    window.sidebarCalendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        height: 'auto',
        noEventsContent: "No hay citas programadas.",
        events: [
            @foreach($citas as $cita)
                @php
                    $color = '#9CA3AF'; // Gris por defecto
                    if ($cita->estado === 'confirmada') $color = '#3161DD'; // Azul Marca
                    elseif ($cita->estado === 'pendiente') $color = '#FBBF24'; // Amarillo suave
                    elseif ($cita->estado === 'cancelada') $color = '#F87171'; // Rojo suave
                @endphp
                {
                    title: '{{ $cita->titulo }}',
                    start: '{{ $cita->fecha_de_la_cita->toDateTimeString() }}',
                    color: '{{ $color }}',
                    idCita: {{ $cita->id_cita }}
                },
            @endforeach
        ],
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            if (info.event.extendedProps.idCita) {
                window.location.href = '/dashboard/citas/' + info.event.extendedProps.idCita;
            }
        }
    });

    window.sidebarCalendar.render();

    window.changeCalendarView = function(viewType) {
        if (window.sidebarCalendar) {
            window.sidebarCalendar.changeView(viewType);
        }
    }
});
</script>

@endsection
