<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trend;
use App\Models\Servicio;
use Carbon\Carbon;

class TrendController extends Controller
{
    public function index()
    {
        $trends = Trend::paginate(10);
        return view('trends.index', compact('trends'));
    }

    public function create()
    {
        return view('trends.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:100',
            'description' => 'required',
            'image' => 'image|nullable|max:1999'
        ]);

        if ($request->hasFile('image')) {
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path = $request->file('image')->storeAs('public/trends_images', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        $trend = new Trend;
        $trend->title = $request->input('title');
        $trend->description = $request->input('description');
        $trend->image = $fileNameToStore;
        $trend->save();

        return redirect('/trends')->with('success', 'Trend Created');
    }

    public function show()
    {
        $monthStart = Carbon::now()->startOfMonth();
    $monthEnd = Carbon::now()->endOfMonth();

    $trends = Servicio::withCount(['reservas' => function ($query) use ($monthStart, $monthEnd) {
                    $query->whereBetween('created_at', [$monthStart, $monthEnd]);
                }])
                ->with('imagenes')  // Cargar imágenes para el carrusel
                ->orderByDesc('reservas_count')
                ->take(3)  // Los tres servicios más reservados
                ->get();

    return view('trends.show', compact('trends'));
    }

    public function edit(string $id)
    {
        $trend = Trend::find($id);
        return view('trends.edit', compact('trend'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|max:100', 
            'description' => 'required',
            'image' => 'image|nullable|max:1999'
        ]);

        $trend = Trend::find($id);
        $trend->title = $request->input('title');
        $trend->description = $request->input('description');

        if ($request->hasFile('image')) {
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path = $request->file('image')->storeAs('public/trends_images', $fileNameToStore);
            $trend->image = $fileNameToStore;
        }

        $trend->save();

        return redirect('/trends')->with('success', 'Trend Updated');
    }

    public function destroy(string $id)
    {
        $trend = Trend::find($id);
        if ($trend) {
            $trend->delete();
            return redirect('/trends')->with('success', 'Tendencia Removida');
        }
        return redirect('/trends')->with('error', 'Tendencia no encontrada');
    }
}