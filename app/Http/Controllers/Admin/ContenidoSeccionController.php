<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaginaSeccion;
use App\Models\ContenidoSeccion;
use Illuminate\Http\Request;

class ContenidoSeccionController extends Controller
{
    public function index(PaginaSeccion $seccion)
    {
        $contenidos = $seccion->contenidos()->orderBy('orden')->get();
        $pagina = $seccion->pagina;
        return view('admin.contenidos.index', compact('seccion', 'contenidos', 'pagina'));
    }

    public function create(PaginaSeccion $seccion)
    {
        $pagina = $seccion->pagina;
        return view('admin.contenidos.create', compact('seccion', 'pagina'));
    }

    public function store(Request $request, PaginaSeccion $seccion)
    {
        $request->validate([
            'etiqueta' => 'required',
            'contenido' => 'required',
            'orden' => 'nullable|integer'
        ]);

        $data = $request->all();
        $data['id_pagina_seccion'] = $seccion->id;

        ContenidoSeccion::create($data);

        return redirect()->route('admin.secciones.contenidos.index', $seccion)
            ->with('success', 'Contenido creado exitosamente');
    }

    public function edit(PaginaSeccion $seccion, ContenidoSeccion $contenido)
    {
        $pagina = $seccion->pagina;
        return view('admin.contenidos.edit', compact('seccion', 'contenido', 'pagina'));
    }

    public function update(Request $request, PaginaSeccion $seccion, ContenidoSeccion $contenido)
    {
        $request->validate([
            'etiqueta' => 'required',
            'contenido' => 'required',
            'orden' => 'nullable|integer'
        ]);

        $contenido->update($request->all());

        return redirect()->route('admin.secciones.contenidos.index', $seccion)
            ->with('success', 'Contenido actualizado exitosamente');
    }

    public function destroy(PaginaSeccion $seccion, ContenidoSeccion $contenido)
    {
        $contenido->delete();

        return redirect()->route('admin.secciones.contenidos.index', $seccion)
            ->with('success', 'Contenido eliminado exitosamente');
    }
}
