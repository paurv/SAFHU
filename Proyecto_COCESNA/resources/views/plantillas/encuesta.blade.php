<!-- FALTA INCLUIR LOS DOCUMENTOS DE BOOTSTRAP(QUE NO QUEDEN COMO LINKS) -->
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Encuesta</title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open Sans"/>
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"><!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{ asset('css/encuestaControlador.css') }}">
    @yield('head')
	</head>
	<body>
    @yield('cuerpoPagina')
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
		<script src="{{ asset('vendor/js/all.js') }}"></script>
    @yield('scripts')
    <script src="{{ asset('vendor/js/encuestaControlador.js') }}"></script>
	</body>
</html>
