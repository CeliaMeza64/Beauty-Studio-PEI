<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Models\Categoria;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;


class ReservaController extends Controller
{
    public function index()
    {
    $reservas = Reserva::with('servicios')
    ->orderBy('fecha_reservacion')
    ->orderBy('hora_reservacion')
    ->paginate(7);
    $reservas->getCollection()->transform(function ($reserva) {
        // Calcular la hora de finalización
        $horaInicio = Carbon::createFromFormat('H:i:s', $reserva->hora_reservacion);
        $duracionTotal = $reserva->servicios->sum('duracion'); // Duración total de los servicios
        $horaFin = $horaInicio->addMinutes($duracionTotal)->format('H:i:s'); // Formato de la hora final
        $reserva->hora_fin = $horaFin; // Añadir la hora de finalización calculada
        return $reserva;
    });
    return view('reservas.index', compact('reservas'));
    }
    public function create(){
        $categorias = Categoria::with('servicios')->get();
        return view('reservas.create', compact('categorias'));
    }

    public function store(Request $request) {
        $duracionServicio = 60; // Duración del servicio en minutos
        $startHour = 9; // 09:00 AM
        $endHour = 21; // 09:00 PM (en formato de 24 horas)
        $endTime = strtotime("{$endHour}:00");
        $horasDisponibles = [];
        for ($i = $startHour; $i <= $endHour; $i++) {
            for ($j = 0; $j < 60; $j += 30) {
                $value = sprintf('%02d:%02d', $i, $j); // Formato 24 horas
                $finReserva = strtotime($value) + ($duracionServicio * 60);
                if ($finReserva <= $endTime) {
                    $horasDisponibles[] = $value; // Agregar hora disponible a la lista
                }
            }
        }
        $validated = $request->validate([
            'nombre_cliente' => 'required|string|max:30',
            'telefono_cliente' => 'required|string|size:9|regex:/^\d{4}-\d{4}$/',
            'categoria_id' => 'required|array|min:1',
            'categoria_id.*' => 'exists:categorias,id',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:servicios,id',
            'fecha_reservacion' => 'required|date|after:today',
            'hora_reservacion' => ['required', 'date_format:H:i', Rule:: in($horasDisponibles) ],
        ], [
            'nombre_cliente.required' => 'El nombre del cliente es obligatorio.',
            'telefono_cliente.required' => 'El teléfono del cliente es obligatorio.',
            'telefono_cliente.regex' => 'El teléfono debe tener el formato XXXX-XXXX.',
            'categoria_id.required' => 'Debe seleccionar al menos una categoría.',
        'servicios.required' => 'Debe seleccionar al menos un servicio.',
            'fecha_reservacion.required' => 'La fecha de reservación es obligatoria.',
            'fecha_reservacion.date' => 'La fecha de reservación no tiene un formato válido.',
            'hora_reservacion.required' => 'La hora de reservación es obligatoria.',
            'hora_reservacion.in' => 'La hora de reservación debe ser una de las horas disponibles entre 09:00 AM y 8:00 PM.',
        ]);
        $horaCierre = Carbon::createFromTimeString(Reserva::$horaCierre);
        $duracionTotal = Servicio::whereIn('id', $request->servicios)->sum('duracion');
        $horaInicio = Carbon::createFromTimeString($request->hora_reservacion);
        $horaFin = $horaInicio->clone()->addMinutes($duracionTotal);
        // Validar si la hora de finalización supera la hora de cierre
        if ($horaFin->greaterThan($horaCierre)) {
            return back()->withErrors([
                'hora_reservacion' => 'La duración total excede el horario de cierre del local.',
            ])->withInput();
        }
        $traslape = Reserva::where('fecha_reservacion', $request->fecha_reservacion)
        ->where(function ($query) use ($horaInicio, $horaFin) {
            $query->whereBetween('hora_reservacion', [$horaInicio, $horaFin])
                  ->orWhereBetween(DB::raw('ADDTIME(hora_reservacion, SEC_TO_TIME(duracion * 60))'), [$horaInicio, $horaFin])
                  ->orWhere(function ($query) use ($horaInicio, $horaFin) {
                      $query->where('hora_reservacion', '<', $horaInicio)
                            ->where(DB::raw('ADDTIME(hora_reservacion, SEC_TO_TIME(duracion * 60))'), '>', $horaFin);
                  });
        })->exists();

    if ($traslape) {
        return back()->withErrors([
            'hora_reservacion' => 'Ya existe una reserva en este rango de tiempo. Intente con otra hora.',
        ])->withInput();
    }
      
    $reserva = Reserva::create([
        'nombre_cliente' => $validated['nombre_cliente'],
        'telefono_cliente' => $validated['telefono_cliente'],
        'fecha_reservacion' => $validated['fecha_reservacion'],
        'hora_reservacion' => $validated['hora_reservacion'],
        'duracion' => $duracionTotal,
    ]);
        // Asociar los servicios seleccionados con la reserva
        $reserva->servicios()->attach($request->servicios);
        $datosReserva = [
            'nombre_cliente' => $reserva->nombre_cliente,
            'telefono_cliente' => $reserva->telefono_cliente,
            'servicios' => $reserva->servicios->pluck('nombre')->toArray(),
            'fecha_reservacion' => $reserva->fecha_reservacion,
            'hora_reservacion' => $reserva->hora_reservacion,
            'hora_fin' => $horaFin->format('H:i'),
            'duracion' => $duracionTotal,
            'estado' => 'Pendiente',
        ];
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'reserva' => $datosReserva,
            ]);
        }

        // Redirigir a la página de índice con un mensaje de éxito
        return redirect()->route('reservas.create')->with('success', 'Reserva creada con éxito.');
    }

    public function storeNew(Request $request){
        $validated = $request->validate([
            'nombre_cliente' => 'required|string|max:30',
            'telefono_cliente' => 'required|string|max:9|regex:/^\d{4}-\d{4}$/',
            'fecha_reservacion' => 'required|date|after:tomorrow',
            'hora_reservacion' => 'required|in:09:00,11:00,13:00,15:00,18:00,20:00',
        ]);
        // Verificar si ya existe una reserva en esa fecha y hora
        $fecha = $request->input('fecha_reservacion');
        $hora = $request->input('hora_reservacion');
        $reservaExistente = Reserva::where('fecha_reservacion', $fecha)
            ->where('hora_reservacion', $hora)
            ->exists();
        if ($reservaExistente) {
            return response()->json(['success' => false, 'message' => 'Ya existe una reserva para esta fecha y hora.']);
        }
        Reserva::create($validated);
        return response()->json(['success' => true]);
    }
    public function edit(Reserva $reserva){
        $servicios = Servicio::all();
        $categorias = Categoria::all();
        return view('reservas.edit', compact('reserva', 'servicios', 'categorias'));
    }


    public function descargarPDF($id)
    {
        $reserva = Reserva::findOrFail($id); // Busca la reserva

        // Renderiza la vista como PDF
        $pdf = Pdf::loadView('reservas.recibo', compact('reserva'));
        return $pdf->download('recibo_reserva_'.$reserva->id.'.pdf');
    }


    public function update(Request $request, Reserva $reserva)
    {
        $validated = $request->validate([

            'estado' => 'required|in:Pendiente,Aprobado,Rechazado,Cancelado,Realizado',        ],
            [

        ], [

            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser uno de los valores permitidos.',
        ]);

        if ($request->estado === 'Realizado' && $reserva->fecha_reservacion > now()->format('Y-m-d')) {
            return redirect()->back()
                ->withErrors(['estado' => 'No se puede marcar como "Realizado" si la fecha de la reservación no ha pasado.'])
                ->withInput();
        }
        $exists = Reserva::where('fecha_reservacion', $request->fecha_reservacion)
        ->where('hora_reservacion', $request->hora_reservacion)
        ->where('id', '!=', $reserva->id) // Excluir la reserva actual
        ->where('estado', '!=', 'Rechazado') // No considerar reservas rechazadas
        ->exists();
    if ($exists) {
        return redirect()->back()
            ->withErrors(['hora_reservacion' => 'Ya existe una reserva para esa fecha y hora.'])
            ->withInput();
    }
        // Verificar si ya existe una reserva en esa fecha y hora, excluyendo la actual
        $exists = Reserva::where('fecha_reservacion', $request->fecha_reservacion)
            ->where('hora_reservacion', $request->hora_reservacion)
            ->where('id', '!=', $reserva->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['hora_reservacion' => 'Ya existe una reserva para esa fecha y hora.'])->withInput();
        }

        $reserva->update($validated);

        return redirect()->route('reservas.index')->with('success', 'Reserva actualizada correctamente.');
    }


    public function destroy(Reserva $reserva)
    {
        $reserva->delete();
        return redirect()->route('reservas.index')->with('success', 'Reserva eliminada exitosamente.');
    }

    public function confirm(Request $request, $id)
    {
        $reserva = Reserva::findOrFail($id);
        $reserva->estado = 'confirmada';
        $reserva->save();

        $datosModal = [
            'nombre_cliente' => $reserva->nombre_cliente,
            'telefono_cliente' => $reserva->telefono_cliente,
            'fecha_reservacion' => $reserva->fecha_reservacion,
            'hora_reservacion' => $reserva->hora_reservacion,
            'estado' => $reserva->estado,
        ];

        return redirect()->route('reservas.index')->with([
            'success' => 'Reserva confirmada exitosamente.',
            'mostrarModal' => true,
            'datosModal' => $datosModal
        ]);
    }

    public function cancel(Request $request, $id)
    {
        $reserva = Reserva::findOrFail($id);
        $reserva->estado = 'cancelada';
        $reserva->save();

        return redirect()->route('reservas.index')->with('success', 'Reserva cancelada exitosamente.');
    }

    public function checkAvailability(Request $request)
    {
        $fecha = $request->input('fecha');
        $hora = $request->input('hora');

        $reservaExistente = Reserva::where('fecha_reservacion', $fecha)
                                    ->where('hora_reservacion', $hora)
                                    ->exists();

        return response()->json(['disponible' => !$reservaExistente]);
    }

    // Función para obtener servicios por categoría
    public function getServiciosByCategoria($categoria_id)
    {
        $servicios = Servicio::where('categoria_id', $categoria_id)->get();
        return response()->json($servicios);
    }

    public function filtrarServicios(Request $request)
    {
        $categoria_id = $request->input('categoria_id');
        $servicios = Servicio::where('categoria_id', $categoria_id)->get();

        return response()->json($servicios);
    }


    public function getReservas()
{
    // Establecer la zona horaria
    date_default_timezone_set('America/Tegucigalpa');

    // Agrupar reservas por fecha y contar
    $reservasPorDia = Reserva::selectRaw('fecha_reservacion, COUNT(*) as cantidad')
        ->groupBy('fecha_reservacion')
        ->get();

    $events = [];

    foreach ($reservasPorDia as $dia) {
        $events[] = [
            'title' => $dia->cantidad . ' reservas', // Cantidad de reservas como título
            'start' => $dia->fecha_reservacion, // Fecha del evento
        ];
    }

    return response()->json($events);
}


