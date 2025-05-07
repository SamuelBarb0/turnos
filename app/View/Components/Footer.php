<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\PaginaSeccion;
use Illuminate\Support\Str;

class Footer extends Component
{
    public $footerColumns;
    public $copyright;
    public $socialLinks;

    protected $socialNames = [
        'facebook', 'instagram', 'twitter', 'whatsapp'
    ];

    public function __construct()
    {
        // Footer directo con ID 5
        $footerSeccion = PaginaSeccion::find(5);
        $footerColumns = [];
        $socialLinks = [];
        $copyright = date('Y') . ' Todos los derechos reservados';
        $currentColumn = null;

        if ($footerSeccion) {
            foreach ($footerSeccion->contenidos()->orderBy('orden')->get() as $contenido) {
                if ($contenido->etiqueta == 'h3') {
                    if ($currentColumn) {
                        $footerColumns[] = $currentColumn;
                    }
                    $currentColumn = [
                        'title' => $contenido->contenido,
                        'links' => []
                    ];
                } elseif ($contenido->etiqueta == 'ul' && $currentColumn) {
                    $linkTexts = preg_split('/\r\n|\r|\n/', $contenido->contenido);
                    foreach ($linkTexts as $linkText) {
                        if (preg_match('/\[([^\]]+)\]\(([^\)]+)\)/', $linkText, $matches)) {
                            $text = $matches[1];
                            $url = $matches[2];

                            // Detectar si es red social
                            if ($this->isSocial($text)) {
                                $socialLinks[] = [
                                    'name' => $text,
                                    'url' => $url
                                ];
                            } else {
                                $currentColumn['links'][] = [
                                    'text' => $text,
                                    'url' => $url
                                ];
                            }
                        } else {
                            $currentColumn['links'][] = [
                                'text' => trim($linkText),
                                'url' => '#'
                            ];
                        }
                    }
                } elseif ($contenido->etiqueta == 'p' && strpos($contenido->contenido, '©') !== false) {
                    $copyright = $contenido->contenido;
                }
            }

            if ($currentColumn) {
                $footerColumns[] = $currentColumn;
            }
        }

        $this->footerColumns = $footerColumns;
        $this->socialLinks = $socialLinks;
        $this->copyright = $copyright;
    }

    protected function isSocial($name)
    {
        $name = strtolower($name);
        foreach ($this->socialNames as $social) {
            if (Str::contains($name, $social)) {
                return true;
            }
        }
        return false;
    }

    public function render()
    {
        return view('components.footer');
    }
}
