@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Navegación lateral -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center mb-6">
                <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" alt="Profile" class="h-10 w-10 rounded-full mr-2">
                <div>
                    <p class="font-medium">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>

            <nav>
                <ul>
                    <li class="mb-2">
                        <a href="{{ route('dashboard.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100 text-gray-700 {{ request()->routeIs('dashboard.index') ? 'bg-gray-100 font-semibold' : '' }}">
                            Mi Perfil
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('dashboard.mensajes.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100 text-gray-700 {{ request()->routeIs('dashboard.mensajes.*') ? 'bg-gray-100 font-semibold' : '' }}">
                            Administrar Mensajes
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard.citas.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100 text-gray-700 {{ request()->routeIs('dashboard.citas.*') ? 'bg-gray-100 font-semibold' : '' }}">
                            Mis Citas
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Contenido principal -->
        <div class="md:col-span-3">
            <!-- Perfil -->
            <div id="profile" class="tab-content bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Mi Perfil</h2>
                <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium mb-1">Nombre</label>
                            <input type="text" name="name" id="name" value="{{ $user->name }}" class="w-full px-3 py-2 border rounded-md">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium mb-1">Correo Electrónico</label>
                            <input type="email" name="email" id="email" value="{{ $user->email }}" class="w-full px-3 py-2 border rounded-md" {{ $user->google_id ? 'readonly' : '' }}>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>

            <!-- Mensajes -->
            <div id="messages" class="tab-content bg-white rounded-lg shadow p-6 hidden">
                <h2 class="text-xl font-semibold mb-4">Plantillas de Mensajes</h2>

                <button id="showTemplateForm" class="mb-4 bg-green-600 text-white px-3 py-1 rounded-md hover:bg-green-700">
                    + Nueva Plantilla
                </button>

                <!-- Formulario de plantilla (inicialmente oculto) -->
                <div id="templateForm" class="mb-4 p-4 bg-gray-50 rounded-lg hidden">
                    <form action="{{ route('dashboard.mensajes.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <input type="hidden" name="mensaje_id" id="mensaje_id" value="">

                        <div class="grid gap-4 mb-3">
                            <div>
                                <label for="title" class="block text-sm font-medium mb-1">Título</label>
                                <input type="text" name="title" id="title" class="w-full px-3 py-2 border rounded-md">
                            </div>

                            <div>
                                <label for="body" class="block text-sm font-medium mb-1">Contenido</label>
                                <textarea name="body" id="body" rows="3" class="w-full px-3 py-2 border rounded-md"></textarea>
                                <p class="text-xs text-gray-500 mt-1">Puedes usar {nombre}, {fecha}, {hora} como variables.</p>
                            </div>

                            <div>
                                <label for="tipo" class="block text-sm font-medium mb-1">Tipo</label>
                                <select name="tipo" id="tipo" class="w-full px-3 py-2 border rounded-md">
                                    <option value="confirmacion">Confirmación</option>
                                    <option value="recordatorio">Recordatorio</option>
                                    <option value="cancelacion">Cancelación</option>
                                    <option value="personalizado">Personalizado</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-2">
                            <button type="button" id="cancelTemplate" class="bg-gray-300 text-gray-700 px-3 py-1 rounded-md">
                                Cancelar
                            </button>
                            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-md">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Lista de plantillas -->
                <div>
                    @forelse($mensajes ?? [] as $mensaje)
                    <div class="border-b py-3">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium">{{ $mensaje->title }}</h3>
                                <p class="text-sm text-gray-600">{{ ucfirst($mensaje->tipo) }}</p>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="editMensaje({{ $mensaje->id }})" class="text-blue-600 hover:text-blue-800 text-sm">Editar</button>
                                <form action="{{ route('admin.mensajes.destroy', $mensaje->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Eliminar</button>
                                </form>
                            </div>
                        </div>
                        <p class="text-sm mt-1">{{ Str::limit($mensaje->body, 100) }}</p>
                    </div>
                    @empty
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-blue-700">No hay plantillas de mensajes disponibles. Crea tu primera plantilla haciendo clic en "Nueva Plantilla".</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Citas -->
            <div id="appointments" class="tab-content bg-white rounded-lg shadow p-6 hidden">
                <h2 class="text-xl font-semibold mb-4">Mis Citas</h2>

                <div class="flex justify-end mb-4">
                    <a href="#" class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700">
                        + Nueva Cita
                    </a>
                </div>

                <!-- Lista de citas -->
                <div>
                    @forelse($citas ?? [] as $cita)
                    <div class="border-b py-3">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium">{{ $cita->titulo }}</h3>
                                <p class="text-sm">
                                    {{ $cita->fecha_de_la_cita->format('d/m/Y - H:i') }}
                                </p>
                            </div>
                            <div>
                                @if($cita->estado === 'confirmada')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Confirmada</span>
                                @elseif($cita->estado === 'pendiente')
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                                @elseif($cita->estado === 'cancelada')
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Cancelada</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex mt-2 space-x-2">
                            <button onclick="viewCita({{ $cita->id_cita }})" class="text-blue-600 hover:text-blue-800 text-sm">Ver</button>
                            @if($cita->estado !== 'confirmada')
                            <button onclick="confirmCita({{ $cita->id_cita }})" class="text-green-600 hover:text-green-800 text-sm">Confirmar</button>
                            @endif
                            @if($cita->estado !== 'cancelada')
                            <button onclick="cancelCita({{ $cita->id_cita }})" class="text-red-600 hover:text-red-800 text-sm">Cancelar</button>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-blue-700">No hay citas programadas. Las citas aparecerán aquí cuando los clientes las soliciten o cuando las crees manualmente.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver detalles de cita -->
<div id="citaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium">Detalles de la Cita</h3>
            <button id="closeCitaModal" class="text-gray-400 hover:text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div id="citaDetails">
            <!-- Los detalles de la cita se cargarán aquí -->
        </div>
        <div class="mt-4 flex justify-end space-x-2">
            <button id="sendReminderBtn" class="bg-green-600 text-white px-3 py-1 rounded-md hover:bg-green-700">
                Enviar Recordatorio
            </button>
            <button id="closeModalBtn" class="bg-gray-300 text-gray-700 px-3 py-1 rounded-md">
                Cerrar
            </button>
        </div>
    </div>
</div>

<!-- Meta tag CSRF para solicitudes AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Navegación entre pestañas
        document.querySelectorAll('.tab-link').forEach(tab => {
            tab.addEventListener('click', e => {
                e.preventDefault();

                // Desactivar todas las pestañas
                document.querySelectorAll('.tab-link').forEach(item => {
                    item.classList.remove('active', 'bg-gray-100');
                });

                // Ocultar todos los contenidos
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                // Activar la pestaña seleccionada
                tab.classList.add('active', 'bg-gray-100');

                // Mostrar el contenido correspondiente
                const targetId = tab.getAttribute('href').substring(1);
                document.getElementById(targetId).classList.remove('hidden');
            });
        });

        // Formulario de plantilla de mensaje
        const showTemplateFormBtn = document.getElementById('showTemplateForm');
        const templateForm = document.getElementById('templateForm');
        const cancelTemplateBtn = document.getElementById('cancelTemplate');

        if (showTemplateFormBtn && templateForm && cancelTemplateBtn) {
            showTemplateFormBtn.addEventListener('click', () => {
                // Limpiar formulario y ponerlo en modo creación
                document.getElementById('formMethod').value = 'POST';
                document.getElementById('mensaje_id').value = '';
                document.getElementById('title').value = '';
                document.getElementById('body').value = '';
                document.getElementById('tipo').value = 'confirmacion';

                // Cambiar acción del formulario
                const form = templateForm.querySelector('form');
                form.action = "{{ route('dashboard.mensajes.store') }}";

                templateForm.classList.remove('hidden');
            });

            cancelTemplateBtn.addEventListener('click', () => {
                templateForm.classList.add('hidden');
            });
        }

        // Modal de citas
        const citaModal = document.getElementById('citaModal');
        const closeCitaModal = document.getElementById('closeCitaModal');
        const closeModalBtn = document.getElementById('closeModalBtn');

        if (citaModal && closeCitaModal && closeModalBtn) {
            const closeModal = () => {
                citaModal.classList.add('hidden');
            };

            closeCitaModal.addEventListener('click', closeModal);
            closeModalBtn.addEventListener('click', closeModal);

            citaModal.addEventListener('click', e => {
                if (e.target === citaModal) closeModal();
            });
        }

        // Configurar botón de recordatorio
        const sendReminderBtn = document.getElementById('sendReminderBtn');
        if (sendReminderBtn) {
            sendReminderBtn.addEventListener('click', function() {
                const citaId = this.getAttribute('data-cita-id');
                if (citaId) sendReminder(citaId);
            });
        }

        // Inicializar datos del calendario si existen
        window.calendarData = window.calendarData || {};
    });

    // Función para editar un mensaje
    window.editMensaje = function(id) {
        if (!id) return;

        // Mostrar formulario
        const templateForm = document.getElementById('templateForm');
        if (!templateForm) return;

        templateForm.classList.remove('hidden');

        // Obtener la información del mensaje mediante AJAX
        fetch(`/dashboard/mensajes/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                // Rellenar formulario
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('mensaje_id').value = id;
                document.getElementById('title').value = data.title || '';
                document.getElementById('body').value = data.body || '';
                document.getElementById('tipo').value = data.tipo || 'confirmacion';

                // Actualizar acción del formulario
                const form = templateForm.querySelector('form');
                form.action = `/dashboard/mensajes/${id}`;
            })
            .catch(error => {
                console.error('Error al cargar datos del mensaje:', error);
                alert('Error al cargar los datos del mensaje. Por favor, intente nuevamente.');
            });
    };

    // Función para ver detalles de una cita
    window.viewCita = function(id) {
        if (!id) return;

        const citaDetails = document.getElementById('citaDetails');
        const citaModal = document.getElementById('citaModal');
        const sendReminderBtn = document.getElementById('sendReminderBtn');

        if (!citaDetails || !citaModal) return;

        // Mostrar estado de carga
        citaDetails.innerHTML = `
        <div class="flex justify-center items-center py-4">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
            <span class="ml-2">Cargando detalles...</span>
        </div>
    `;

        // Mostrar modal
        citaModal.classList.remove('hidden');

        // Actualizar ID en botón de recordatorio
        if (sendReminderBtn) {
            sendReminderBtn.setAttribute('data-cita-id', id);
        }

        // Cargar datos de la cita
        fetch(`/dashboard/citas/${id}`)
            .then(response => response.json())
            .then(data => {
                // Preparar badge de estado
                let statusBadge = '';
                if (data.estado === 'confirmada') {
                    statusBadge = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Confirmada</span>';
                } else if (data.estado === 'pendiente') {
                    statusBadge = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>';
                } else if (data.estado === 'cancelada') {
                    statusBadge = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cancelada</span>';
                }

                // Rellenar detalles
                citaDetails.innerHTML = `
                <div class="space-y-2">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Título</h4>
                        <p>${data.titulo || 'Sin título'}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Fecha y Hora</h4>
                        <p>${data.fecha_formateada || 'No disponible'} a las ${data.hora_formateada || 'No disponible'}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Estado</h4>
                        <p>${statusBadge || 'Desconocido'}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Teléfono</h4>
                        <p>${data.telefono || 'No disponible'}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Descripción</h4>
                        <p>${data.descripcion || 'Sin descripción'}</p>
                    </div>
                    <div class="pt-2 border-t">
                        <div class="flex space-x-2">
                            ${data.estado !== 'confirmada' ? 
                                `<button onclick="confirmCita(${data.id_cita || data.id})" class="text-xs bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded">Confirmar</button>` : ''}
                            ${data.estado !== 'cancelada' ? 
                                `<button onclick="cancelCita(${data.id_cita || data.id})" class="text-xs bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded">Cancelar</button>` : ''}
                        </div>
                    </div>
                </div>
            `;

                // Habilitar/deshabilitar botón de recordatorio
                if (sendReminderBtn) {
                    if (data.telefono && data.estado !== 'cancelada') {
                        sendReminderBtn.removeAttribute('disabled');
                        sendReminderBtn.classList.remove('bg-gray-400');
                        sendReminderBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                    } else {
                        sendReminderBtn.setAttribute('disabled', 'disabled');
                        sendReminderBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                        sendReminderBtn.classList.add('bg-gray-400');
                    }
                }
            })
            .catch(error => {
                console.error('Error al cargar detalles de cita:', error);
                citaDetails.innerHTML = `
                <div class="bg-red-50 p-4 rounded-md">
                    <p class="text-red-700">Error al cargar los detalles de la cita. Intente nuevamente.</p>
                </div>
            `;
            });
    };

    // Función para confirmar una cita
    window.confirmCita = function(id) {
        if (!id) return;

        if (confirm('¿Estás seguro de que deseas confirmar esta cita?')) {
            // Obtener token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Enviar solicitud
            fetch(`/dashboard/citas/${id}/confirm`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Cita confirmada correctamente.');
                        window.location.reload();
                    } else {
                        alert('Error al confirmar la cita: ' + (data.message || 'Error desconocido'));
                    }
                })
                .catch(error => {
                    console.error('Error al confirmar cita:', error);
                    alert('Error al confirmar la cita. Por favor, intente nuevamente.');
                });
        }
    };

    // Función para cancelar una cita
    window.cancelCita = function(id) {
        if (!id) return;

        if (confirm('¿Estás seguro de que deseas cancelar esta cita?')) {
            // Obtener token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Enviar solicitud
            fetch(`/dashboard/citas/${id}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Cita cancelada correctamente.');
                        window.location.reload();
                    } else {
                        alert('Error al cancelar la cita: ' + (data.message || 'Error desconocido'));
                    }
                })
                .catch(error => {
                    console.error('Error al cancelar cita:', error);
                    alert('Error al cancelar la cita. Por favor, intente nuevamente.');
                });
        }
    };

    // Función para enviar un recordatorio
    window.sendReminder = function(id) {
        if (!id) return;

        // Cambiar el estado del botón
        const sendReminderBtn = document.getElementById('sendReminderBtn');
        if (sendReminderBtn) {
            sendReminderBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Enviando...
        `;
            sendReminderBtn.disabled = true;
        }

        // Obtener token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // Enviar solicitud
        fetch(`/dashboard/citas/${id}/send-reminder`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Recordatorio enviado correctamente.');
                } else {
                    alert('Error al enviar el recordatorio: ' + (data.message || 'Error desconocido'));
                }

                // Restaurar botón
                if (sendReminderBtn) {
                    sendReminderBtn.innerHTML = 'Enviar Recordatorio';
                    sendReminderBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error al enviar recordatorio:', error);
                alert('Error al enviar el recordatorio. Por favor, intente nuevamente.');

                // Restaurar botón
                if (sendReminderBtn) {
                    sendReminderBtn.innerHTML = 'Enviar Recordatorio';
                    sendReminderBtn.disabled = false;
                }
            });
    };
</script>
@endpush
@endsection