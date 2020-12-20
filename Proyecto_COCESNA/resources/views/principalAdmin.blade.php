@extends('plantillas.dashboard')

@section('tituloDashboard')
  <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
    <h1 class="h3 mb-0 text-gray-800">Áreas De Preguntas</h1>
    <div>
      <a role="button" class="d-none d-sm-inline-block btn btn-sm btn-regresar shadow-sm" data-toggle="modal" data-target="#modalEditarPreguntaFiltro"><i class="far fa-edit"></i> Editar pregunta filtro</a>
      <a role="button" class="d-none d-sm-inline-block btn btn-sm btn-area-preg bg-cos bg-cos-list shadow-sm" data-toggle="modal" data-target="#modalAgregarPregunta"><i class="far fa-file-alt mr-1"></i>Crear Área de Preguntas</a>  
    </div>
  </div>
  <hr>
@endsection

@section('contenido')

  @foreach ($areas as $area)

    <div class="col-12 col-lg-6 col-xl-4"  style="z-index:1;">
      <div class="card card-style mb-3">
        <div  id="{{ $area->id_area }}"  onclick="mostrarPreguntas(this)">
          <div class="card-header"><span class="mr-1 titulo-enc">
            Encuesta:</span>{{ $area->nombre }}
          </div>
          <div class="card-body" >
            <h5 class="card-title descripcion-enc">Descripción</h5>
            <p class="card-text">{{ $area->descripcion }}</p>
          </div>
        </div>
        <div class="card-footer d-flex justify-content-center">
          <button type="button" class="btn bg-cos bg-cos-list mb-2 mr-3"  onclick="editar({{ $area->id_area }},'{{ $area->nombre }}','{{ $area->descripcion }}');" data-toggle="modal" > Editar </button>
          <button type="button" class="btn bg-cos-gray mb-2 mr-3"  onclick="eliminar({{ $area->id_area }});" data-toggle="modal" > Eliminar </button>
        </div>
      </div>
    </div>

  @endforeach


  @if (count($areas) == 0)
    <h4 class="ml-5">Sin áreas aún</h4>
  @endif

@endsection

@section('modalEliminar')
  <!-- Modal -->
  <div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div  id="contenido-modal" class="modal-body">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="button"  onclick="confirmarEliminar()"  class="btn btn-danger">Eliminar</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('modalPrincipal')
  <div class="modal fade" id="modalAgregarPregunta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Crear Área de Preguntas</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="recipient-name" class="col-form-label">Nombre:</label>
              <input type="text" class="form-control" id="nombre">
              <div id="valida-nombre"></div>
            </div>
            <div class="form-group">
              <label for="message-text" class="col-form-label">Descripcion:</label>
              <textarea class="form-control" id="descripcion"></textarea>
              <div id="valida-descripcion"></div>
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
  <div class="modal fade" id="modalEditarPregunta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Editar Área de Preguntas</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="recipient-name" class="col-form-label">Nombre:</label>
              <input type="text" class="form-control" id="nombre-editar">
              <div id="valida-nombre-editar"></div>
            </div>
            <div class="form-group">
              <label for="message-text" class="col-form-label">Descripcion:</label>
              <textarea class="form-control" id="descripcion-editar"></textarea>
              <div id="valida-descripcion-editar"></div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="confirmarEditar();">Actualizar</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modalEditarPreguntaFiltro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Editar Pregunta Filtro</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="preguntaF-editar" class="col-form-label">Pregunta filtro:</label>
              <input type="text" class="form-control" id="preguntaF-editar" value="{{ $PreguntaF }}">
              <div id="valida-preguntaF-editar"></div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="confirmarEditarPF();">Actualizar</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    var rutas = {
      AgregarArea:"{{ route('administrador.agregarArea') }}",
      MostrarPreguntas: "{{ route('pagina.preguntas') }}",
      principalAdmin: "{{ route('administrador.principal') }}", 
      editarArea: "{{ route('administrador.actualizarArea') }}",
      editarPreguntaFiltro: "{{ route('administrador.actualizarPF') }}",
    }
  </script>
@endsection