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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route('reservas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Reserva
                    </a>

                    <input type="text" id="search" class="form-control" style="width: 500px;" placeholder="Buscar reservas por cliente o fecha">
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Teléfono</th>
                                <th>Servicios</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Duración</th>
                                <th>Hora Fin</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="resultados">
                            @include('reservas.parcial', ['reservas' => $reservas])
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-3" id="pagination-links">
                        {{ $reservas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
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
                    url: "{{ route('reservas.buscar') }}",
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
