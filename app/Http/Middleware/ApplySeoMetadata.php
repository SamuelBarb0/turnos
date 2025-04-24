<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SeoMetadata;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class ApplySeoMetadata
{
    public function handle(Request $request, Closure $next)
    {
        // Obtener el path actual
        $currentPath = $request->path();
        
        // Manejo especial para la página de inicio
        if ($currentPath === '/' || $currentPath === '') {
            $seoMetadata = SeoMetadata::where('page_slug', 'home')->first();
            
            // Log para debugging
            Log::info('SEO Middleware - Home page detected, looking for "home" slug');
            if ($seoMetadata) {
                Log::info('SEO found for homepage: ' . $seoMetadata->meta_title);
            } else {
                Log::info('No SEO found for homepage');
            }
        } else {
            // Para otras páginas, normalizar el path
            $currentPath = '/' . ltrim($currentPath, '/');
            
            // Log para debugging
            Log::info('SEO Middleware - Current Path: ' . $currentPath);
            
            // Buscar metadatos SEO para esta ruta
            $seoMetadata = SeoMetadata::where('page_slug', $currentPath)->first();
            
            if ($seoMetadata) {
                Log::info('SEO found for path: ' . $currentPath);
            } else {
                Log::info('No SEO found for path: ' . $currentPath);
                
                // Intentar buscar por slug sin la barra inicial
                $pathWithoutSlash = ltrim($currentPath, '/');
                $seoMetadata = SeoMetadata::where('page_slug', $pathWithoutSlash)->first();
                
                if ($seoMetadata) {
                    Log::info('SEO found for path without slash: ' . $pathWithoutSlash);
                } else {
                    // Intentar con comodines
                    $segments = explode('/', $currentPath);
                    array_pop($segments);
                    
                    if (count($segments) > 0) {
                        $wildcardPath = implode('/', $segments) . '/*';
                        Log::info('Trying wildcard: ' . $wildcardPath);
                        
                        $seoMetadata = SeoMetadata::where('page_slug', $wildcardPath)->first();
                        
                        if ($seoMetadata) {
                            Log::info('SEO found with wildcard: ' . $wildcardPath);
                        }
                    }
                }
            }
        }
        
        // Compartir metadatos con todas las vistas
        View::share('seo', $seoMetadata ?? null);
        
        return $next($request);
    }
}