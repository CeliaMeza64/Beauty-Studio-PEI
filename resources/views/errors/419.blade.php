@extends('layouts.errores')

@section('title', 'Página Expirada')

@section('content')
<div class="error-page">
<h1>{{ $code }}</h1> 
<p>{{ $message ??La sesión ha expirado. Por favor, recarga la página e intenta nuevamente.}}</p>
    <a href="{{ url()->previous() }}">Volver a la página anterior</a>
</div>
@endsection
