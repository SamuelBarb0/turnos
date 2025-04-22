<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pagina;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaginaController extends Controller
{
    public function index()
    {
        $paginas = Pagina::all();
        return view('admin.paginas.index', compact('paginas'));
    }

    public function create()
    {
        return view('admin.paginas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'nullable|unique:paginas,slug'
        ]);

        $data = $request->all();
        
        // Si no se proporciona un slug, generarlo desde el título
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        Pagina::create($data);

        return redirect()->route('admin.paginas.index')
            ->with('success', 'Página creada exitosamente');
    }

    public function edit(Pagina $pagina)
    {
        return view('admin.paginas.edit', compact('pagina'));
    }

    public function update(Request $request, Pagina $pagina)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'nullable|unique:paginas,slug,' . $pagina->id
        ]);

        $data = $request->all();
        
        // Si no se proporciona un slug, generarlo desde el título
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $pagina->update($data);

        return redirect()->route('admin.paginas.index')
            ->with('success', 'Página actualizada exitosamente');
    }

    public function destroy(Pagina $pagina)
    {
        $pagina->delete();

        return redirect()->route('admin.paginas.index')
            ->with('success', 'Página eliminada exitosamente');
    }
}