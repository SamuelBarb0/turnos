@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6 z-0 relative">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Editar Metadatos SEO</h1>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow" role="alert">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <form action="{{ route('admin.seo.update', $seoMetadata->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Sección de información básica -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Información Básica</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="page_slug" class="block text-sm font-medium text-gray-700 mb-1">Slug de página <span class="text-red-500">*</span></label>
                            <input type="text" id="page_slug" name="page_slug" value="{{ old('page_slug', $seoMetadata->page_slug) }}" 
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('page_slug') border-red-500 @else border-gray-300 @enderror"
                                required>
                            <p class="mt-1 text-xs text-gray-500">URL relativa de la página, ej: /nosotros, /productos/categoria</p>
                            @error('page_slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="language_code" class="block text-sm font-medium text-gray-700 mb-1">Idioma</label>
                            <select id="language_code" name="language_code"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('language_code') border-red-500 @else border-gray-300 @enderror">
                                <option value="es" @if(old('language_code', $seoMetadata->language_code) == 'es') selected @endif>Español (es)</option>
                                <option value="en" @if(old('language_code', $seoMetadata->language_code) == 'en') selected @endif>Inglés (en)</option>
                                <option value="pt" @if(old('language_code', $seoMetadata->language_code) == 'pt') selected @endif>Portugués (pt)</option>
                            </select>
                            @error('language_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Sección de meta tags -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Meta Tags</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Título de la página</label>
                            <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $seoMetadata->meta_title) }}" 
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('meta_title') border-red-500 @else border-gray-300 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Recomendado: 50-60 caracteres</p>
                            @error('meta_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="canonical_url" class="block text-sm font-medium text-gray-700 mb-1">URL Canónica</label>
                            <input type="url" id="canonical_url" name="canonical_url" value="{{ old('canonical_url', $seoMetadata->canonical_url) }}" 
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('canonical_url') border-red-500 @else border-gray-300 @enderror">
                            <p class="mt-1 text-xs text-gray-500">URL completa, ej: https://example.com/pagina</p>
                            @error('canonical_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta descripción</label>
                            <textarea id="meta_description" name="meta_description" rows="3"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('meta_description') border-red-500 @else border-gray-300 @enderror">{{ old('meta_description', $seoMetadata->meta_description) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Recomendado: 150-160 caracteres</p>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">Meta palabras clave</label>
                            <input type="text" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $seoMetadata->meta_keywords) }}" 
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('meta_keywords') border-red-500 @else border-gray-300 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Separadas por comas</p>
                            @error('meta_keywords')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="meta_robots" class="block text-sm font-medium text-gray-700 mb-1">Meta robots</label>
                            <input type="text" id="meta_robots" name="meta_robots" value="{{ old('meta_robots', $seoMetadata->meta_robots) }}" 
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('meta_robots') border-red-500 @else border-gray-300 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Ej: index, follow, noindex, nofollow</p>
                            @error('meta_robots')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Sección de Open Graph -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Open Graph (Facebook)</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="og_title" class="block text-sm font-medium text-gray-700 mb-1">OG Título</label>
                            <input type="text" id="og_title" name="og_title" value="{{ old('og_title', $seoMetadata->og_title) }}" 
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('og_title') border-red-500 @else border-gray-300 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Si se deja vacío, se usará el meta título</p>
                            @error('og_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="og_type" class="block text-sm font-medium text-gray-700 mb-1">OG Tipo</label>
                            <select id="og_type" name="og_type"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('og_type') border-red-500 @else border-gray-300 @enderror">
                                <option value="website" @if(old('og_type', $seoMetadata->og_type) == 'website') selected @endif>website</option>
                                <option value="article" @if(old('og_type', $seoMetadata->og_type) == 'article') selected @endif>article</option>
                                <option value="product" @if(old('og_type', $seoMetadata->og_type) == 'product') selected @endif>product</option>
                            </select>
                            @error('og_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="og_description" class="block text-sm font-medium text-gray-700 mb-1">OG Descripción</label>
                            <textarea id="og_description" name="og_description" rows="3"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('og_description') border-red-500 @else border-gray-300 @enderror">{{ old('og_description', $seoMetadata->og_description) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Si se deja vacío, se usará la meta descripción</p>
                            @error('og_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="og_url" class="block text-sm font-medium text-gray-700 mb-1">OG URL</label>
                            <input type="url" id="og_url" name="og_url" value="{{ old('og_url', $seoMetadata->og_url) }}" 
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('og_url') border-red-500 @else border-gray-300 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Si se deja vacío, se usará la URL canónica</p>
                            @error('og_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="og_site_name" class="block text-sm font-medium text-gray-700 mb-1">OG Nombre del sitio</label>
                            <input type="text" id="og_site_name" name="og_site_name" value="{{ old('og_site_name', $seoMetadata->og_site_name) }}" 
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('og_site_name') border-red-500 @else border-gray-300 @enderror">
                            @error('og_site_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="og_locale" class="block text-sm font-medium text-gray-700 mb-1">OG Localización</label>
                            <input type="text" id="og_locale" name="og_locale" value="{{ old('og_locale', $seoMetadata->og_locale) }}" 
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('og_locale') border-red-500 @else border-gray-300 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Formato: idioma_PAÍS (ej: es_CO, en_US)</p>
                            @error('og_locale')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="og_image" class="block text-sm font-medium text-gray-700 mb-1">OG Imagen</label>
                            <input type="file" id="og_image" name="og_image" 
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('og_image') border-red-500 @else border-gray-300 @enderror">
                            @if($seoMetadata->og_image)
                                <div class="mt-2">
                                    <img src="{{ asset($seoMetadata->og_image) }}" alt="OG Image" class="max-h-32 rounded-md shadow-sm">
                                    <p class="mt-1 text-xs text-gray-500">Imagen actual: {{ basename($seoMetadata->og_image) }}</p>
                                </div>
                            @endif
                            <p class="mt-1 text-xs text-gray-500">Tamaño recomendado: 1200x630 píxeles. Deja en blanco para mantener la imagen actual.</p>
                            @error('og_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Sección de Twitter Cards -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Twitter Cards</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="twitter_card" class="block text-sm font-medium text-gray-700 mb-1">Twitter Card</label>
                            <select id="twitter_card" name="twitter_card"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('twitter_card') border-red-500 @else border-gray-300 @enderror">
                                <option value="summary_large_image" @if(old('twitter_card', $seoMetadata->twitter_card) == 'summary_large_image') selected @endif>summary_large_image</option>
                                <option value="summary" @if(old('twitter_card', $seoMetadata->twitter_card) == 'summary') selected @endif>summary</option>
                                <option value="app" @if(old('twitter_card', $seoMetadata->twitter_card) == 'app') selected @endif>app</option>
                            </select>
                            @error('twitter_card')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="twitter_title" class="block text-sm font-medium text-gray-700 mb-1">Twitter Título</label>
                            <input type="text" id="twitter_title" name="twitter_title" value="{{ old('twitter_title', $seoMetadata->twitter_title) }}" 
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('twitter_title') border-red-500 @else border-gray-300 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Si se deja vacío, se usará el meta título</p>
                            @error('twitter_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="twitter_description" class="block text-sm font-medium text-gray-700 mb-1">Twitter Descripción</label>
                            <textarea id="twitter_description" name="twitter_description" rows="3"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('twitter_description') border-red-500 @else border-gray-300 @enderror">{{ old('twitter_description', $seoMetadata->twitter_description) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Si se deja vacío, se usará la meta descripción</p>
                            @error('twitter_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="twitter_site" class="block text-sm font-medium text-gray-700 mb-1">Twitter @usuario del sitio</label>
                            <input type="text" id="twitter_site" name="twitter_site" value="{{ old('twitter_site', $seoMetadata->twitter_site) }}" 
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('twitter_site') border-red-500 @else border-gray-300 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Incluye el @ (ej: @tuempresa)</p>
                            @error('twitter_site')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="twitter_creator" class="block text-sm font-medium text-gray-700 mb-1">Twitter @usuario del autor</label>
                            <input type="text" id="twitter_creator" name="twitter_creator" value="{{ old('twitter_creator', $seoMetadata->twitter_creator) }}" 
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('twitter_creator') border-red-500 @else border-gray-300 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Incluye el @ (ej: @tuautor)</p>
                            @error('twitter_creator')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="twitter_image" class="block text-sm font-medium text-gray-700 mb-1">Twitter Imagen</label>
                            <input type="file" id="twitter_image" name="twitter_image" 
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('twitter_image') border-red-500 @else border-gray-300 @enderror">
                            @if($seoMetadata->twitter_image)
                                <div class="mt-2">
                                    <img src="{{ asset($seoMetadata->twitter_image) }}" alt="Twitter Image" class="max-h-32 rounded-md shadow-sm">
                                    <p class="mt-1 text-xs text-gray-500">Imagen actual: {{ basename($seoMetadata->twitter_image) }}</p>
                                </div>
                            @endif
                            <p class="mt-1 text-xs text-gray-500">Tamaño recomendado: 1200x600 píxeles. Deja en blanco para mantener la imagen actual.</p>
                            @error('twitter_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="twitter_image_alt" class="block text-sm font-medium text-gray-700 mb-1">Twitter Texto alternativo de imagen</label>
                            <input type="text" id="twitter_image_alt" name="twitter_image_alt" value="{{ old('twitter_image_alt', $seoMetadata->twitter_image_alt) }}" 
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('twitter_image_alt') border-red-500 @else border-gray-300 @enderror">
                            @error('twitter_image_alt')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Botones de acción -->
                <div class="flex items-center justify-start space-x-3">
                    <a href="{{ route('admin.seo.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition duration-300">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-300 flex items-center">
                        <i class="fas fa-save mr-2"></i> Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Script para contar caracteres en campos importantes
    document.addEventListener('DOMContentLoaded', function() {
        const charCountFields = [
            { input: 'meta_title', limit: 60 },
            { input: 'meta_description', limit: 160 },
            { input: 'og_title', limit: 90 },
            { input: 'og_description', limit: 200 },
            { input: 'twitter_title', limit: 70 },
            { input: 'twitter_description', limit: 200 }
        ];

        charCountFields.forEach(field => {
            const element = document.getElementById(field.input);
            if (element) {
                const small = element.nextElementSibling;
                if (small && small.tagName === 'P') {
                    const originalText = small.textContent;
                    
                    const updateCount = () => {
                        const count = element.value.length;
                        small.textContent = `${originalText} (${count}/${field.limit})`;
                        
                        if (count > field.limit) {
                            small.classList.add('text-red-500');
                            small.classList.remove('text-gray-500');
                        } else {
                            small.classList.remove('text-red-500');
                            small.classList.add('text-gray-500');
                        }
                    };

                    element.addEventListener('input', updateCount);
                    updateCount(); // Inicializar
                }
            }
        });
    });
</script>
@endsection