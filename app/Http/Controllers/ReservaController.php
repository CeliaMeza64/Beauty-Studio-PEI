<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Models\Categoria;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class ReservaController extends Controller
{
    public function index()
    {
        $reservas = Reserva::orderBy('fecha_reservacion')
            ->orderBy('hora_reservacion')
            ->paginate(7);

        return view('reservas.index', compact('reservas'));
    }

    public function create()
    {
        $categorias = Categoria::with('servicios')->get();
        return view('reservas.create', compact('categorias'));
    }
    
    public function store(Request $request)
    {
        $duracionServicio = 60; // Duración del servicio en minutos
        $startHour = 9; // 09:00 AM
        $endHour = 19; // 07:00 PM (en formato de 24 horas)
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
            'servicio_id' => 'required|exists:servicios,id',
            'categoria_id' => 'required|exists:categorias,id',
            'fecha_reservacion' => 'required|date|after:today',
            'hora_reservacion' => ['required', 'date_format:H:i', Rule:: in($horasDisponibles) ],
            'duracion' => 'required|integer'
        ], [
            'nombre_cliente.required' => 'El nombre del cliente es obligatorio.',
            'telefono_cliente.required' => 'El teléfono del cliente es obligatorio.',
            'telefono_cliente.regex' => 'El teléfono debe tener el formato XXXX-XXXX.',
            'servicio_id.required' => 'El servicio es obligatorio.',
            'categoria_id.required' => 'La categoría es obligatoria.',
            'fecha_reservacion.required' => 'La fecha de reservación es obligatoria.',
            'fecha_reservacion.date' => 'La fecha de reservación no tiene un formato válido.',
            'hora_reservacion.required' => 'La hora de reservación es obligatoria.',
            'hora_reservacion.in' => 'La hora de reservación debe ser una de las horas disponibles entre 09:00 AM y 07:30 PM.',
            'duracion' => 'La duracion debe ser requerida',      
        
        ]);
        $reserva = new Reserva();
        $reserva->nombre_cliente = $request->input('nombre_cliente');
        $reserva->telefono_cliente = $request->input('telefono_cliente');
        $reserva->servicio_id = $request->input('servicio_id');
        $reserva->categoria_id = $request->input('categoria_id');
        $reserva->fecha_reservacion = $request->input('fecha_reservacion');
        $reserva->hora_reservacion = $request->input('hora_reservacion'); // Se almacena como HH:MM:SS
        $reserva->duracion = $request->input('duracion');

        // Verificar si ya existe una reserva en esa fecha y hora
        $fecha = $request->input('fecha_reservacion');
        $hora = $request->input('hora_reservacion');

        $reservaExistente = Reserva::where('fecha_reservacion', $fecha)
            ->where('hora_reservacion', $hora)
            ->exists();

        if ($reservaExistente) {
            return back()->withErrors(['hora_reservacion' => 'Ya existe una reserva para esta fecha y hora. Por favor, elija otro horario.'])
                        ->withInput();
        }

        // Si no existe, proceder a guardar la nueva reserva
    
        $reserva->save();

        // Redirigir a la página de índice con un mensaje de éxito
        return redirect()->route('reservas.create')->with('success', 'Reserva creada con éxito.');
    }

    public function storeNew(Request $request)
    {
        $validated = $request->validate([
            'nombre_cliente' => 'required|string|max:30',
            'telefono_cliente' => 'required|string|max:9|regex:/^\d{4}-\d{4}$/',
            'fecha_reservacion' => 'required|date|after:tomorrow',
            'hora_reservacion' => 'required|in:09:00,11:00,13:00,15:00,18:00,20:00',
            'duracion' => 'required'
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

    public function edit(Reserva $reserva)
    {
        $servicios = Servicio::all();
        $categorias = Categoria::all();
        return view('reservas.edit', compact('reserva', 'servicios', 'categorias'));
    }

    public function update(Request $request, Reserva $reserva)
    {
        $validated = $request->validate([
            'nombre_cliente' => 'required|string|max:30',
            'telefono_cliente' => 'required|string|size:9|regex:/^\d{4}-\d{4}$/',
            'servicio_id' => 'required|exists:servicios,id',
            'categoria_id' => 'required|exists:categorias,id',
            'fecha_reservacion' => 'required|date|after:today',
            'hora_reservacion' => 'required|in:09:00,11:00,13:00,15:00,18:00,20:00',
            'estado' => 'required|in:Pendiente,Aprobado,Rechazado,Cancelado,Realizado',        ],
            [
            'nombre_cliente.required' => 'El nombre del cliente es obligatorio.',
            'telefono_cliente.required' => 'El teléfono del cliente es obligatorio.',
            'telefono_cliente.regex' => 'El teléfono debe tener el formato 3345-7865.',
            'servicio_id.required' => 'El servicio es obligatorio.',
            'categoria_id.required' => 'La categoría es obligatoria.',
            'fecha_reservacion.required' => 'La fecha de reservación es obligatoria.',
            'fecha_reservacion.date' => 'La fecha de reservación no tiene un formato válido.',
            'hora_reservacion.required' => 'La hora de reservación es obligatoria.',
            'hora_reservacion.in' => 'La hora de reservación debe ser una de las siguientes: 09:00, 11:00, 13:00, 15:00, 18:00, 20:00.',
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

        // Generar el contenido para impresión
        $nombre = $reserva->nombre_cliente;
        $telefono = $reserva->telefono_cliente;
        $fecha = $reserva->fecha_reservacion;
        $hora = $reserva->hora_reservacion;
        $duracion = $reserva->duracion;
        $printContent = "
            <html>
            <head><title>Imprimir Reserva</title></head>
            <body>
                <h1>Reserva Confirmada</h1>
                <ul>
                    <li><strong>Nombre:</strong> $nombre</li>
                    <li><strong>Teléfono:</strong> $telefono</li>
                    <li><strong>Fecha:</strong> $fecha</li>
                    <li><strong>Hora:</strong> $hora</li>
                    <li><strong>Duracion:</strong> $duracion </li>
                </ul>
            </body>
            </html>
        ";

        $printWindow = "<script>
            var printContent = $printContent;
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        </script>";

        return redirect()->route('reservas.index')->with('success', 'Reserva confirmada exitosamente.')->with('printWindow', $printWindow);
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
        // NO TOQUE YIRENY 

    public function getReservas()
{
    // Establecer la zona horaria
    date_default_timezone_set('America/Tegucigalpa');

    // Obtener todas las reservas
    $reservas = Reserva::all();
    
    if ($reservas->isEmpty()) {
        return response()->json([]);
    }

    $events = [];

    foreach ($reservas as $reserva) {
        // Crear un evento para cada reserva con su hora y detalles
        $events[] = [
            'id' => $reserva->id, // Usamos el ID para identificar la reserva en el evento 'eventClick'
            'title' => $reserva->nombre_cliente, // Mostrar el nombre del cliente
            'start' => $reserva->fecha_reservacion . 'T' . $reserva->hora_reservacion, // Incluir la fecha y hora
            'description' => $reserva->telefono_cliente, // Teléfono del cliente
            'servicio' => $reserva->servicio->nombre, // Nombre del servicio reservado
        ];
    }

    return response()->json($events);
}

public function reservasPorDia($fecha)
{
    // Obtener las reservas de la fecha seleccionada
    $reservas = Reserva::with('servicio')
        ->whereDate('fecha_reservacion', $fecha)
        ->orderBy('hora_reservacion', 'asc') // Ordenar por hora
        ->get();

    $events = [];
    foreach ($reservas as $reserva) {
        $events[] = [
            'id' => $reserva->id,
            'title' => $reserva->nombre_cliente,
            'time' => $reserva->hora_reservacion,
            'description' => $reserva->telefono_cliente,
            'servicio' => $reserva->servicio->nombre
        ];
    }

    return response()->json($events);
}

public function detallesReserva($id)
{
    // Obtener los detalles de la reserva específica
    $reserva = Reserva::with('servicio')->find($id);

    if (!$reserva) {
        return response()->json(null, 404); // Reserva no encontrada
    }

    $detalle = [
        'title' => $reserva->nombre_cliente,
        'time' => $reserva->hora_reservacion,
        'description' => $reserva->telefono_cliente,
        'servicio' => $reserva->servicio->nombre
    ];

    return response()->json($detalle);
}


}
