@extends('layouts.app')

@section('background_image')
{{ '' }}
@endsection

@section('content')
<div class="container">
    <h1>Servicios en Tendencia</h1>

    <div class="row">
        @foreach($trends as $index => $trend)
            <div class="col-md-12 mb-4">
                <h2 class="text-center">Top {{ $index + 1 }}: {{ $trend->nombre }}</h2>

                <!-- Carrusel del servicio en tendencia -->
                <div id="carouselServicio{{ $trend->id }}" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($trend->imagenes as $i => $imagen)
                            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/imagenes/' . $imagen->ruta) }}" class="d-block w-100" alt="{{ $trend->nombre }}">
                            </div>
                        @endforeach
                    </div>

                    <!-- Controles del carrusel -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselServicio{{ $trend->id }}" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselServicio{{ $trend->id }}" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>

                <p class="text-center mt-3"><strong>Reservas en el mes:</strong> {{ $trend->reservas_count }}</p>
            </div>
        @endforeach
    </div>
</div>

<style>
    .carousel-item img {
        max-height: 500px;
        object-fit: cover;
    }
</style>
@endsection
