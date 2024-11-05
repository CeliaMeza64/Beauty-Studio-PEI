@extends('layouts.errores')

@section('title', 'Error del Servidor')

@section('content')
<div class="error-page">
<h1>{{ $code }}</h1> 
<p>{{ $message ??Algo salió mal en el servidor. Estamos trabajando para solucionarlo.}}</p>
    <a href="{{ url('/') }}">Volver a la página de inicio</a>
</div>
@endsection
