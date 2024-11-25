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

    $monthStart = Carbon::now()->startOfMonth();
    $monthEnd = Carbon::now()->endOfMonth();

    $reservasPorServicio = Servicio::withCount(['reservas' => function ($query) use ($monthStart, $monthEnd) {
            $query->whereBetween('created_at', [$monthStart, $monthEnd]);
        }])
        ->orderByDesc('reservas_count')
        ->get();

    return view('trends.index', compact('trends', 'reservasPorServicio'));
}


    public function show()
    {
        $monthStart = Carbon::now()->startOfMonth();
    $monthEnd = Carbon::now()->endOfMonth();

    $trends = Servicio::withCount(['reservas' => function ($query) use ($monthStart, $monthEnd) {
                    $query->whereBetween('created_at', [$monthStart, $monthEnd]);
                }])
                ->with('images') 
                ->orderByDesc('reservas_count')
                ->take(5)  
                ->get();

    return view('trends.show', compact('trends'));
    }
}