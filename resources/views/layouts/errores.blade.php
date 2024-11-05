<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Error</title>
    <link rel="icon" href="{{ asset('imagenes/log.png') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            text-align: center;
            padding: 50px;
        }
        h1 {
            font-size: 100px;
            margin: 0;
            color: #FF6EA2;
        }
        h2 {
            font-size: 30px;
            margin: 20px 0;
        }
        p {
            font-size: 20px;
        }
        .btn-home {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #FF6EA2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-home:hover {
            background-color: #e55a8c;
        }
    </style>
</head>
<body>
    <div>
        <h1>Oops!</h1>
        <h2>Error {{ $code ?? 'Error' }}</h2>
        <p>{{ $message ?? 'Un error ha ocurrido.' }}</p> <!-- Mensaje de error aquí -->
        <a href="{{ url('/') }}" class="btn-home">Volver al inicio</a>
    </div>
</body>
</html>
