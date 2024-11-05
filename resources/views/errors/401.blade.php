@extends('layouts.errores')

@section('title', 'No Autorizado')

@section('content')
<div class="error-page">
    <h1>401</h1>
    <p>No estás autorizado para acceder a esta página. Por favor, inicia sesión y vuelve a intentarlo.</p>
    <a href="{{ route('login') }}">Iniciar Sesión</a>
</div>
@endsection
