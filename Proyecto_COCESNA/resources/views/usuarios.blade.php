@extends('plantillas.dashboard')

@section('tituloDashboard')
  <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
    <h1 class="h3 mb-0 text-gray-800">Usuarios</h1>
    <div>
      <a role="button" class="d-none d-sm-inline-block btn btn-sm btn-llenar bg-cos-gray shadow-sm" data-toggle="modal" data-target="#modalLlenarEncuesta"><i class="fas fa-marker"></i>  Llenar encuesta de un controlador</a>
      <a role="button" class="d-none d-sm-inline-block btn btn-sm btn-regresar shadow-sm" data-toggle="modal" data-target="#modalNuevaEncuesta"><i class="far fa-plus-square"></i>  Nueva oportunidad de encuesta</a>
      <a role="button" class="d-none d-sm-inline-block btn btn-sm btn-area-preg bg-cos-list shadow-sm" data-toggle="modal" data-target="#modalAgregarUsuario"><i class="far fa-file-alt mr-1"></i> Agregar Usuario</a>
    </div>
  </div>
  <hr>
@endsection

@section('contenido')
  <table class="table mx-2">
    <thead class="thead-dark">
      <tr>
        <th scope="col">No.<br> empleado</th>
        <th scope="col">Nombre<br> completo</th>
        <th scope="col">Posición</th>
        <th scope="col">Turno</th>
        <th scope="col">Correo</th>
        <th scope="col">Acción</th>
      </tr>
    </thead>
    <tbody id="tbl-usuarios">
      
      @forelse ($usuarios as $usuario)
        <tr>
          <th scope="row">{{ $usuario->no_empleado }}</th>
          <td>{{ $usuario->nombres }} {{ $usuario->apellidos }}</td>
          <td>{{ $usuario->posicion }}</td>
          <td>{{ $usuario->turno }}</td>
          <td>{{ $usuario->email }}</td>
          <td class="form-inline">
            <button class="btn btn-black mr-1" onclick="editarUsuario({{ $usuario->no_empleado }},'{{ $usuario->email }}','{{ $usuario->posicion }}');"> <i class="far fa-edit"></i></button>
            <button class="btn bg-cos-gray mr-1" onclick="eliminarUsuario({{ $usuario->no_empleado }});"><i class="fas fa-user-minus"></i></button>
            <button class="btn btn-regresar mr-1" onclick="cambioClave({{ $usuario->no_empleado }});"><i class="fas fa-unlock"></i></button>
            <form method="GET" action="{{ route('controlador.estadisticas') }}">
              <button type="submit" class="btn bg-cos bg-cos-list" name="estadisticaEmpleado" value="{{ $usuario->no_empleado }}"><i class="far fa-chart-bar"></i></button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5">
            <h1> No hay Usuarios registrados</h1>
          </td>
        </tr>
      @endforelse

    </tbody>
  </table>

@endsection

@section('modalPrincipal')
  <div class="modal fade" id="modalAgregarUsuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Agregar Usuario</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Seleccione el número de empleado:</label>
            <select type="text" class="form-control" id="noEmpleado-agregar" default="asd">
              @foreach ($id_disponibles as $empleado)
                <option value="{{ $empleado->no_empleado }}">{{ $empleado->no_empleado }}</option>
              @endforeach
            </select>
            <div id="valida-nombre"></div>
          </div>
          <div class="form-group">
            <label for="message-text" class="col-form-label">Correo:</label>
            <input type="text" id="correo-agregar" class="form-control">
            <div id="valida-correo-agregar"></div>
          </div>
          <div class="form-group">
            <label for="posicion-agregar" class="col-form-label">posicion:</label>
            <select name="posicion" class="form-control" id="posicion-agregar">
              @foreach ($posiciones as $posicion)
                <option value="{{ $posicion->id_posicion }}">{{ $posicion->posicion }}</option>
              @endforeach
            </select>
            <div id="valida-posicion-agregar"></div>
          </div>
          <div class="form-group">
            <label for="turno-agregar" class="col-form-label">Turno:</label>
            <select name="turno" class="form-control" id="turno-agregar">
              @foreach ($turnos as $turno)
                <option value="{{ $turno->id_turno }}">{{ $turno->turno }}</option>
              @endforeach
            </select>
            <div id="valida-turno-agregar"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="validar(1);">Guardar Cambios</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalNuevaEncuesta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Nueva oportunidad de encuesta</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="noEmpleado-nueOpo" class="col-form-label">Seleccione el número de empleado:</label>
            <select type="text" class="form-control" id="noEmpleado-nueOpo">
              @foreach ($usuarios as $usuario)
                <option value="{{ $usuario->no_empleado }}">{{ $usuario->no_empleado }}</option>
              @endforeach
            </select>
            <div id="valida-noEmpleado-nueOpo"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="nuevaOportunidadDeEncuesta();">Guardar Cambios</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalEditarUsuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Editar Usuario</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="message-text" class="col-form-label">Correo:</label>
            <input type="text" id="input-correo-editar" class="form-control">
            <div id="valida-input-correo-editar"></div>
          </div>
          <div class="form-group">
            <label for="message-text" class="col-form-label">posicion:</label>
            <select name="posicion" class="form-control" id="select-posicion">
              @foreach ($posiciones as $posicion)
                <option value="{{ $posicion->posicion }}">{{ $posicion->posicion }}</option>
              @endforeach
            </select>
            <div id="valida-posicion"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="validar();">Actualizar</button>
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
    
  <!-- Modal -->
  <div class="modal fade" id="modalLlenarEncuesta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
@endsection

@section('modalEliminar')
<div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Eliminar Usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="contenidoModal">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" onclick="confirmarEliminar()" class="btn btn-danger">Eliminar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
  <script>
    var ruta = "{{ route('usuarios.mostrar') }}";
    var actualizar = "{{ route('usuarios.actualizar') }}";
    var agregarUsuario = "{{ route('usuario.agregar') }}";
    var nuevaEncuesta = "{{ route('usuarios.nuevaOportunidad') }}";
    var rutaEstadistica = "{{ route('controlador.estadisticas') }}";
  </script>
  <script src="{{ asset('vendor/js/mainUsuarios.js') }}"></script>
@endsection
