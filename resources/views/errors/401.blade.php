@extends('layouts.app')
@section('background_image')
{{ '' }}
@endsection

@section('title', 'No Autorizado')

@section('content')
<div class="home-page">
    <h1>Oops!</h1>
    <h2>Error {{ $code }}</h2>
    <p>{{ $message }}</p> 
</div>
@endsection


