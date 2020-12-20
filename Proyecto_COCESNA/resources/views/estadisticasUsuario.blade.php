@extends('plantillas.dashboard')

@section('tituloDashboard')
  <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
    <h1 class="h3 mb-0 text-gray-800">Estadísticas del Usuario {{ $estadisticaEmpleado }}</h1>
    <div>
        <a role="button" class="d-none d-sm-inline-block btn btn-sm btn-regresar shadow-sm" onclick=" window.location = '{{ route('usuarios.mostrar') }}';"><i class="fas fa-arrow-alt-circle-left"></i> Regresar</a>
    </div>
  </div>
  <hr>
@endsection

@section('contenido')

<div class="mt-2 mx-auto">
  <form class="form-inline"  action="{{ route('controlador.estadisticas') }}">
    <span class="form-inline">
      Desde: <input class="form-control mr-3 ml-2" type="date" name="fecha_inicio" value="{{ Session::get('fecha_inicio') }}">
    </span>
    <span class="form-inline">
      Hasta: <input class="form-control mr-3 ml-2" type="date" name="fecha_fin" value="{{ Session::get('fecha_fin') }}">
    </span>
    <input name="estadisticaEmpleado" type="hidden" value="{{ $estadisticaEmpleado }}">
    <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-regresar shadow-sm"><i class="fas fa-sync"></i> Actualizar</button>
  </form>
</div>

<div class="container">
  <div class="row">
    <div class="col-12 col-md-6">
      <br><br>
      <table class="table table-bordered"> 
        <tbody>
          <tr>
            <th scope="row">Nombre</th>
            <td>{{ $usuario->nombres.' '.$usuario->apellidos }}</td>
          </tr>
          <tr>
            <th scope="row">Edad</th>
            <td>{{ $edad }} años</td>
          </tr>
          <tr>
            <th scope="row">Sexo</th>
            <td colspan="2">{{ $usuario->sexo }}</td>
          </tr>
          <tr>
            <th scope="row">Años de Laborar</th>
            <td colspan="2">{{ $tiempoTrabajando }} años</td>
          </tr>
          <tr>
            <th scope="row">Preguntas Respondidas</th>
            <td colspan="2">{{ $preguntasRespondidas }}</td>
          </tr>
          <tr>
            <th scope="row">Áreas Respondidas</th>
            <td colspan="2">{{ $areasRespondidas }}</td>
          </tr>
          <tr>
            <th scope="row">Correos Enviados</th>
            <td colspan="2">{{ $correosEnviados }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    @foreach ($reportes as $reporte)

      {{-- <div class="row mb-4"> --}}
        <div class="col-12 col-md-6 mt-5">
          {!! $reporte->container() !!}
        </div>
      {{-- </div> --}}

    @endforeach
  </div>
</div>

@endsection
@section('scripts')

  <script src="{{ asset('vendor/js/chart.js') }}" charset="utf-8"></script>

  @foreach ($reportes as $reporte)

    {!! $reporte->script() !!}

  @endforeach

@endsection