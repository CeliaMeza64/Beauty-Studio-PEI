<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index(Request $request) { $categorias = Categoria::paginate(3); return view('categorias.index', compact('categorias')); } public function buscar(Request $request) { $search = $request->input('search'); $categorias = Categoria::when($search, function ($query, $search) { return $query->where('nombre', 'like', '%' . $search . '%') ->orWhere(function ($q) use ($search) { if (stripos('activo', $search) !== false) { $q->where('estado', 1); } if (stripos('inactivo', $search) !== false) { $q->orWhere('estado', 0); } if (strtolower($search) === 'activo') { $q->where('estado', 1); } elseif (strtolower($search) === 'inactivo') { $q->where('estado', 0); } }); })->paginate(3); $html = view('categorias.parcial', compact('categorias'))->render(); $pagination = $categorias->links()->render(); return response()->json(['html' => $html, 'pagination' => $pagination]); }

    public function create()
    {
        return view('categorias.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'nombre' => [
            'required',
            'string',
            'max:50',
            'regex:/^(?!\d+$)(?![\W_]+$)[\pL\pN\s]+$/u',
            'unique:categorias,nombre',
        ],
        'descripcion' => 'required|string|max:255',
        'estado' => 'required|boolean',
        'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ], [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.max' => 'El nombre no puede exceder los 50 caracteres.',
        'nombre.regex' => 'El nombre no puede ser solo números ni solo caracteres especiales.',
        'nombre.unique' => 'Ya existe una categoría con este nombre.',
        'descripcion.required' => 'La descripción es obligatoria.',
        'estado.required' => 'El estado es obligatorio.',
        'imagen.required' => 'La imagen es obligatoria.',
        'imagen.image' => 'Debe subir una imagen válida.',
        'imagen.mimes' => 'Formatos válidos: jpeg, png, jpg o gif.',
        'imagen.max' => 'La imagen no puede superar los 2 MB.',
    ]);

    $data = $request->all();
    $data['imagen'] = $request->file('imagen')->store('images/categorias', 'public');

    Categoria::create($data);

    return redirect()->route('categorias.index')->with('success', 'Categoría creada con éxito.');
}


    public function show()
    {

        $categorias = Categoria::where('estado', true)->get();
        return view('categorias.show', compact('categorias'));
    }

    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
{
    $request->validate([
        'nombre' => [
            'required',
            'string',
            'max:50',
            'regex:/^(?!\d+$)(?![\W_]+$)[\pL\pN\s]+$/u',
            'unique:categorias,nombre,' . $categoria->id,
        ],
        'descripcion' => 'required|string|max:255',
        'estado' => 'required|boolean',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ], [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.max' => 'El nombre no puede exceder los 50 caracteres.',
        'nombre.regex' => 'El nombre no puede ser solo números ni solo caracteres especiales.',
        'nombre.unique' => 'Ya existe otra categoría con este nombre.',
        'descripcion.required' => 'La descripción es obligatoria.',
        'estado.required' => 'El estado es obligatorio.',
        'imagen.image' => 'Debe subir una imagen válida.',
        'imagen.mimes' => 'Formatos válidos: jpeg, png, jpg o gif.',
        'imagen.max' => 'La imagen no puede superar los 2 MB.',
    ]);

    $data = $request->all();

    if ($request->hasFile('imagen')) {
        if ($categoria->imagen) {
            \Storage::disk('public')->delete($categoria->imagen);
        }
        $data['imagen'] = $request->file('imagen')->store('images/categorias', 'public');
    }

    $categoria->update($data);

    return redirect()->route('categorias.index')->with('success', 'Categoría actualizada con éxito.');
}


    public function destroy(Categoria $categoria)
    {
        if ($categoria->imagen) {
            \Storage::disk('public')->delete($categoria->imagen);
        }

        $categoria->delete();
        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada con éxito.');
    }
}