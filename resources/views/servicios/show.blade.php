@extends('layouts.app')

@section('background_image')
{{''}}
@endsection

@section('content')
<div class="container">
    @if(Auth::check() && Auth::user()->role == 'admin')
        <div class="mb-3 d-flex justify-content-start">
            <a href="{{ route('servicios.showServicios', ['categoriaN' => $categoriaN]) }}" class="btn btn-secondary">Regresar</a>
         
        </div>
    @endif

    <a href="{{ route('serviciosImagen.show', $servicio->id) }}" class="text-decoration-none">
        <div class="card mb-4">
            <div class="row no-gutters">
                <div class="col-md-4">
                    @if ($servicio->imagen)
                        <img src="{{ asset('storage/' . $servicio->imagen) }}" alt="Imagen del servicio" class="img-fluid" style="max-width: 100%; height: auto;">
                    @else
                        <img src="ruta/a/imagen/placeholder.jpg" alt="Imagen no disponible" class="img-fluid" style="max-width: 100%; height: auto;">
                    @endif
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">{{ $servicio->nombre }}</h5>
                        <p class="card-text">{!! nl2br(e($servicio->descripcion)) !!}</p>
                        <p class="card-text"><strong>Categoría:</strong> {{ $servicio->categoria->nombre }}</p>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>
@endsection
