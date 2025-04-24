<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Blog;

class BlogController extends Controller
{
    /**
     * Muestra una lista de los artículos del blog.
     */
    public function index()
    {
        $articulos = Blog::orderBy('created_at', 'desc')->get();
        
        // Obtener las páginas para mostrarlas en la misma vista
        $paginas = \App\Models\Pagina::all();
        
        // Obtener la configuración del blog
        $blog_settings = \App\Models\BlogSettings::first();
        
        // Volver a la vista de administración de páginas con la pestaña de blog activa
        return view('admin.paginas.index', compact('articulos', 'paginas', 'blog_settings'));
    }

    /**
     * Muestra el formulario para crear un nuevo artículo.
     */
    public function create()
    {
        return view('admin.blog.create');
    }

    /**
     * Almacena un nuevo artículo en la base de datos.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blogs,slug',
            'resumen' => 'nullable|string|max:500',
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'categoria' => 'required|string',
            'etiquetas' => 'nullable|string',
            'autor' => 'required|string',
            'tiempo_lectura' => 'required|integer|min:1',
            'estado' => 'required|string|in:publicado,borrador',
            'fecha_publicacion' => 'required|date',
        ]);
        
        // Procesar la imagen si se ha subido
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreArchivo = time() . '_' . $imagen->getClientOriginalName();
            
            // Guardar directamente en public_html/images/blog
            $rutaDestino = base_path('../public_html/images/blog');
            
            // Crear el directorio si no existe
            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0755, true);
            }
            
            $imagen->move($rutaDestino, $nombreArchivo);
            $validatedData['imagen'] = '/images/blog/' . $nombreArchivo;
        }

        Blog::create([
            'titulo' => $validatedData['titulo'],
            'slug' => $validatedData['slug'],
            'resumen' => $validatedData['resumen'],
            'contenido' => $validatedData['contenido'],
            'imagen' => $validatedData['imagen'] ?? null,
            'categoria' => $validatedData['categoria'],
            'etiquetas' => $validatedData['etiquetas'],
            'autor' => $validatedData['autor'],
            'tiempo_lectura' => $validatedData['tiempo_lectura'],
            'estado' => $validatedData['estado'],
            'fecha_publicacion' => $validatedData['fecha_publicacion'],
            'user_id' => auth()->id(),
        ]);

        
        return redirect()->route('admin.blog.index')
            ->with('success', 'Artículo creado correctamente');
    }

    /**
     * Muestra el formulario para editar un artículo existente.
     */
    public function edit($id)
    {
        $articulo = Blog::findOrFail($id);
        return view('admin.blog.edit', compact('articulo'));
    }

    /**
     * Actualiza un artículo existente en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $articulo = Blog::findOrFail($id);
        
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blogs,slug,' . $id,'resumen' => 'nullable|string|max:500',
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'categoria' => 'required|string',
            'etiquetas' => 'nullable|string',
            'autor' => 'required|string',
            'tiempo_lectura' => 'required|integer|min:1',
            'estado' => 'required|string|in:publicado,borrador',
            'fecha_publicacion' => 'required|date',
        ]);
        
        // Procesar la imagen si se ha subido una nueva
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($articulo->imagen) {
                $rutaAnterior = base_path('../public_html' . $articulo->imagen);
                if (file_exists($rutaAnterior)) {
                    unlink($rutaAnterior);
                }
            }
            
            $imagen = $request->file('imagen');
            $nombreArchivo = time() . '_' . $imagen->getClientOriginalName();
            
            // Guardar directamente en public_html/images/blog
            $rutaDestino = base_path('../public_html/images/blog');
            
            // Crear el directorio si no existe
            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0755, true);
            }
            
            $imagen->move($rutaDestino, $nombreArchivo);
            $validatedData['imagen'] = '/images/blog/' . $nombreArchivo;
        }
    
        $articulo->update([
            'titulo' => $validatedData['titulo'],
            'slug' => $validatedData['slug'],
            'resumen' => $validatedData['resumen'],
            'contenido' => $validatedData['contenido'],
            'imagen' => $validatedData['imagen'] ?? $articulo->imagen,
            'categoria' => $validatedData['categoria'],
            'etiquetas' => $validatedData['etiquetas'],
            'autor' => $validatedData['autor'],
            'tiempo_lectura' => $validatedData['tiempo_lectura'],
            'estado' => $validatedData['estado'],
            'fecha_publicacion' => $validatedData['fecha_publicacion'],
        ]);

        
        return redirect()->route('admin.blog.index')
            ->with('success', 'Artículo actualizado correctamente');
    }

    /**
     * Elimina un artículo del blog.
     */
    public function destroy($id)
    {
        $articulo = Blog::findOrFail($id);
        
        // Eliminar la imagen si existe
        if ($articulo->imagen) {
            $rutaImagen = base_path('../public_html' . $articulo->imagen);
            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }
        }
        
        $articulo->delete();

        
        return redirect()->route('admin.blog.index')
            ->with('success', 'Artículo eliminado correctamente');
    }

    /**
     * Actualiza la configuración del blog.
     */
    public function updateSettings(Request $request)
    {
        $settings = \App\Models\BlogSettings::first();
        
        if (!$settings) {
            $settings = new \App\Models\BlogSettings();
        }
        
        $settings->title = $request->blog_title;
        $settings->description = $request->blog_description;
        $settings->background_color = $request->blog_background_color;
        $settings->save();
        
        return redirect()->route('admin.blog.index', ['tab' => 'blog'])
            ->with('success', 'Configuración del blog actualizada correctamente');
    }

    /**
     * MÉTODOS PARA EL FRONTEND
     */

    /**
     * Muestra la lista de artículos en el blog público
     */
    public function showBlog()
    {
        // Obtener artículos publicados y ordenados por fecha de publicación (más recientes primero)
        $articulos = Blog::where('estado', 'publicado')
                        ->where('fecha_publicacion', '<=', now())
                        ->orderBy('fecha_publicacion', 'desc')
                        ->get();
        
        // Obtener todas las páginas
        $paginas = \App\Models\Pagina::all();
        
        return view('blog', compact('articulos', 'paginas'));
    }
    
    /**
     * Muestra un artículo específico
     */
    public function showArticulo($slug)
    {
        $articulo = Blog::where('slug', $slug)
                      ->where('estado', 'publicado')
                      ->firstOrFail();
        
        return view('showblog', compact('articulo'));
    }
    
    /**
     * Sube imágenes para el contenido del artículo
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        
        try {
            if ($request->hasFile('image')) {
                $imagen = $request->file('image');
                $nombreArchivo = time() . '_' . $imagen->getClientOriginalName();
                
                // Guardar directamente en public_html/images/blog/content
                $rutaDestino = base_path('../public_html/images/blog/content');
                
                // Crear el directorio si no existe
                if (!file_exists($rutaDestino)) {
                    mkdir($rutaDestino, 0755, true);
                }
                
                $imagen->move($rutaDestino, $nombreArchivo);
                
                // Devolver la URL de la imagen
                return response()->json([
                    'success' => true,
                    'url' => '/images/blog/content/' . $nombreArchivo
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No se pudo procesar la imagen.'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir la imagen: ' . $e->getMessage()
            ], 500);
        }
    }
}