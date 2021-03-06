{{--  todo esto se Modifico  --}}
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <script src="https://kit.fontawesome.com/08f90d9e82.js"></script>
    <title>COCESNA</title>
    <link rel="shortcut icon" href="{{ asset('img/icon.png') }}" type="image/x-icon">
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"><!-- Bootstrap core CSS -->
    <link href="{{ asset('css/simple-sidebar.css') }}" rel="stylesheet"><!-- Custom styles for this template -->
    <link rel="stylesheet" href="{{ asset('css/crear-areas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/preguntas-css.css') }}">
    <link rel="stylesheet" href="{{ asset('css/areas.css') }}">
    <script src="{{ asset('vendor/FontAwesome/js/all.js') }}"></script>
    @yield('head')
  </head>

  <body>
    <div class="d-flex" id="wrapper">

      <!-- Page Content -->
      <div id="page-content-wrapper">

        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom" id="navbar">

          <!-- <button class="btn btn-primary" id="menu-toggle">Ocultar</button> -->
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
              <li class="nav-item active text-primary">
                @yield('nombreControlador')
              </li>
            </ul>
          </div>

        </nav>

        <div class="container-fluid">
          @yield('tituloDashboard')
          <div class="row" id="areaPreguntas" style="overflow-y:auto; overflow-x:hidden; max-height: 400px;">
            @yield('contenido')
          </div>
          @yield('modalEliminar')
        </div>
      </div>

    <!-- /#page-content-wrapper -->
    </div>
    @yield('modalPrincipal')
    
    <!-- Bootstrap core JavaScript -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/js/main.js') }}"></script>

    <!-- Menu Toggle Script -->
    <script>
      $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
      });
      $(document).ready(function(){
        $('#areaPreguntas').css('max-height',document.body.scrollHeight-36);
      });
    </script>
    @yield('scripts')

  </body>
</html>
