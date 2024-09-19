<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::paginate(6);
        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'required|string|max:255',
            'estado' => 'required|boolean',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nombre.required' => 'El nombre es obligatorio y debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede exceder los 50 caracteres.',
            'descripcion.required' => 'La descripción es obligatoria y debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción no puede exceder los 255 caracteres.',
            'estado.required' => 'El estado es obligatorio y debe ser un valor booleano.',
            'estado.boolean' => 'El estado debe ser verdadero o falso.',
            'imagen.required' => 'La imagen es obligatoria',
            'imagen.image' => 'El archivo subido debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo jpeg, png, jpg o gif.',
            'imagen.max' => 'La imagen no puede exceder los 2 MB.',
        ]);

        $data = $request->all();

        // Verificar si el archivo de imagen se ha subido
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('images/categorias', 'public');
        } else {
            // Esto no debería ocurrir si la validación es correcta
            return redirect()->back()->withErrors(['imagen' => 'La imagen es obligatoria.']);
        }

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
            'nombre' => 'required|string|max:50',
            'descripcion' => 'required|string|max:255',
            'estado' => 'required|boolean',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nombre.required' => 'El nombre es obligatorio y debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede exceder los 50 caracteres.',
            'descripcion.required' => 'La descripción es obligatoria y debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción no puede exceder los 255 caracteres.',
            'estado.required' => 'El estado es obligatorio y debe ser un valor booleano.',
            'estado.boolean' => 'El estado debe ser verdadero o falso.',
            'imagen.image' => 'El archivo subido debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo jpeg, png, jpg o gif.',
            'imagen.max' => 'La imagen no puede exceder los 2 MB.',
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            // Eliminar la imagen antigua si existe
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