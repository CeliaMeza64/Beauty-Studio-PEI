@extends('layouts.app')

@section('background_image')
{{''}}
@endsection

@section('content')
<div class="container">
    <h1>Tendencias</h1>

    @if(Auth::check() && Auth::user()->role == 'admin')
        <a href="{{ route('trends.index') }}" class="btn btn-primary mb-3">Volver a la lista</a>
    @endif

    <div class="row">
        @forelse($trends as $trend)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100" data-bs-toggle="modal" data-bs-target="#trendModal-{{ $trend->id }}">
                    @if ($trend->image && $trend->image !== 'noimage.jpg')
                        <img src="{{ asset('storage/trends_images/' . $trend->image) }}" alt="{{ $trend->title }}" class="card-img-top img-fluid rounded">
                    @else
                        <img src="ruta/a/imagen/placeholder.jpg" alt="Imagen no disponible" class="card-img-top img-fluid rounded">
                    @endif
                    <div class="card-body">
                        <h2 class="card-title">{{ $trend->title }}</h2>
                        <div class="description">
                            <p class="card-text">{!! nl2br(e(Str::limit($trend->description, 100))) !!}</p>
                        </div>
                        <div class="text-muted mt-2">
                            <small>Creado el: {{ \Carbon\Carbon::parse($trend->created_at)->locale('es')->translatedFormat('l, d \d\e F \d\e Y \a \l\a\s H:i') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="trendModal-{{ $trend->id }}" tabindex="-1" aria-labelledby="trendModalLabel{{ $trend->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="trendModalLabel{{ $trend->id }}">{{ $trend->title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if ($trend->image && $trend->image !== 'noimage.jpg')
                                <img src="{{ asset('storage/trends_images/' . $trend->image) }}" alt="{{ $trend->title }}" class="img-fluid mb-3">
                            @else
                                <img src="ruta/a/imagen/placeholder.jpg" alt="Imagen no disponible" class="img-fluid mb-3">
                            @endif
                            <p>{!! nl2br(e($trend->description)) !!}</p>
                            <div class="text-muted mt-2">
                                <small>Creado el: {{ \Carbon\Carbon::parse($trend->created_at)->locale('es')->translatedFormat('l, d \d\e F \d\e Y \a \l\a\s H:i') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>Lo sentimos, no hay tendencias disponibles en este momento.</p>
        @endforelse
    </div>
</div>

<style>
    .description {
        max-height: 5.5em; 
        overflow: hidden;
        position: relative;
    }

    .card {
        cursor: pointer;
    }

    .modal-body img {
        max-width: 100%;
        height: auto;
        margin-bottom: 1rem;
    }
</style>
@endsection
