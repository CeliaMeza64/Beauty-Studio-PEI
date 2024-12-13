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
        <form action="{{ route('reservas.destroy', $reserva) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </td>
</tr>
@endforeach

@if($reservas->isEmpty())
<tr>
    <td colspan="10" class="text-center">No hay resultados para su búsqueda</td>
</tr>
@endif
