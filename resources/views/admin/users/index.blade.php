<!-- resources/views/admin/users/index.blade.php -->

@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Gestión de Usuarios</h1>
        <div>
            <a href="{{ route('admin.paginas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver al Panel
            </a>
        </div>
    </div>

    <!-- Filtros de búsqueda -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4">
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nombre o email" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:ring-[#3161DD] focus:border-[#3161DD] sm:text-sm">
                </div>
                
                <div class="w-40">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                    <select name="role" id="role" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-[#3161DD] focus:border-[#3161DD] sm:text-sm">
                        <option value="">Todos</option>
                        <option value="{{ App\Models\User::ROLE_FREE }}" {{ request('role') == App\Models\User::ROLE_FREE ? 'selected' : '' }}>Free</option>
                        <option value="{{ App\Models\User::ROLE_BASIC }}" {{ request('role') == App\Models\User::ROLE_BASIC ? 'selected' : '' }}>Basic</option>
                        <option value="{{ App\Models\User::ROLE_PREMIUM }}" {{ request('role') == App\Models\User::ROLE_PREMIUM ? 'selected' : '' }}>Premium</option>
                        <option value="{{ App\Models\User::ROLE_ADMIN }}" {{ request('role') == App\Models\User::ROLE_ADMIN ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#3161DD] hover:bg-[#2951c7] text-white text-sm font-medium rounded-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de usuarios -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rol
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Registro
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $user->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($user->isAdmin())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Admin
                                </span>
                            @elseif($user->isPremium())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#e0e7ff] text-[#3161DD]">
                                    Premium
                                </span>
                            @elseif($user->isBasic())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Basic
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Free
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-3">
                                <button type="button"
                                        class="text-[#3161DD] hover:text-[#2951c7]"
                                        onclick="editUser({{ $user->id }}, '{{ $user->name }}', {{ $user->role }})">
                                    Cambiar rol
                                </button>
                                
                                @if(!$user->isAdmin())
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('¿Estás seguro de que quieres eliminar este usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        Eliminar
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-700">
                    Mostrando {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} de {{ $users->total() }} resultados
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar rol -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 transition-opacity z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg max-w-md w-full mx-auto overflow-hidden shadow-xl transform transition-all">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900 mb-4">
                Cambiar rol de <span id="userName" class="font-semibold"></span>
            </div>
            
            <form id="updateRoleForm" action="" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                    <select name="role" id="role" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-[#3161DD] focus:border-[#3161DD] sm:text-sm">
                        <option value="{{ App\Models\User::ROLE_FREE }}">Free</option>
                        <option value="{{ App\Models\User::ROLE_BASIC }}">Basic</option>
                        <option value="{{ App\Models\User::ROLE_PREMIUM }}">Premium</option>
                        <option value="{{ App\Models\User::ROLE_ADMIN }}">Admin</option>
                    </select>
                </div>
                
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" onclick="closeModal()" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md">
                        Cancelar
                    </button>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#3161DD] hover:bg-[#2951c7] text-white text-sm font-medium rounded-md">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editUser(userId, userName, currentRole) {
        // Configurar el modal
        document.getElementById('userName').textContent = userName;
        document.getElementById('updateRoleForm').action = `/admin/users/${userId}/update-role`;
        document.getElementById('role').value = currentRole;
        
        // Mostrar el modal
        document.getElementById('editModal').classList.remove('hidden');
    }
    
    function closeModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
    
    // Cerrar modal haciendo clic fuera de él
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('editModal');
        if (event.target === modal) {
            closeModal();
        }
    });
    
    // Cerrar modal con tecla Escape
    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endsection