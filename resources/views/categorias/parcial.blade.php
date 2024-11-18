@foreach($categorias as $categoria)
    <tr>
        <td>{{ ($categorias->currentPage() - 1) * $categorias->perPage() + $loop->iteration }}</td>
        <td>{{ $categoria->nombre }}</td>
        <td><div class="truncate">{{ $categoria->descripcion }}</div></td>
        <td>{{ $categoria->estado ? 'Activo' : 'Inactivo' }}</td>
        <td>
            @if ($categoria->imagen)
                <img src="{{ asset('storage/' . $categoria->imagen) }}" alt="Imagen" style="width: 50px; height: 50px; object-fit: cover;">
            @else
                No hay imagen
            @endif
        </td>
        <td>
            <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-success btn-sm" title="Editar">
                <i class="fas fa-edit"></i>
            </a>
            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#eliminarModal_{{ $categoria->id }}" title="Eliminar">
                <i class="fas fa-trash-alt"></i>
            </button>
            <div class="modal fade" id="eliminarModal_{{ $categoria->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Eliminar Categoría</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ¿Realmente quieres eliminar la categoría: {{ $categoria->nombre }}?
                        </div>
                        <div class="modal-footer">
                            <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST">
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
@if($categorias->isEmpty())
    <tr>
        <td colspan="6" class="text-center">No hay resultados para su búsqueda</td>
    </tr>
@endif
