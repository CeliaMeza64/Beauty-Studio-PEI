<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Models\ServicioImage;
use Illuminate\Support\Facades\Storage;

class ServicioImageController extends Controller
{
    public function index($servicioId)
    {
        $servicio = Servicio::findOrFail($servicioId);
        $images = $servicio->images;
        return view('serviciosImagen.index', compact('servicio', 'images'));
    }

    public function create(Servicio $servicio)
    {
        $servicios = Servicio::all();
        $servicioId = $servicio->id;
        return view('serviciosImagen.create', compact('servicios', 'servicioId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'servicio_id' => 'required|exists:servicios,id',
        ]);

    $servicio = Servicio::findOrFail($request->input('servicio_id'));

        $path = $request->file('image')->store('images', 'public');

        $servicio->images()->create([
            'path' => $path,
        ]);

        return redirect()->route('serviciosImagen.index', ['servicio' => $servicio->id])->with('success', 'Imagen agregada con éxito.');
    }

    public function show(Servicio $servicio)
    {
        $images = $servicio->images; 
        return view('serviciosImagen.show', compact('servicio', 'images'));
    }


    public function edit(Servicio $servicio, ServicioImage $image)
    {
        return view('serviciosImagen.edit', compact('servicio', 'image'));
    }

    public function update(Request $request, Servicio $servicio, ServicioImage $image)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($image->path);
            $path = $request->file('image')->store('images', 'public');

            $image->update(['path' => $path]);
        }

        return redirect()->route('serviciosImagen.index', $servicio->id)->with('success', 'Imagen actualizada con éxito.');
    }

    public function destroy(Servicio $servicio, ServicioImage $image)
    {
       
        Storage::disk('public')->delete($image->path);

        $image->delete();

        return redirect()->route('serviciosImagen.index', $servicio->id)->with('success', 'Imagen eliminada con éxito.');
    }
}
