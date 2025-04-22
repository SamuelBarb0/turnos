<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pagina;
use App\Models\PaginaSeccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaginaSeccionController extends Controller
{
    public function index(Pagina $pagina)
    {
        $secciones = $pagina->secciones()->orderBy('orden')->get();
        return view('admin.secciones.index', compact('pagina', 'secciones'));
    }

    public function create(Pagina $pagina)
    {
        return view('admin.secciones.create', compact('pagina'));
    }

    public function store(Request $request, Pagina $pagina)
    {
        $request->validate([
            'seccion' => 'required',
            'imagen' => 'nullable|image|max:2048',
            'orden' => 'nullable|integer'
        ]);

        $data = $request->all();
        $data['pagina_id'] = $pagina->id;
        
        // Manejar la carga de la imagen
        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('secciones', 'public');
            $data['ruta_image'] = $path;
        }

        PaginaSeccion::create($data);

        return redirect()->route('admin.paginas.secciones.index', $pagina)
            ->with('success', 'Sección creada exitosamente');
    }

    public function edit(Pagina $pagina, PaginaSeccion $seccion)
    {
        return view('admin.secciones.edit', compact('pagina', 'seccion'));
    }

    public function update(Request $request, Pagina $pagina, PaginaSeccion $seccion)
    {
        $request->validate([
            'seccion' => 'required',
            'imagen' => 'nullable|image|max:2048',
            'orden' => 'nullable|integer'
        ]);

        $data = $request->all();
        
        // Manejar la carga de la imagen
        if ($request->hasFile('imagen')) {
            // Eliminar la imagen anterior si existe
            if ($seccion->ruta_image && Storage::disk('public')->exists($seccion->ruta_image)) {
                Storage::disk('public')->delete($seccion->ruta_image);
            }
            
            $path = $request->file('imagen')->store('secciones', 'public');
            $data['ruta_image'] = $path;
        }

        $seccion->update($data);

        return redirect()->route('admin.paginas.secciones.index', $pagina)
            ->with('success', 'Sección actualizada exitosamente');
    }

    public function destroy(Pagina $pagina, PaginaSeccion $seccion)
    {
        // Eliminar la imagen si existe
        if ($seccion->ruta_image && Storage::disk('public')->exists($seccion->ruta_image)) {
            Storage::disk('public')->delete($seccion->ruta_image);
        }
        
        $seccion->delete();

        return redirect()->route('admin.paginas.secciones.index', $pagina)
            ->with('success', 'Sección eliminada exitosamente');
    }
}
