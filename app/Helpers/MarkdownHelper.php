<?php

namespace App\Helpers;

class MarkdownHelper
{
    /**
     * Convierte texto en formato markdown a HTML
     *
     * @param string $text
     * @return string
     */
    public static function parse($text)
    {
        // Procesar imágenes primero
        $text = preg_replace('/!\[(.*?)\]\((.*?)\)/', '<img src="$2" alt="$1" class="w-full h-auto rounded-lg my-4">', $text);
        
        // Procesar encabezados
        $text = preg_replace('/^# (.*?)$/m', '<h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">$1</h2>', $text);
        $text = preg_replace('/^## (.*?)$/m', '<h3 class="text-xl font-bold text-gray-900 dark:text-white mt-6 mb-3">$1</h3>', $text);
        $text = preg_replace('/^### (.*?)$/m', '<h4 class="text-lg font-bold text-gray-900 dark:text-white mt-5 mb-2">$1</h4>', $text);
        
        // Procesar negrita
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
        
        // Procesar cursiva
        $text = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $text);
        
        // Procesar listas
        $text = preg_replace('/^- (.*?)$/m', '<li class="ml-6 mb-2">$1</li>', $text);
        $text = preg_replace('/((?:<li.*?>.*?<\/li>)+)/', '<ul class="list-disc mb-4">$1</ul>', $text);
        
        // Procesar párrafos (evitar wrappear elementos que ya están en HTML)
        $lines = explode("\n", $text);
        $result = '';
        $inParagraph = false;
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Si la línea está vacía y estamos en un párrafo, cerramos el párrafo
            if (empty($line) && $inParagraph) {
                $result .= "</p>\n\n";
                $inParagraph = false;
                continue;
            }
            
            // Si la línea está vacía, la saltamos
            if (empty($line)) {
                continue;
            }
            
            // Si la línea ya tiene HTML, la agregamos tal cual
            if (str_starts_with($line, '<')) {
                $result .= $line . "\n";
                continue;
            }
            
            // Si no estamos en un párrafo, iniciamos uno nuevo
            if (!$inParagraph) {
                $result .= "<p class=\"text-gray-700 dark:text-gray-300 mb-4\">";
                $inParagraph = true;
            }
            
            // Agregamos la línea
            $result .= $line . " ";
        }
        
        // Si terminamos y estamos en un párrafo, lo cerramos
        if ($inParagraph) {
            $result .= "</p>\n";
        }
        
        return $result;
    }
}