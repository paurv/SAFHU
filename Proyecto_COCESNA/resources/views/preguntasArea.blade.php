@extends('plantillas.dashboard')

@section('tituloDashboard')
  <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
    <h1 class="h3 mb-0 text-gray-800">Preguntas del área "{{ $nombreArea }}"</h1>
    <div>
      <a role="button" class="d-none d-sm-inline-block btn btn-sm btn-regresar shadow-sm" onclick=" window.location = '{{ route('administrador.principal') }}';"><i class="fas fa-arrow-alt-circle-left"></i> Regresar</a>
      <a role="button" class="d-none d-sm-inline-block btn btn-sm btn-area-preg shadow-sm" data-toggle="modal" data-target="#modalAgregarPregunta"><i class="far fa-file-alt mr-1"></i>Agregar Preguntas</a>
    </div>
  </div>
  <hr>    
@endsection

@section('contenido')
@endsection

@section('modalPrincipal')
  <!-- Modal agregar pregunta -->
  <div class="modal fade" id="modalAgregarPregunta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Agregar Preguntas</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="pregunta" class="col-form-label">Pregunta:</label>
              <input type="text" class="form-control" id="pregunta" pattern="[-+/*]">
              <div id="valida-pregunta"></div>
            </div>
            <div class="row">
              <h5 class="ml-4 mb-4 mr-4 col-form-label">Tipo de respuesta:</h5>
              <select id="inputState" class="form-tipo col-5">
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="validar();">Guardar Cambios</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal de editar pregunta --> 
  <div class="modal fade" id="modalEditarPregunta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modificar Pregunta</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="pregunta" class="col-form-label">Pregunta:</label>
              <input type="text" class="form-control" id="pregunta-editar">
              <div id="valida-pregunta-editar"></div>
            </div>
            <div class="row">
              <h5 class="ml-4 mb-4 mr-4 col-form-label">Tipo de respuesta:</h5>
              <select id="inputState2" class="form-tipo col-6">
              </select>
            </div>
            <input type="hidden" id="id-pregunta-editar" value="">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="validarEditar();">Guardar Cambios</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal eliminar pregunta -->
  <div class="modal fade" id="modalEliminarPregunta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Eliminar Pregunta</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modal-eliminar-contenido">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-danger" onclick="validarElminar();">Eliminar</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    var AJAX = {
      idArea:"{{ $id }}",
      rutaMostrarTiposRespuestas: "{{ route('encuesta.tiposDeRespuesta') }}",
      rutaMostrarRespuestasDelTipo: "{{ route('tipoRespuesta.elementos') }}",
      rutaMostrarPreguntas: "{{ route('area.preguntasAJAX') }}",
      rutaAgregarPreguntas: "{{ route('administrador.agregarPregunta') }}",
      rutaActualizarPreguntas: "{{ route('administrador.actualizarPregunta') }}",
      rutaEliminar: "{{ route('pagina.preguntas') }}",
    }
  </script>
  <script src="{{ asset('vendor/js/mainPreguntas.js') }}"></script>
@endsection