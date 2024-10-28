@extends('adminlte::page')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Servicios</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route('servicios.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear
                    </a>

                    <input type="text" id="search" class="form-control" style="width: 500px;"
                           placeholder="Buscar servicios por nombre, categoría, duración o disponibilidad">
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Categoría</th>
                                <th>Disponibilidad</th>
                                <th>Imagen</th>
                                <th>Duración</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="resultados">
                            <!-- Aquí se cargará el contenido mediante AJAX -->
                            @include('servicios.parcial', ['servicios' => $servicios])
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-3" id="pagination-links">
                        {{ $servicios->links() }}
                    </div>
                                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
    $('#search').on('keyup', function () {
        let query = $(this).val();
        fetchData(query, 1);
    });


    function fetchData(query = '', page = 1) {
        $.ajax({
            url: "{{ route('servicios.buscar') }}",
            method: 'GET',
            data: { search: query, page: page },
            success: function (data) {
                $('#resultados').html(data.html);
                $('#pagination-links').html(data.pagination); 
            },
            error: function (xhr) {
                console.error('Error en la solicitud:', xhr.responseText);
            }
        });
    }


    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1]; 
        let query = $('#search').val(); 
        fetchData(query, page); 
    });
});

    </script>
@endsection
