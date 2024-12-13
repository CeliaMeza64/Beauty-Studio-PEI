@foreach($reservas as $reserva)
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
    <td>
        <a href="{{ route('reservas.edit', $reserva) }}" class="btn btn-success btn-sm mr-2">
            <i class="fas fa-edit"></i>
        </a>
        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#eliminarModal_{{ $reserva->id }}" title="Eliminar">
            <i class="fas fa-trash"></i>
        </button>

        <!-- Modal de eliminación -->
        <div class="modal fade" id="eliminarModal_{{ $reserva->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Eliminar Reserva</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ¿Realmente quieres eliminar la reserva de {{ $reserva->nombre_cliente }} para el {{ \Carbon\Carbon::parse($reserva->fecha_reservacion)->format('d/m/Y') }}?
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('reservas.destroy', $reserva) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </td>
</tr>
@endforeach

@if($reservas->isEmpty())
<tr>
    <td colspan="10" class="text-center">No hay resultados para su búsqueda</td>
</tr>
@endif
