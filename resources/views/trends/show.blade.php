@extends('layouts.app')

@section('background_image')
{{ '' }}
@endsection

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-5">Servicios en Tendencia</h1>

    <div class="row justify-content-center">
        @foreach($trends as $index => $trend)
            <div class="col-lg-8 col-md-10 mb-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h2 class="text-center mb-4 text-primary">Top {{ $index + 1 }}: {{ $trend->nombre }}</h2>

                        <div id="carouselServicio{{ $trend->id }}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner rounded">
                                @foreach($trend->images as $i => $imagen)
                                    <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $imagen->path) }}" class="d-block w-100" alt="{{ $trend->nombre }}">
                                    </div>
                                @endforeach
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselServicio{{ $trend->id }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselServicio{{ $trend->id }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>

                        <p class="text-center mt-3 mb-0"><strong>Reservas en el mes:</strong> {{ $trend->reservas_count }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .carousel-item img {
        max-height: 500px;
        object-fit: cover;
        border-radius: 8px;
    }
    .card {
        background-color: #f9f9f9;
        border-radius: 12px;
    }
    h2.text-primary {
        color: #0056b3;
    }
</style>
@endsection
