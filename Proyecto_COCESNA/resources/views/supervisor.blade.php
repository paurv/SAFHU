@extends('plantillas.encuesta')

@section('head')
  <link rel="stylesheet" href="{{ asset('css/preguntas-css.css') }}">
@endsection

@section('cuerpoPagina')
  <div id='container'>
    <button type="button" id="contras" class="btn btn-outline-secondary" data-toggle="tooltip" data-placement="bottom" title="Cambiar contraseña" onclick="cambioClave({{ Session::get('noEmpleado') }});"><i class="fas fa-wrench"></i></button>
    <div id="envolver">
      <div id='title' class="mt-4">
        <h1 class="titulo-enc" style="color: gray"><i class="fas fa-tasks"></i></h1>
        <h1 class="titulo-enc">¿Desea contestar encuesta de controlador?</h1>
        <h5 class="d-flex justify-content-center" style="color: gray; font-weight: 400; font-size: 20px;">Dar clic en "aceptar" para realizar una encuesta.</h5>	
      </div>
        <br/>
      <div class="col-12 d-flex justify-content-center mb-4">
        <a role="button" href="{{ route('sistema.inicio') }}" class="btn btn-lg btn-secondary m-2">Salir</a>
          <button type="button" class="btn btn-lg btn-primary m-2" data-toggle="modal" data-target="#exampleModal">Aceptar</button>
      </div>
    </div>
    

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Responder Encuestas</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <form id="FormularioLlenarEncuesta" action="{{ route('llenar.encuesta') }}">
                <div class="form-group">
                  <label for="slc-controlador">Seleccione el controlador del cual llenará la encuesta</label>
                  <select name="noEmpleado" class="form-control" id="slc-controlador">
                    @foreach ($numerosDeEmpleados as $numero)
                    
                      <option>{{ $numero->no_empleado.' '.$numero->nombres.' '.$numero->apellidos }}</option>
    
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="txta-razon">Razón por la que se está llenando la encuesta</label>
                  <textarea name="razon" class="form-control" id="txta-razon" rows="3"></textarea>
                </div>
              </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" onclick="llenarEncuesta();">Iniciar</button>
          </div>
        </div>
      </div>
    </div>
  </div>		

  <div class="modal fade" id="modalCambioClave" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Cambio de clave</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="form-clav">
                <div class="form-group">
                    <label for="exampleFormControlInput1" class="label-sty">Nueva Clave</label>
                    <div class="input-group" id="input-clave">
                        <input type="password" class="form-control" id="con1" onkeyup="val(this)" placeholder="contraseña">
                        <div class="input-group-btn">
                            <!-- <div class="input-btn-most">@</div> -->
                            <button type="button" class="input-btn-most" id="vc"><i class="far fa-eye-slash"></i></button>
                        </div>
                        <!-- <div class="invalid-feedback" id="val-feed">
                          La contraseña debe contener al menos una mayuscula una minuscula un numero y un caracter especial(!@#$%&*,.?)
                        </div> -->
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1" class="label-sty">Confirmar Clave</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="con2" onkeyup="val(this)" placeholder="contraseña">
                        <div class="input-group-btn">
                            <!-- <div class="input-btn-most">@</div> -->
                            <button type="button" class="input-btn-most" id="vcc"><i class="far fa-eye-slash"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="btn-env" onclick="cambiarContrasena('{{ route('usuario.cambiarContrasena') }}');">Confirmar</button>
          <!-- <button type="button" class="btn btn-primary mb-2" id="btn-env">Confirmar</button> -->
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    var variables = {
      contrasenaCorreo: "{{ route('usuario.recuperar') }}",
      rutaEncuesta: "{{ route('llenar.encuesta') }}",
		}
  </script>
@endsection