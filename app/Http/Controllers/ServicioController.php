<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;
class ServicioController extends Controller
{

    public function index()
    {
        $servicios = Servicio::paginate(6);
        return view('servicios.index')->with('servicios',$servicios);
    }

    public function create()
    {
        $categorias = Categoria::where('estado', true)->get();
        return view('servicios.create')->with('categorias',$categorias);
    }

    public function store(Request $request)
    {
        $request->validate([
    
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'disponibilidad' => 'required|boolean',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 50 caracteres.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'categoria_id.required' => 'La categoría es obligatoria.',
            'categoria_id.exists' => 'La categoría seleccionada no existe.',
            'disponibilidad.required' => 'La disponibilidad es obligatoria.',
            'imagen.image' => 'El archivo subido debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo jpeg, png, jpg, gif o svg.',
    
        ]);

        $path = $request->file('imagen') ? $request->file('imagen')->store('images', 'public') : null;

        Servicio::create([
      
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'categoria_id' => $request->categoria_id,
            'disponibilidad' => $request->disponibilidad,
            'imagen' => $path,
         
        ]);

        return redirect()->route('servicios.index')->with('success', 'Servicio creado correctamente.');
    }

    public function show($id)
    {
        $servicio = Servicio::findOrFail($id);
        $categoriaN = $servicio->categoria->nombre;
        return view('servicios.show', compact('servicio','categoriaN'));
    }
    
    public function showServicios($categoriaN){
        $categoria = Categoria::where('nombre',$categoriaN)->where('estado', true)->first();
        if($categoria){
            $servicios = Servicio::where('categoria_id',$categoria->id)
            ->where('disponibilidad', true)
            ->orderBy('nombre')->get();

        }else{
            $servicios = collect();
        }
        return view('servicios.showServicios',compact('servicios','categoriaN'));
    }

    public function edit($id)
    {
        $servicio = Servicio::findOrFail($id);
        $categorias = Categoria::all();
        return view('servicios.edit', compact('servicio', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'disponibilidad' => 'required|boolean',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 50 caracteres.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'categoria_id.required' => 'La categoría es obligatoria.',
            'categoria_id.exists' => 'La categoría seleccionada no existe.',
            'disponibilidad.required' => 'La disponibilidad es obligatoria.',
            'imagen.image' => 'El archivo subido debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo jpeg, png, jpg, gif o svg.',
            'imagen.max' => 'La imagen no puede exceder los 2 MB.',
     
        ]);

        $servicio->nombre = $request->nombre;
        $servicio->descripcion = $request->descripcion;
        $servicio->categoria_id = $request->categoria_id;
        $servicio->disponibilidad = $request->disponibilidad;

        if ($request->hasFile('imagen')) {
            if ($servicio->imagen) {
                Storage::delete('public/' . $servicio->imagen);
            }
            $path = $request->file('imagen')->store('images', 'public');
            $servicio->imagen = $path;
        }

        $servicio->save();

        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);
        if ($servicio->imagen) {
            Storage::delete('public/' . $servicio->imagen);
        }
        $servicio->delete();
        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado correctamente.');
    }


}
