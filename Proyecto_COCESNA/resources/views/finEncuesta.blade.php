@extends('plantillas.encuesta')

@section('cuerpoPagina')
  <div id='container'>
    <div id="envolver">
      <div id="title" class="mt-4">
        <h1 class="titulo-enc" id="titulo-enc">{{ $mensaje0 }}</h1>
      </div>
      <br>        
      <div id="quiz" style="display: block;"><p id="cuestmsj">{{ $mensaje1 }}<br>{{ $mensaje2 }}</p></div>
    </div>
  </div>
@endsection

@section('scripts')
  {{-- 
    el siguiente script permite que 
    la pagina se redireccione a la vista 
    "iniciarSesion" luego de terminar 
    la encuesta 
  --}}
  <script> 
    var home = '{{ route("sistema.inicio") }}';
    setTimeout(function(){
      window.location = home;
    }, {{ $retardo }}); //dentro de 2 o 4 segundos redirecciona 
    var variables = {};
  </script>
@endsection
