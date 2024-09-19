<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use Illuminate\Support\Facades\Log;
use App\Models\ServicioImage;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        Log::info('Accediendo a la vista de home');
        $servicios = Servicio::paginate(6);
        return view('home' , compact('servicios'));
    }
}

 // O cualquier lógica para obtener los servicios