public function reservasPorDia($fecha)
{
    // Obtener las reservas de la fecha seleccionada
    $reservas = Reserva::with('servicios')
        ->whereDate('fecha_reservacion', $fecha)
        ->orderBy('hora_reservacion', 'asc') // Ordenar por hora
        ->get();

    $events = [];
    foreach ($reservas as $reserva) {
        $nombresServicios = $reserva->servicios->isNotEmpty()
        ? $reserva->servicios->pluck('nombre')->join(', ')
        : 'Sin servicios';
        $events[] = [
            'id' => $reserva->id,
            'title' => $reserva->nombre_cliente ,
            'time' => $reserva->hora_reservacion,
            'description' => $reserva->telefono_cliente,
            'servicios' => $nombresServicios ?: 'Sin servicios',
        ];
    }



    return response()->json($events);
}

public function detallesReserva($id)
{
    // Obtener los detalles de la reserva específica
    $reserva = Reserva::with('servicios')->find($id);

    if (!$reserva) {
        return response()->json(null, 404); // Reserva no encontrada
    }

    $nombresServicios = $reserva->servicios->pluck('nombre')->join(', ');
    $detalle = [
        'title' => $reserva->nombre_cliente,
        'time' => $reserva->hora_reservacion,
        'description' => $reserva->telefono_cliente,
        'servicios' => $nombresServicios,
    ];

    return response()->json($detalle);
}


}
