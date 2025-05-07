@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <div class="mb-4 md:mb-0">
            <h1 class="text-2xl font-bold text-gray-800">Administración de Páginas</h1>
        </div>
        <div>
            <a href="{{ route('admin.blog.create') }}" class="bg-[#3161DD] hover:bg-[#2050C0] text-white px-4 py-2 rounded-md text-sm font-medium flex items-center transition duration-300">
                <i class="fas fa-plus mr-2"></i> Nuevo Artículo
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <!-- Pestañas de navegación -->
    <div class="mb-6 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px">
            <li class="mr-2">
                <a class="inline-block py-2 px-4 text-[#3161DD] border-b-2 border-[#3161DD] font-semibold" href="#">
                    Páginas
                </a>
            </li>
            <li class="mr-2">
                <a class="inline-block py-2 px-4 text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300" href="#">
                    Blog
                </a>
            </li>
            <li class="mr-2">
                <a class="inline-block py-2 px-4 text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300" href="#">
                    Política de Privacidad
                </a>
            </li>
            <li class="mr-2">
                <a class="inline-block py-2 px-4 text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300" href="#">
                    Contacto
                </a>
            </li>
        </ul>
    </div>

    <!-- Contenedor principal para paneles -->
    <div class="panels-container">
        <!-- Panel de Páginas -->
        <div id="paginas-panel">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Creado</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($paginas as $pagina)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pagina->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $pagina->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pagina->slug }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pagina->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.paginas.edit', $pagina) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs flex items-center transition duration-300">
                                            <i class="fas fa-edit mr-1"></i> Editar
                                        </a>
                                        <a href="{{ route('admin.paginas.secciones.index', $pagina) }}" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs flex items-center transition duration-300">
                                            <i class="fas fa-list mr-1"></i> Secciones
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No hay páginas registradas</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Panel de Blog (Oculto inicialmente) -->
        <div id="blog-panel" class="hidden">
            <!-- Blog Header Editable -->
            <div class="bg-gray-50 rounded-lg shadow-md p-8 mb-6">
                <form action="{{ route('admin.blog.settings.update') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="text-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-700">Configuración de la página del Blog</h2>
                        <p class="text-sm text-gray-500">Estos ajustes son mostrados en la página principal del blog</p>
                    </div>

                    <div>
                        <label for="blog_title" class="block text-sm font-medium text-gray-700 mb-1">Título del Blog</label>
                        <input type="text" name="blog_title" id="blog_title" value="{{ $blog_settings->title ?? 'Blog de Agendux' }}" class="shadow-sm focus:ring-[#3161DD] focus:border-[#3161DD] block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label for="blog_description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea name="blog_description" id="blog_description" rows="2" class="shadow-sm focus:ring-[#3161DD] focus:border-[#3161DD] block w-full sm:text-sm border-gray-300 rounded-md">{{ $blog_settings->description ?? 'Amplía tus conocimientos sobre gestión de citas y organización personal con los mejores consejos de Agendux.' }}</textarea>
                    </div>

                    <div>
                        <label for="blog_background" class="block text-sm font-medium text-gray-700 mb-1">Color de fondo</label>
                        <div class="flex">
                            <input type="color" name="blog_background_color" id="blog_background_color" value="{{ $blog_settings->background_color ?? '#f9fafb' }}" class="h-10 w-10 rounded border-gray-300 mr-2">
                            <input type="text" name="blog_background_color_hex" id="blog_background_color_hex" value="{{ $blog_settings->background_color ?? '#f9fafb' }}" class="shadow-sm focus:ring-[#3161DD] focus:border-[#3161DD] block w-32 sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#3161DD] hover:bg-[#2050C0] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3161DD]">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>

            <!-- Vista previa -->
            <div class="bg-gray-50 rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-700 mb-3">Vista previa</h3>
                <div class="bg-{{ $blog_settings->background_color ?? 'gray-50' }} dark:bg-gray-900 py-8 rounded-lg">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-adineue mb-3">{{ $blog_settings->title ?? 'Blog de Agendux' }}</h1>
                        <p class="text-base text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                            {{ $blog_settings->description ?? 'Amplía tus conocimientos sobre gestión de citas y organización personal con los mejores consejos de Agendux.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tabla de artículos -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Autor</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($articulos ?? [] as $articulo)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $articulo->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $articulo->titulo }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $articulo->categoria }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $articulo->autor }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $articulo->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $articulo->estado == 'publicado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($articulo->estado) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.blog.edit', $articulo->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs flex items-center transition duration-300">
                                            <i class="fas fa-edit mr-1"></i> Editar
                                        </a>
                                        <a href="{{ route('blog.show', $articulo->slug) }}" target="_blank" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs flex items-center transition duration-300">
                                            <i class="fas fa-eye mr-1"></i> Ver
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    <div class="py-8">
                                        <p class="text-gray-500 mb-4">No hay artículos publicados en el blog</p>
                                        <a href="{{ route('admin.blog.create') }}" class="bg-[#3161DD] hover:bg-[#2050C0] text-white px-4 py-2 rounded-md text-sm font-medium inline-flex items-center transition duration-300">
                                            <i class="fas fa-plus mr-2"></i> Crear primer artículo
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Panel de Política de Privacidad (Oculto inicialmente) -->
        <div id="politica-panel" class="hidden">
            <div class="bg-white rounded-lg shadow-md overflow-hidden p-6 space-y-6">
                <form action="{{ route('admin.politica.update') }}" method="POST" onsubmit="syncPolicyContent()">
                    @csrf
                    @method('PUT')

                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Editar Política de Privacidad</h2>

                    <!-- Herramientas de edición -->
                    <div class="bg-gray-50 p-3 rounded-t-md border border-gray-300 border-b-0 flex flex-wrap gap-2">
                        <button type="button" onclick="policyMakeBold()" class="text-sm bg-white px-3 py-1 rounded border border-gray-300 hover:bg-gray-100" title="Negrita (Ctrl+B)">B</button>
                        <button type="button" onclick="policyMakeItalic()" class="text-sm bg-white px-3 py-1 rounded border border-gray-300 hover:bg-gray-100" title="Cursiva (Ctrl+I)">I</button>
                        <button type="button" onclick="policyMakeHeading()" class="text-sm bg-white px-3 py-1 rounded border border-gray-300 hover:bg-gray-100" title="Encabezado H3">H3</button>
                        <button type="button" onclick="policyMakeList()" class="text-sm bg-white px-3 py-1 rounded border border-gray-300 hover:bg-gray-100" title="Lista">LI</button>
                    </div>

                    <!-- Editor visual -->
                    <div id="policy-editor"
                        contenteditable="true"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3161DD] focus:ring-[#3161DD] bg-white p-4 min-h-[300px] space-y-2 prose prose-sm max-w-none"
                        style="outline: none;">
                        {!! old('contenido', $politica->contenido ?? '') !!}
                    </div>

                    <!-- Campo oculto para enviar el contenido -->
                    <input type="hidden" name="contenido" id="policy-editor-hidden">

                    <div class="flex justify-end mt-4">
                        <button type="submit" class="px-4 py-2 bg-[#3161DD] hover:bg-[#2050C0] text-white rounded-md text-sm">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Panel de Contacto (Oculto inicialmente) -->
        <div id="contacto-panel" class="hidden">
            <div class="bg-gray-50 rounded-lg shadow-md p-8 mb-6">
                <form action="{{ route('admin.contacto.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="text-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-700">Configuración de la página de Contacto</h2>
                        <p class="text-sm text-gray-500">Estos ajustes son mostrados en la página de contacto del sitio web.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label for="contact_tag" class="block text-sm font-medium text-gray-700 mb-1">Etiqueta</label>
                            <input type="text" name="contact_tag" id="contact_tag" value="{{ old('contact_tag', $contacto->tag ?? 'Contacto') }}" class="shadow-sm focus:ring-[#3161DD] focus:border-[#3161DD] block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="contact_title" class="block text-sm font-medium text-gray-700 mb-1">Título principal</label>
                            <input type="text" name="contact_title" id="contact_title" value="{{ old('contact_title', $contacto->title ?? 'Hablemos de tu proyecto') }}" class="shadow-sm focus:ring-[#3161DD] focus:border-[#3161DD] block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="md:col-span-2">
                            <label for="contact_description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                            <textarea name="contact_description" id="contact_description" rows="2" class="shadow-sm focus:ring-[#3161DD] focus:border-[#3161DD] block w-full sm:text-sm border-gray-300 rounded-md">{{ old('contact_description', $contacto->description ?? 'Estamos aquí para ayudarte. Contáctanos y descubre cómo nuestras soluciones pueden transformar tu negocio.') }}</textarea>
                        </div>

                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $contacto->phone ?? '+34 91 123 45 67') }}" class="shadow-sm focus:ring-[#3161DD] focus:border-[#3161DD] block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $contacto->email ?? 'info@agendux.com') }}" class="shadow-sm focus:ring-[#3161DD] focus:border-[#3161DD] block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                            <input type="text" name="contact_address" id="contact_address" value="{{ old('contact_address', $contacto->address ?? 'Calle Innovación 123, Bogotá, Colombia') }}" class="shadow-sm focus:ring-[#3161DD] focus:border-[#3161DD] block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="contact_hours" class="block text-sm font-medium text-gray-700 mb-1">Horario</label>
                            <input type="text" name="contact_hours" id="contact_hours" value="{{ old('contact_hours', $contacto->hours ?? 'Lunes a Viernes 9:00 - 18:00') }}" class="shadow-sm focus:ring-[#3161DD] focus:border-[#3161DD] block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <hr class="my-6">

                    <div class="text-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-700">Redes Sociales</h3>
                        <p class="text-sm text-gray-500">Enlaces a tus redes sociales (opcional)</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="contact_facebook" class="block text-sm font-medium text-gray-700 mb-1">Facebook URL</label>
                            <input type="url" name="contact_facebook" id="contact_facebook" value="{{ old('contact_facebook', $contacto->facebook ?? '#') }}" class="shadow-sm focus:ring-[#3161DD] focus:border-[#3161DD] block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="contact_instagram" class="block text-sm font-medium text-gray-700 mb-1">Instagram URL</label>
                            <input type="url" name="contact_instagram" id="contact_instagram" value="{{ old('contact_instagram', $contacto->instagram ?? '#') }}" class="shadow-sm focus:ring-[#3161DD] focus:border-[#3161DD] block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="contact_twitter" class="block text-sm font-medium text-gray-700 mb-1">Twitter URL</label>
                            <input type="url" name="contact_twitter" id="contact_twitter" value="{{ old('contact_twitter', $contacto->twitter ?? '#') }}" class="shadow-sm focus:ring-[#3161DD] focus:border-[#3161DD] block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="contact_whatsapp" class="block text-sm font-medium text-gray-700 mb-1">WhatsApp URL</label>
                            <input type="url" name="contact_whatsapp" id="contact_whatsapp" value="{{ old('contact_whatsapp', $contacto->whatsapp ?? '#') }}" class="shadow-sm focus:ring-[#3161DD] focus:border-[#3161DD] block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#3161DD] hover:bg-[#2050C0] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3161DD]">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('ul.flex li a');
        const paginasPanel = document.getElementById('paginas-panel');
        const blogPanel = document.getElementById('blog-panel');
        const politicaPanel = document.getElementById('politica-panel');
        const contactoPanel = document.getElementById('contacto-panel');

        // Sincronizar el selector de color con el campo de texto
        const colorPicker = document.getElementById('blog_background_color');
        const colorText = document.getElementById('blog_background_color_hex');

        if (colorPicker && colorText) {
            colorPicker.addEventListener('input', function() {
                colorText.value = this.value;
            });

            colorText.addEventListener('input', function() {
                colorPicker.value = this.value;
            });
        }

        // Verificar si hay un parámetro de URL 'tab'
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');

        // Activar la pestaña correcta según el parámetro
        if (tabParam && tabParam.toLowerCase() === 'blog') {
            activateTab('Blog');
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();

                activateTab(this.textContent.trim());
            });
        });

        function activateTab(tabName) {
            // Remover clase activa de todas las pestañas
            tabs.forEach(t => {
                t.classList.remove('text-[#3161DD]', 'border-[#3161DD]', 'font-semibold');
                t.classList.add('text-gray-500', 'border-transparent');
            });

            // Agregar clase activa a la pestaña seleccionada
            tabs.forEach(tab => {
                if (tab.textContent.trim() === tabName) {
                    tab.classList.remove('text-gray-500', 'border-transparent');
                    tab.classList.add('text-[#3161DD]', 'border-[#3161DD]', 'font-semibold');
                }
            });

            // Mostrar el panel correspondiente
            paginasPanel.classList.add('hidden');
            blogPanel.classList.add('hidden');
            politicaPanel.classList.add('hidden');
            contactoPanel.classList.add('hidden');

            if (tabName === 'Páginas') {
                paginasPanel.classList.remove('hidden');
            } else if (tabName === 'Blog') {
                blogPanel.classList.remove('hidden');
            } else if (tabName === 'Política de Privacidad') {
                politicaPanel.classList.remove('hidden');
            } else if (tabName === 'Contacto') {
                contactoPanel.classList.remove('hidden');
            }
        }
    });
</script>
<script>
    function policyInsert(tag, placeholder = '') {
        const editor = document.getElementById('policy-editor');
        const selection = window.getSelection();
        const range = selection.getRangeAt(0);
        const selectedText = selection.toString() || placeholder;

        const element = document.createElement(tag);
        element.textContent = selectedText;

        range.deleteContents();
        range.insertNode(element);

        const newRange = document.createRange();
        newRange.setStartAfter(element);
        selection.removeAllRanges();
        selection.addRange(newRange);

        editor.focus();
    }

    function syncPolicyContent() {
        document.getElementById('policy-editor-hidden').value = document.getElementById('policy-editor').innerHTML;
    }

    function policyMakeBold() {
        document.execCommand('bold');
    }

    function policyMakeItalic() {
        document.execCommand('italic');
    }

    function policyMakeHeading() {
        document.execCommand('formatBlock', false, 'h3');
    }

    function policyMakeList() {
        document.execCommand('insertUnorderedList');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const editor = document.getElementById('policy-editor');
        const hiddenInput = document.getElementById('policy-editor-hidden');

        document.querySelector('#politica-panel form').addEventListener('submit', () => {
            hiddenInput.value = editor.innerHTML;
        });
    });
</script>

@endsection