@extends('plantillas.dashboard')


@section('tituloDashboard')
  <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
    <h1 class="h3 mb-0 text-gray-800">Registro de Actividad</h1>
    <div>
      <!-- <a role="button" class="d-none d-sm-inline-block btn btn-sm btn-regresar shadow-sm" data-toggle="modal" data-target="#modalNuevaEncuesta"><i class="far fa-plus-square"></i>  Nueva oportunidad de encuesta</a> -->
      <!-- <a role="button" class="d-none d-sm-inline-block btn btn-sm btn-area-preg shadow-sm" data-toggle="modal" data-target="#modalAgregarUsuario"><i class="far fa-file-alt mr-1"></i> Agregar Usuario</a> -->
    </div>
  </div>
  <hr>
@endsection

@section('contenido')
    
<table class="table mx-2" style="font-size: 13px;">
    <thead class="thead-dark">
      <tr>
        <th scope="col">Fecha</th>
        <th scope="col">Hora</th>
        <th scope="col">Código Usuario</th>
        <th scope="col">Usuario</th>
        <th scope="col">Detalle</th>
        <th scope="col">LLave</th>
        <th scope="col">Tabla</th>
        <th scope="col">Acción</th>
        <th scope="col">Comando</th>
        <th scope="col">Dirección Ip</th>
      </tr>
    </thead>
    <tbody id="tbl-registros">
        @forelse ($registros as $registro)
        <tr>
        <th> {{ $registro->SegLogFecha}}</th>
        <th> {{ $registro->SegLogHora}}</th>
        <th> {{ $registro->SegUsrKey}}</th>
        <th> {{ $registro->SegUsrUsuario}}</th>
        <th> {{ $registro->SegLogDetalle}}</th>
        <th> {{ $registro->SegLogLlave}}</th>
        <th> {{ $registro->SegLogTabla}}</th>
        <th> {{ $registro->SegLogAccion}}</th>
        <th> {{ $registro->SegLogComando}}</th>
        <th> {{ $registro->SegLogIp}}</th>
        </tr>
        @empty
        <tr>
            <td colspan="10">
              <h1> No hay  registros</h1>
            </td>
        </tr>
        @endforelse
    
    </tbody>
    </table>
@endsection
