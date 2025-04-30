<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
class ServicioController extends Controller
{

    public function index(Request $request)
    {

        $servicios = Servicio::paginate(5);
        return view('servicios.index', compact('servicios'));
    }
    public function buscar(Request $request)
    {
        $search = $request->input('search');

        $servicios = Servicio::when($search, function ($query, $search) {
            return $query->where('nombre', 'like', '%' . $search . '%')
                    ->orWhereHas('categoria', function ($q) use ($search) {
                        $q->where('nombre', 'like', '%' . $search . '%');
                    })
                    ->orWhere('duracion', 'like', '%' . $search . '%')
                    ->orWhere(function ($q) use ($search) {

                        if (stripos('disponible', $search) !== false) {
                            $q->where('disponibilidad', 1);
                        }

                        if (stripos('no disponible', $search) !== false ) {
                            $q->orWhere('disponibilidad', 0);
                        }

                        if (strtolower($search) === 'disponible') {
                            $q->where('disponibilidad', 1);
                        } elseif (strtolower($search) === 'no disponible') {
                            $q->where('disponibilidad', 0);
                        }
                    });
        })->paginate(5);
        $html = view('servicios.parcial', compact('servicios'))->render();
        $pagination = $servicios->links()->render();

        return response()->json(['html' => $html, 'pagination' => $pagination]);
    }



    public function create()
    {
        $categorias = Categoria::where('estado', true)->get();
        return view('servicios.create')->with('categorias',$categorias);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:50',
                'unique:servicios,nombre',
                'not_regex:/@/',
                function ($attribute, $value, $fail) {
                    if (is_array($value)) {
                        $fail('El nombre del servicio no puede ser un arreglo.');
                    }
                    if (preg_match('/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/', $value) ||
                        preg_match('/^([0-9A-Fa-f]{4}\.){2}[0-9A-Fa-f]{4}$/', $value)) {
                        $fail('El nombre no puede tener el formato de una dirección MAC.');
                    }
                },
            ],
            'descripcion' => 'nullable|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'disponibilidad' => 'required|boolean',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'duracion' => 'required|integer|min:30|max:420',
        ], [
            'nombre.required' => 'El nombre del servicio es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede exceder los 50 caracteres.',
            'nombre.unique' => 'Ya existe un servicio con ese nombre.',
            'nombre.not_regex' => 'El nombre no puede contener el símbolo @.',

            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción no puede superar los 255 caracteres.',

            'categoria_id.required' => 'Debe seleccionar una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no existe.',

            'disponibilidad.required' => 'Debe indicar la disponibilidad del servicio.',
            'disponibilidad.boolean' => 'La disponibilidad debe ser un valor booleano.',

            'imagen.required' => 'Debe subir una imagen para el servicio.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo jpeg, png, jpg, gif o svg.',
            'imagen.max' => 'La imagen no puede superar los 2 MB.',

            'duracion.required' => 'La duración del servicio es obligatoria.',
            'duracion.integer' => 'La duración debe ser un número entero en minutos.',
            'duracion.min' => 'La duración mínima es de 30 minutos.',
            'duracion.max' => 'La duración máxima permitida es de 420 minutos.',
        ]);

        $path = $request->file('imagen')->store('images', 'public');

        Servicio::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'categoria_id' => $request->categoria_id,
            'disponibilidad' => $request->disponibilidad,
            'imagen' => $path,
            'duracion' => $request->duracion,
        ]);

        return redirect()->route('servicios.index')->with('success', 'Servicio creado correctamente.');
    }

    public function show($id)
    {
        $servicio = Servicio::findOrFail($id);
        $categoriaN = $servicio->categoria->nombre;
        $images = $servicio->images;
        return view('servicios.show', compact('servicio','categoriaN', 'images'));
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
            'nombre' => [
                'required',
                'string',
                'max:50',
                Rule::unique('servicios', 'nombre')->ignore($servicio->id),
                'not_regex:/@/',
                function ($attribute, $value, $fail) {
                    if (is_array($value)) {
                        $fail('El nombre del servicio no puede ser un arreglo.');
                    }
                    if (preg_match('/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/', $value) ||
                        preg_match('/^([0-9A-Fa-f]{4}\.){2}[0-9A-Fa-f]{4}$/', $value)) {
                        $fail('El nombre no puede tener el formato de una dirección MAC.');
                    }
                },
            ],
            'descripcion' => 'nullable|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'disponibilidad' => 'required|boolean',
            'duracion' => 'required|integer|min:30|max:420',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'nombre.required' => 'El nombre del servicio es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede exceder los 50 caracteres.',
            'nombre.unique' => 'Ya existe un servicio con ese nombre.',
            'nombre.not_regex' => 'El nombre no puede contener el símbolo @.',

            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción no puede superar los 255 caracteres.',

            'categoria_id.required' => 'Debe seleccionar una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no existe.',

            'disponibilidad.required' => 'Debe indicar la disponibilidad del servicio.',
            'disponibilidad.boolean' => 'La disponibilidad debe ser un valor booleano.',

            'duracion.required' => 'La duración del servicio es obligatoria.',
            'duracion.integer' => 'La duración debe ser un número entero en minutos.',
            'duracion.min' => 'La duración mínima es de 30 minutos.',
            'duracion.max' => 'La duración máxima permitida es de 420 minutos.',

            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo jpeg, png, jpg, gif o svg.',
            'imagen.max' => 'La imagen no puede superar los 2 MB.',
        ]);

        $servicio->nombre = $request->nombre;
        $servicio->descripcion = $request->descripcion;
        $servicio->categoria_id = $request->categoria_id;
        $servicio->disponibilidad = $request->disponibilidad;
        $servicio->duracion = $request->duracion;

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


}