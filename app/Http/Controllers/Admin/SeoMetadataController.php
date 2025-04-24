<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoMetadata;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SeoMetadataController extends Controller
{
    /**
     * Mostrar lista de metadatos SEO
     */
    public function index()
    {
        $seoMetadatas = SeoMetadata::all();
        return view('admin.seo.index', compact('seoMetadatas'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('admin.seo.create');
    }

    /**
     * Almacenar nuevo metadato SEO
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'page_slug' => 'required|unique:seo_metadata,page_slug|max:255',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable|max:500',
            'meta_keywords' => 'nullable',
            'meta_robots' => 'nullable|max:100',
            'canonical_url' => 'nullable|url|max:255',
            'og_title' => 'nullable|max:255',
            'og_description' => 'nullable|max:500',
            'og_type' => 'nullable|max:50',
            'og_url' => 'nullable|url|max:255',
            'og_site_name' => 'nullable|max:255',
            'og_locale' => 'nullable|max:20',
            'twitter_card' => 'nullable|max:50',
            'twitter_title' => 'nullable|max:255',
            'twitter_description' => 'nullable|max:500',
            'twitter_image_alt' => 'nullable|max:255',
            'twitter_site' => 'nullable|max:255',
            'twitter_creator' => 'nullable|max:255',
            'language_code' => 'nullable|max:10',
        ]);

        // Procesar imagen OG si se ha subido
        if ($request->hasFile('og_image')) {
            $imagen = $request->file('og_image');
            $nombreArchivo = time() . '_' . $imagen->getClientOriginalName();
            
            // Guardar directamente en public_html/images/seo
            $rutaDestino = base_path('../public_html/images/seo');
            
            // Crear el directorio si no existe
            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0755, true);
            }
            
            $imagen->move($rutaDestino, $nombreArchivo);
            $validatedData['og_image'] = '/images/seo/' . $nombreArchivo;
        }

        // Procesar imagen de Twitter si se ha subido
        if ($request->hasFile('twitter_image')) {
            $imagen = $request->file('twitter_image');
            $nombreArchivo = time() . '_' . $imagen->getClientOriginalName();
            
            // Guardar directamente en public_html/images/seo
            $rutaDestino = base_path('../public_html/images/seo');
            
            // Crear el directorio si no existe
            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0755, true);
            }
            
            $imagen->move($rutaDestino, $nombreArchivo);
            $validatedData['twitter_image'] = '/images/seo/' . $nombreArchivo;
        }

        // Valores por defecto
        $validatedData['meta_robots'] = $validatedData['meta_robots'] ?? 'index, follow';
        $validatedData['og_type'] = $validatedData['og_type'] ?? 'website';
        $validatedData['og_locale'] = $validatedData['og_locale'] ?? 'es_CO';
        $validatedData['twitter_card'] = $validatedData['twitter_card'] ?? 'summary_large_image';
        $validatedData['language_code'] = $validatedData['language_code'] ?? 'es';
        
        // Usar el mismo título para OG y Twitter si no se especifica uno diferente
        $validatedData['og_title'] = $validatedData['og_title'] ?? $validatedData['meta_title'];
        $validatedData['og_description'] = $validatedData['og_description'] ?? $validatedData['meta_description'];
        $validatedData['twitter_title'] = $validatedData['twitter_title'] ?? $validatedData['meta_title'];
        $validatedData['twitter_description'] = $validatedData['twitter_description'] ?? $validatedData['meta_description'];
        
        // URL canónica para OG si no se especifica
        $validatedData['og_url'] = $validatedData['og_url'] ?? $validatedData['canonical_url'];

        SeoMetadata::create($validatedData);

        return redirect()->route('admin.seo.index')
            ->with('success', 'Metadatos SEO creados correctamente');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $seoMetadata = SeoMetadata::findOrFail($id);
        return view('admin.seo.edit', compact('seoMetadata'));
    }

    /**
     * Actualizar metadato SEO
     */
    public function update(Request $request, $id)
    {
        $seoMetadata = SeoMetadata::findOrFail($id);

        $validatedData = $request->validate([
            'page_slug' => 'required|max:255|unique:seo_metadata,page_slug,' . $id,
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable|max:500',
            'meta_keywords' => 'nullable',
            'meta_robots' => 'nullable|max:100',
            'canonical_url' => 'nullable|url|max:255',
            'og_title' => 'nullable|max:255',
            'og_description' => 'nullable|max:500',
            'og_type' => 'nullable|max:50',
            'og_url' => 'nullable|url|max:255',
            'og_site_name' => 'nullable|max:255',
            'og_locale' => 'nullable|max:20',
            'twitter_card' => 'nullable|max:50',
            'twitter_title' => 'nullable|max:255',
            'twitter_description' => 'nullable|max:500',
            'twitter_image_alt' => 'nullable|max:255',
            'twitter_site' => 'nullable|max:255',
            'twitter_creator' => 'nullable|max:255',
            'language_code' => 'nullable|max:10',
        ]);

        // Procesar imagen OG si se ha subido
        if ($request->hasFile('og_image')) {
            // Eliminar imagen anterior si existe
            if ($seoMetadata->og_image) {
                $rutaAnterior = base_path('../public_html' . $seoMetadata->og_image);
                if (file_exists($rutaAnterior)) {
                    unlink($rutaAnterior);
                }
            }

            $imagen = $request->file('og_image');
            $nombreArchivo = time() . '_' . $imagen->getClientOriginalName();
            
            // Guardar directamente en public_html/images/seo
            $rutaDestino = base_path('../public_html/images/seo');
            
            // Crear el directorio si no existe
            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0755, true);
            }
            
            $imagen->move($rutaDestino, $nombreArchivo);
            $validatedData['og_image'] = '/images/seo/' . $nombreArchivo;
        }

        // Procesar imagen de Twitter si se ha subido
        if ($request->hasFile('twitter_image')) {
            // Eliminar imagen anterior si existe
            if ($seoMetadata->twitter_image) {
                $rutaAnterior = base_path('../public_html' . $seoMetadata->twitter_image);
                if (file_exists($rutaAnterior)) {
                    unlink($rutaAnterior);
                }
            }

            $imagen = $request->file('twitter_image');
            $nombreArchivo = time() . '_' . $imagen->getClientOriginalName();
            
            // Guardar directamente en public_html/images/seo
            $rutaDestino = base_path('../public_html/images/seo');
            
            // Crear el directorio si no existe
            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0755, true);
            }
            
            $imagen->move($rutaDestino, $nombreArchivo);
            $validatedData['twitter_image'] = '/images/seo/' . $nombreArchivo;
        }

        // Valores por defecto
        $validatedData['meta_robots'] = $validatedData['meta_robots'] ?? 'index, follow';
        $validatedData['og_type'] = $validatedData['og_type'] ?? 'website';
        $validatedData['og_locale'] = $validatedData['og_locale'] ?? 'es_CO';
        $validatedData['twitter_card'] = $validatedData['twitter_card'] ?? 'summary_large_image';
        $validatedData['language_code'] = $validatedData['language_code'] ?? 'es';
        
        // Usar el mismo título para OG y Twitter si no se especifica uno diferente
        $validatedData['og_title'] = $validatedData['og_title'] ?? $validatedData['meta_title'];
        $validatedData['og_description'] = $validatedData['og_description'] ?? $validatedData['meta_description'];
        $validatedData['twitter_title'] = $validatedData['twitter_title'] ?? $validatedData['meta_title'];
        $validatedData['twitter_description'] = $validatedData['twitter_description'] ?? $validatedData['meta_description'];
        
        // URL canónica para OG si no se especifica
        $validatedData['og_url'] = $validatedData['og_url'] ?? $validatedData['canonical_url'];

        $seoMetadata->update($validatedData);

        return redirect()->route('admin.seo.index')
            ->with('success', 'Metadatos SEO actualizados correctamente');
    }

    /**
     * Eliminar metadato SEO
     */
    public function destroy($id)
    {
        $seoMetadata = SeoMetadata::findOrFail($id);

        // Eliminar imágenes si existen
        if ($seoMetadata->og_image) {
            $rutaOgImagen = base_path('../public_html' . $seoMetadata->og_image);
            if (file_exists($rutaOgImagen)) {
                unlink($rutaOgImagen);
            }
        }

        if ($seoMetadata->twitter_image) {
            $rutaTwitterImagen = base_path('../public_html' . $seoMetadata->twitter_image);
            if (file_exists($rutaTwitterImagen)) {
                unlink($rutaTwitterImagen);
            }
        }

        $seoMetadata->delete();

        return redirect()->route('admin.seo.index')
            ->with('success', 'Metadatos SEO eliminados correctamente');
    }
}