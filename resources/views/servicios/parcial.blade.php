@foreach($servicios as $servicio)
    <tr>
        <td>{{($servicios->currentPage() - 1) * $servicios->perPage() + $loop->iteration}}</td>
        <td>{{ $servicio->nombre }}</td>
        <td><div class="truncate">{{ $servicio->descripcion }}</td>
        <td>{{ $servicio->categoria->nombre ?? 'No Asignada' }}</td>
        <td>{{ $servicio->disponibilidad ? 'Disponible' : 'No Disponible' }}</td>
        <td>
            @if ($servicio->imagen)
                <img src="{{ asset('storage/' . $servicio->imagen) }}"
                     alt="Imagen" style="width: 50px; height: 50px; object-fit: cover;">
            @else
                No hay imagen
            @endif
        </td>
        <td>{{ $servicio->duracion }}</td>
        <td>
            <a href="{{ route('servicios.edit', $servicio->id) }}" class="btn btn-success btn-sm" title="Editar">
                <i class="fas fa-edit"></i>
            </a>
            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                    data-target="#eliminarModal_{{ $servicio->id }}" title="Eliminar">
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
    </tr>
@endforeach

@if($servicios->isEmpty())
    <tr>
        <td colspan="8" class="text-center">No hay resultados para su búsqueda</td>
    </tr>
@endif
