@extends('adminlte::page')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Reservas</li> 
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="container">
                @if ($reservas->isEmpty())
                    <p class="text-muted">No hay reservas.</p>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr >
                                <th>#</th>
                                <th>Nombre del Cliente</th>
                                <th>Teléfono del Cliente</th>
                                <th>Servicio</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Duración</th>
                                <th>Hora finalización</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservas as $reserva)
                                <tr>
                                    <td>{{ ($reservas->currentPage() - 1) * $reservas->perPage() + $loop->iteration }}</td>
                                    
                                    <td>{{ $reserva->nombre_cliente }}</td>
                                    <td>{{ $reserva->telefono_cliente }}</td>
                                    <td>
                                        @foreach ($reserva->servicios as $servicio)
                                            {{ $servicio->nombre }}@if (!$loop->last), @endif
                                        @endforeach
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($reserva->fecha_reservacion)->format('d/m/Y') }}</td>
                                    <td>{{ $reserva->hora_reservacion }}</td>
                                    <td>{{ $reserva->duracion }} minutos</td>
                                    <td>{{ $reserva->hora_fin }}</td>
                                    <td>{{ ucfirst($reserva->estado) }}</td>
                                    <td class="d-flex align-items-center">
                                        <a href="{{ route('reservas.edit', $reserva) }}" class="btn btn-success btn-sm mr-2">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminarModal_{{ $reserva->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <div class="modal fade" id="eliminarModal_{{ $reserva->id }}" tabindex="-1" aria-labelledby="eliminarModalLabel_{{ $reserva->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="eliminarModalLabel_{{ $reserva->id }}">Eliminar reserva</h5>
                                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>¿Realmente quieres eliminar la reserva de {{ $reserva->nombre_cliente }}?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <form action="{{ route('reservas.destroy', $reserva) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $reservas->links() }}
                @endif
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .breadcrumb-item a,
        .breadcrumb-item.active {
            font-size: 1.30em;
        }
        .table th,
        .table td {
            vertical-align: middle;
        }
    </style>
@stop

@section('js')

@stop