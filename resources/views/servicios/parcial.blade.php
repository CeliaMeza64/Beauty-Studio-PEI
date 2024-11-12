@foreach($servicios as $servicio)
    <tr>
        <td>{{($servicios->currentPage() - 1) * $servicios->perPage() + $loop->iteration}}</td>
        <td>{{ $servicio->nombre }}</td>
        <td><div class="truncate">{{ $servicio->descripcion }}</div></td>
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
        <td class="text-center">{{ $servicio->duracion }}</td>
        <td>
            <a href="{{ route('servicios.edit', $servicio->id) }}" class="btn btn-success btn-sm" title="Editar">
                <i class="fas fa-edit"></i>
            </a>
            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                    data-target="#eliminarModal_{{ $servicio->id }}" title="Eliminar">
                <i class="fas fa-trash-alt"></i>
            </button>
            
            <div class="modal fade" id="eliminarModal_{{ $servicio->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Eliminar Servicio</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        ¿Realmente quieres eliminar el servicio: {{ $servicio->nombre }}?
                        </div>
                        <div class="modal-footer">
                            <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST">
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
@if($servicios->isEmpty())
    <tr>
        <td colspan="8" class="text-center">No hay resultados para su búsqueda</td>
    </tr>
@endif