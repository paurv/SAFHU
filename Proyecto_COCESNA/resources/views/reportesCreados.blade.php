@extends('plantillas.dashboard')

@section('tituloDashboard')
  <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
    <h1 class="h3 mb-0 text-gray-800">Reportes Generales</h1>
    {{-- <div>
        <a role="button" class="d-none d-sm-inline-block btn btn-sm btn-regresar shadow-sm" onclick=" window.location = '{{ route('administrador.principal') }}';"><i class="fas fa-arrow-alt-circle-left"></i> Regresar</a>
    </div> --}}
  </div>
  <hr>
@endsection

@section('contenido')
  <div class="mb-5 mx-auto">
    <form class="form-inline"  action="{{ route('reportes.mostrar') }}">
      <span class="form-inline">
        Desde: <input class="form-control mr-3 ml-2" type="date" name="fecha_inicio" value="{{ Session::get('fecha_inicio') }}">
      </span>
      <span class="form-inline">
        Hasta: <input class="form-control mr-3 ml-2" type="date" name="fecha_fin" value="{{ Session::get('fecha_fin') }}">
      </span>
      <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-regresar shadow-sm"><i class="fas fa-sync"></i> Actualizar</button>
    </form>
  </div>

  <div class="container">
    {{-- <div class="row mb-4">
      <div class="col-12">
        {!! $chart1->container() !!}
      </div>
    </div>
    <div class="row mb-4">
      <div class="col-12">
        {!! $chart2->container() !!}
      </div>
    </div>
    <br>
    <div class="row mb-4">
      <div class="col-12">
        {!! $chart3->container() !!}
      </div>
    </div>
    <div class="row mb-4">
      <div class="col-12">
        {!! $chart4->container() !!}
      </div>
    </div> --}}    
    
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
  <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
  -->
  <script src="{{ asset('vendor/js/chart.js') }}" charset="utf-8"></script>
  {{-- {!! $chart1->script() !!}
  {!! $chart2->script() !!}
  {!! $chart3->script() !!}
  {!! $chart4->script() !!} --}}

  @foreach ($reportes as $reporte)
    {!! $reporte->script() !!}
  @endforeach
@endsection