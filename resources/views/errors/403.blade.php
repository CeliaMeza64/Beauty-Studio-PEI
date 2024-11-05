@extends('layouts.errores')

@section('title', 'Acceso Prohibido')

@section('content')
<div class="error-page">
<h1>{{ $code }}</h1> 
<p>{{ $message ??No tienes permiso para acceder a esta página.}}</p>
    <a href="{{ url('/') }}">Volver a la página de inicio</a>
</div>
@endsection
