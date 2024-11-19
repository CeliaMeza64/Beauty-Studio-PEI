@extends('layouts.app')

@section('background_image')
{{ '' }}
@endsection

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-5">Servicios en Tendencia</h1>

    <div class="row justify-content-center">
        @foreach($trends as $index => $trend)
            <div class="col-lg-12 col-md-12 mb-5"> 
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h2 class="text-center mb-4 text-white border-2 p-2 rounded custom-text">
                            <em>Top {{ $index + 1 }}:</em> {{ $trend->nombre }}
                        </h2>

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

                        <p class="text-center mt-3 mb-0">
                            <strong>Reservas en el mes:</strong> 
                            <span class="reservas-count">{{ $trend->reservas_count }}</span>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
<<<<<<< HEAD
    .carousel-inner {
        max-width: 100%; 
    }

    .carousel-item img {
        width: 100%; 
        height: 500px; 
        object-fit: contain; 
        object-position: center; 
        border-radius: 8px; 
    }

=======
    
    .carousel-inner {
        max-width: 100%; 
    }

    .carousel-item img {
        width: 100%; 
        height: 500px; 
        object-fit: contain; 
        object-position: center; 
        border-radius: 8px; 
    }

>>>>>>> 11fe12c4f1abbf130b353bc2aaed56d31c423e2c
    .card {
        border: 2px solid #000;
        border-radius: 12px;
<<<<<<< HEAD
        background-color: #eaeaea;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .custom-text {
        color: #fff; 
        text-shadow: 0 0 3px rgba(255, 0, 127, 0.7), 0 0 5px rgba(255, 0, 127, 0.7); 
        background-color: rgba(255, 0, 127, 0.3); 
        padding: 8px;
        border-radius: 10px;
    }

    .carousel-control-prev-icon, .carousel-control-next-icon {
        background-color: #000;
        border-radius: 50%;
        padding: 10px;
    }

    .reservas-count {
        background-color: rgba(255, 0, 127, 0.3); 
        color: #fff; 
        text-shadow: 0 0 3px rgba(255, 0, 127, 0.7), 0 0 5px rgba(255, 0, 127, 0.7); 
        padding: 5px 10px; 
        border-radius: 8px; 
    }
    
=======
        padding: 0; 
    }

    h2.text-primary {
        color: #0056b3;
    }

 
    .carousel-control-prev-icon, .carousel-control-next-icon {
        background-color: #0056b3;
        border-radius: 50%;
        padding: 10px;
    }
>>>>>>> 11fe12c4f1abbf130b353bc2aaed56d31c423e2c
</style>
@endsection
