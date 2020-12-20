<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://kit.fontawesome.com/08f90d9e82.js"></script>
    <link rel="shortcut icon" href="{{ asset('img/icon.png') }}" type="image/x-icon">
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"><!-- Bootstrap core CSS -->
    
    {{-- @yield permite marcar las partes donde las vistas modifican el contenido de la plantilla --}}
    <title>@yield('tituloPagina')</title> 
    
    {{--Remueve las flechas que traen por defecto los <inputs type="number">--}}
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance:textfield;
        }
    </style>
    
    {{-- Si hay mas elementos de <head> los llamaremos aqui --}}
    @yield('head')

</head>
<body>

    {{--Dentro de este campo se incluye el contenido que lleva la etiqueta <body>--}}
    @yield('cuerpoPagina')

    {{--Se agregan los scripts de bootstrap y jquery--}}
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    {{--En este campo se agregan mas scripts--}}
    @yield('scripts')

</body>
</html>


