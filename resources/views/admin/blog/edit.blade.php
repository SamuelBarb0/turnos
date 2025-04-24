@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ isset($articulo) ? 'Editar' : 'Crear' }} Artículo de Blog</h1>
        <a href="{{ route('admin.paginas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm flex items-center transition duration-300">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow">
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden p-6">
        <form action="{{ isset($articulo) ? route('admin.blog.update', $articulo) : route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($articulo))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Columna principal - 2/3 del ancho -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Título -->
                    <div>
                        <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título del artículo</label>
                        <input type="text" name="titulo" id="titulo" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3161DD] focus:ring-[#3161DD]" value="{{ old('titulo', $articulo->titulo ?? '') }}" required>
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug (URL)</label>
                        <div class="flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                /blog/
                            </span>
                            <input type="text" name="slug" id="slug" class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 focus:border-[#3161DD] focus:ring-[#3161DD] sm:text-sm" value="{{ old('slug', $articulo->slug ?? '') }}" required>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">El slug se generará automáticamente a partir del título, pero puedes personalizarlo.</p>
                    </div>

                    <!-- Resumen -->
                    <div>
                        <label for="resumen" class="block text-sm font-medium text-gray-700 mb-1">Resumen</label>
                        <textarea name="resumen" id="resumen" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3161DD] focus:ring-[#3161DD]">{{ old('resumen', $articulo->resumen ?? '') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Breve descripción que aparecerá en las tarjetas del blog (máximo 160 caracteres).</p>
                    </div>

                    <!-- Herramientas de edición sencillas -->
                    <div class="bg-gray-50 p-3 rounded-t-md border border-gray-300 border-b-0 flex flex-wrap gap-2">
                        <button type="button" class="text-sm bg-white px-3 py-1 rounded border border-gray-300 hover:bg-gray-100" onclick="insertFormat('**', '**', 'negrita')">
                            <i class="fas fa-bold"></i>
                        </button>
                        <button type="button" class="text-sm bg-white px-3 py-1 rounded border border-gray-300 hover:bg-gray-100" onclick="insertFormat('*', '*', 'cursiva')">
                            <i class="fas fa-italic"></i>
                        </button>
                        <button type="button" class="text-sm bg-white px-3 py-1 rounded border border-gray-300 hover:bg-gray-100" onclick="insertFormat('# ', '', 'encabezado')">
                            <i class="fas fa-heading"></i>
                        </button>
                        <button type="button" class="text-sm bg-white px-3 py-1 rounded border border-gray-300 hover:bg-gray-100" onclick="insertFormat('- ', '', 'lista')">
                            <i class="fas fa-list-ul"></i>
                        </button>
                        <button type="button" class="text-sm bg-white px-3 py-1 rounded border border-gray-300 hover:bg-gray-100" onclick="showImageUploader()">
                            <i class="fas fa-image"></i> Imagen
                        </button>
                    </div>

                    <!-- Contenido -->
                    <div class="relative">
                        <label for="contenido" class="block text-sm font-medium text-gray-700 mb-1">Contenido</label>
                        <textarea name="contenido" id="editor" rows="20" class="block w-full rounded-t-none rounded-b-md border-gray-300 shadow-sm focus:border-[#3161DD] focus:ring-[#3161DD]">{{ old('contenido', $articulo->contenido ?? '') }}</textarea>
                        
                        <!-- Panel flotante para subir imágenes (oculto por defecto) -->
                        <div id="image-uploader" class="hidden absolute z-10 bg-white border border-gray-300 rounded-md shadow-lg p-4 w-[90%] max-w-md">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="font-medium">Insertar imagen</h3>
                                <button type="button" onclick="closeImageUploader()" class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm text-gray-700 mb-1">Seleccionar imagen</label>
                                    <input type="file" id="content-image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>
                                
                                <div class="preview-container hidden">
                                    <p class="text-sm text-gray-700 mb-1">Vista previa:</p>
                                    <img id="image-preview" src="#" alt="Vista previa" class="max-h-[150px] max-w-full object-contain border rounded">
                                </div>
                                
                                <div>
                                    <label for="image-alt" class="block text-sm text-gray-700 mb-1">Texto alternativo</label>
                                    <input type="text" id="image-alt" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3161DD] focus:ring-[#3161DD] text-sm" placeholder="Describe la imagen para accesibilidad">
                                </div>
                                
                                <div class="flex justify-end space-x-2">
                                    <button type="button" onclick="closeImageUploader()" class="px-3 py-1.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                        Cancelar
                                    </button>
                                    <button type="button" onclick="uploadAndInsertImage()" id="upload-image-btn" class="px-3 py-1.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#3161DD] hover:bg-[#2050C0] disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                        Insertar imagen
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barra lateral - 1/3 del ancho -->
                <div class="space-y-6">
                    <!-- Imagen destacada -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="font-medium text-gray-900 mb-3">Imagen destacada</h3>
                        
                        <div class="mb-3">
                            @if(isset($articulo) && $articulo->imagen)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $articulo->imagen) }}" alt="Imagen destacada" class="rounded-lg w-full h-auto">
                            </div>
                            @endif
                            
                            <label class="block">
                                <span class="sr-only">Seleccionar imagen</span>
                                <input type="file" name="imagen" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </label>
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG o WEBP (Recomendado: 1200×630px)</p>
                        </div>
                    </div>

                    <!-- Categoría y Etiquetas -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="font-medium text-gray-900 mb-3">Categoría y etiquetas</h3>
                        
                        <!-- Categoría -->
                        <div class="mb-4">
                            <label for="categoria" class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                            <select name="categoria" id="categoria" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3161DD] focus:ring-[#3161DD]">
                                <option value="guia" {{ (old('categoria', $articulo->categoria ?? '') == 'guia') ? 'selected' : '' }}>Guía</option>
                                <option value="tutorial" {{ (old('categoria', $articulo->categoria ?? '') == 'tutorial') ? 'selected' : '' }}>Tutorial</option>
                                <option value="noticia" {{ (old('categoria', $articulo->categoria ?? '') == 'noticia') ? 'selected' : '' }}>Noticia</option>
                                <option value="consejo" {{ (old('categoria', $articulo->categoria ?? '') == 'consejo') ? 'selected' : '' }}>Consejo</option>
                            </select>
                        </div>
                        
                        <!-- Etiquetas -->
                        <div>
                            <label for="etiquetas" class="block text-sm font-medium text-gray-700 mb-1">Etiquetas</label>
                            <input type="text" name="etiquetas" id="etiquetas" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3161DD] focus:ring-[#3161DD]" value="{{ old('etiquetas', $articulo->etiquetas ?? '') }}">
                            <p class="mt-1 text-xs text-gray-500">Separa las etiquetas con comas (ej: whatsapp, agendux, productividad)</p>
                        </div>
                    </div>

                    <!-- Autor y Tiempos -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="font-medium text-gray-900 mb-3">Autor y tiempos</h3>
                        
                        <!-- Autor -->
                        <div class="mb-4">
                            <label for="autor" class="block text-sm font-medium text-gray-700 mb-1">Autor</label>
                            <input type="text" name="autor" id="autor" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3161DD] focus:ring-[#3161DD]" value="{{ old('autor', $articulo->autor ?? auth()->user()->name) }}">
                        </div>
                        
                        <!-- Tiempo de lectura -->
                        <div>
                            <label for="tiempo_lectura" class="block text-sm font-medium text-gray-700 mb-1">Tiempo de lectura (minutos)</label>
                            <input type="number" name="tiempo_lectura" id="tiempo_lectura" min="1" max="60" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3161DD] focus:ring-[#3161DD]" value="{{ old('tiempo_lectura', $articulo->tiempo_lectura ?? '3') }}">
                        </div>
                    </div>

                    <!-- Estado de publicación -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="font-medium text-gray-900 mb-3">Publicación</h3>
                        
                        <!-- Estado -->
                        <div class="mb-4">
                            <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                            <select name="estado" id="estado" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3161DD] focus:ring-[#3161DD]">
                                <option value="publicado" {{ (old('estado', $articulo->estado ?? '') == 'publicado') ? 'selected' : '' }}>Publicado</option>
                                <option value="borrador" {{ (old('estado', $articulo->estado ?? '') == 'borrador') ? 'selected' : '' }}>Borrador</option>
                            </select>
                        </div>
                        
                        <!-- Fecha de publicación -->
                        <div>
                            <label for="fecha_publicacion" class="block text-sm font-medium text-gray-700 mb-1">Fecha de publicación</label>
                            <input type="date" name="fecha_publicacion" id="fecha_publicacion" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3161DD] focus:ring-[#3161DD]" value="{{ old('fecha_publicacion', isset($articulo->fecha_publicacion) ? $articulo->fecha_publicacion->format('Y-m-d') : now()->format('Y-m-d')) }}">
                        </div>
                    </div>
                    
                    <!-- Botones de acción -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="window.history.back()" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3161DD]">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#3161DD] hover:bg-[#2050C0] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3161DD]">
                            {{ isset($articulo) ? 'Actualizar' : 'Publicar' }} artículo
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Token CSRF para solicitudes AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    // Para generar el slug automáticamente a partir del título
    document.addEventListener('DOMContentLoaded', function() {
        const tituloInput = document.getElementById('titulo');
        const slugInput = document.getElementById('slug');
        
        if (tituloInput && slugInput) {
            tituloInput.addEventListener('blur', function() {
                if (slugInput.value === '' && tituloInput.value !== '') {
                    // Solo generar el slug si está vacío
                    slugInput.value = tituloInput.value
                        .toLowerCase()
                        .replace(/[^\w\s-]/g, '')    // Eliminar caracteres especiales
                        .replace(/[\s_-]+/g, '-')    // Reemplazar espacios y guiones bajos por guiones
                        .replace(/^-+|-+$/g, '');    // Eliminar guiones al inicio y final
                }
            });
        }
    });

    // Funciones para formatear texto en el editor
    function insertFormat(startTag, endTag, placeholder) {
        const editor = document.getElementById('editor');
        const start = editor.selectionStart;
        const end = editor.selectionEnd;
        const selectedText = editor.value.substring(start, end);
        const replacement = selectedText.length > 0 ? selectedText : placeholder;
        
        // Insertar las etiquetas alrededor del texto seleccionado o del placeholder
        const newText = editor.value.substring(0, start) + 
                       startTag + replacement + endTag + 
                       editor.value.substring(end);
        
        editor.value = newText;
        
        // Reposicionar el cursor después de la inserción
        const newCursorPos = selectedText.length > 0 
                           ? start + startTag.length + selectedText.length + endTag.length 
                           : start + startTag.length + placeholder.length;
        
        editor.focus();
        editor.setSelectionRange(newCursorPos, newCursorPos);
    }

    // Funciones para el cargador de imágenes
    function showImageUploader() {
        document.getElementById('image-uploader').classList.remove('hidden');
    }

    function closeImageUploader() {
        document.getElementById('image-uploader').classList.add('hidden');
        document.getElementById('content-image').value = '';
        document.getElementById('image-alt').value = '';
        document.querySelector('.preview-container').classList.add('hidden');
        document.getElementById('upload-image-btn').disabled = true;
    }

    // Previsualizar imagen seleccionada
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('content-image');
        const imagePreview = document.getElementById('image-preview');
        const previewContainer = document.querySelector('.preview-container');
        const uploadButton = document.getElementById('upload-image-btn');
        
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    uploadButton.disabled = false;
                }
                
                reader.readAsDataURL(this.files[0]);
            } else {
                previewContainer.classList.add('hidden');
                uploadButton.disabled = true;
            }
        });
    });

    // Subir imagen e insertarla en el editor
    function uploadAndInsertImage() {
        const imageFile = document.getElementById('content-image').files[0];
        const imageAlt = document.getElementById('image-alt').value || 'Imagen';
        const uploadButton = document.getElementById('upload-image-btn');
        
        if (!imageFile) return;
        
        // Deshabilitar el botón durante la carga
        uploadButton.disabled = true;
        uploadButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Subiendo...';
        
        const formData = new FormData();
        formData.append('image', imageFile);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        fetch('{{ route("admin.blog.upload-image") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Insertar la imagen en el editor
                const editor = document.getElementById('editor');
                const imageMarkdown = `\n\n![${imageAlt}](${data.url})\n\n`;
                
                const cursorPos = editor.selectionStart;
                editor.value = editor.value.substring(0, cursorPos) + imageMarkdown + editor.value.substring(cursorPos);
                
                // Cerrar el panel de carga
                closeImageUploader();
            } else {
                alert('Error al subir la imagen: ' + data.message);
                uploadButton.disabled = false;
                uploadButton.innerHTML = 'Insertar imagen';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al subir la imagen. Por favor, inténtalo de nuevo.');
            uploadButton.disabled = false;
            uploadButton.innerHTML = 'Insertar imagen';
        });
    }
</script>
@endsection