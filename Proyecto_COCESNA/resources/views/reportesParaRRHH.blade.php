@extends('plantillas.controlador')

@section('tituloDashboard')
  <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
    <h1 class="h3 mb-0 text-gray-800">Reportes</h1>
    <a role="button" class="d-none d-sm-inline-block btn btn-sm btn-regresar shadow-sm" onclick=" window.location = '{{ route('administrador.principal') }}';"><i class="fas fa-arrow-alt-circle-left"></i> Regresar</a>
  </div>
  <hr>
@endsection

@section('nombreControlador')
  <li class="nav-item">
    <a class="nav-link" href="{{ route('sistema.inicio') }}"><i class="fas fa-door-open"></i> Salir</a>
  </li>
@endsection

@section('contenido')
<div class="mb-5 mx-auto">
  <form class='form-inline' action="{{ route('reportes.mostrar') }}">
    <input class="form-control mr-3" type="date" name="fecha_inicio" value="{{ Session::get('fecha_inicio') }}">
    <input class="form-control mr-3" type="date" name="fecha_fin" value="{{ Session::get('fecha_fin') }}">
    <button type="submit" class="d-none d-sm-inline-block btn btn-regresar shadow-sm"> Actualizar </button>
  </form>
</div>

<div class="container">
  
  @foreach ($reportes as $reporte)
    <div class="row mb-4">
      <div class="col-12">
        {!! $reporte->container() !!}
      </div>
    </div>
  @endforeach

</div>

@endsection

@section('scripts')
  <script src="{{ asset('vendor/js/chart.js') }}" charset="utf-8"></script>

  @foreach ($reportes as $reporte)
    {!! $reporte->script() !!}
  @endforeach
@endsection