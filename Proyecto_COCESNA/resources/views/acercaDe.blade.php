@extends('plantillas.dashboard')

@section('tituloDashboard')
  <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
    <h1 class="h3 mb-0 text-gray-800">Sistema de Automatización de Factores Humanos</h1>
    {{-- <div>
        <a role="button" class="d-none d-sm-inline-block btn btn-sm btn-regresar shadow-sm" onclick=" window.location = '{{ route('administrador.principal') }}';"><i class="fas fa-arrow-alt-circle-left"></i> Regresar</a>
    </div> --}}
  </div>
  <hr>
@endsection

@section('contenido')
  <div class="container">
    <div class="mb-3">
      <b>Equipo de desarrollo:</b>  
    </div> 
    <table>
      <tbody>
        <tr>
          <td>Alejandro Jossué Claros &nbsp;&nbsp;&nbsp;</td>
          <td class="text-info">alejandrocontreras.ac83@gmail.com</td>
        </tr>
        <tr>
          <td>Ana Paula Rivera</td>
          <td class="text-info">ana.posadasr97@gmail.com</td>
        </tr>
        <tr>
          <td>Luis Armando Tejada</td>
          <td class="text-info">luartemu@gmail.com</td>
        </tr>
      </tbody>
    </table>
    <br>
    Versión 1.0
  </div>
@endsection