@extends('plantillas.encuesta')

@section('head')
	<link rel="stylesheet" href="{{ asset('css/preguntas-css.css') }}">
@endsection

@section('cuerpoPagina')
	<div id='container'>
		<button type="button" id="contras" class="btn btn-outline-secondary" data-toggle="tooltip" data-placement="bottom" title="Cambiar contraseña" onclick="cambioClave({{ Session::get('noEmpleado') }});" ><i class="fas fa-wrench"></i></button>
		<div id="envolver">
			<div id='title' class="mt-5 px-5">
				<h1 class="titulo-enc" id="titulo-enc">{{ $preguntaFiltro }}</h1>
			</div>
      <br/>
      
      <form method="POST" action="{{ route('encuesta.seleccionar') }}" id="box-repuestas-filtro" class="d-flex justify-content-center p-3 mb-4">
        {{method_field('PUT')}}     {{-- cambia de method="POST" a method="PUT" --}}
        {!! csrf_field() !!}        <!--Proteccion de ataques csrf-->
        <input name="noEmpleado" type="hidden" value="{{ $datos }}">
        <button name="btn" type="submit" value="1" id="btn-filtro-si" class="button quiz-btn btn-filtro-si mr-3">Si</button>
        <button name="btn" type="submit" value="0" id="btn-filtro-no" class="button quiz-btn btn-filtro-no">No</button>
      </form>
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
			redireccionLogin: "{{ route('sistema.inicio') }}",
			contrasenaCorreo: "{{ route('usuario.recuperar') }}",
		}
		function Regresar(){
			window.location = "{{ route('encuesta.mostrarAreas') }}"
		}
	</script> 		
@endsection
