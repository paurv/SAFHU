@extends('plantillas.encuesta')

@section('head')
	<link rel="stylesheet" href="{{ asset('css/preguntas-css.css') }}">
@endsection

@section('cuerpoPagina')
	<div id='container'>
		<button type="button" id="contras" class="btn btn-outline-secondary" data-toggle="tooltip" data-placement="bottom" title="Cambiar contraseña" onclick="cambioClave({{ Session::get('noEmpleado') }});" ><i class="fas fa-wrench"></i></button>
		<div id="envolver">
			<div id='title' class="mt-4">
				<h1 class="titulo-enc" id="titulo-enc">Encuesta: {{$nombreArea}}</h1>
			</div>
			<br/>
			<div id='quiz'></div>
			<div class='button quiz-btn' id='next'><span>siguiente</span></div>
			<div class='button quiz-btn' id='start'><span>Finalizar encuesta</span></div>
			<div class='button quiz-btn quiz-btn-red' id='enc-sig'><span onclick="Regresar();">Realizar otra encuesta</span></div>
		</div>
	</div>

	{{-- <div class="modal fade" id="mail-modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Cambio de clave</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
						<div class="form-group">
							<label for="nuevaContrasena1" class="col-form-label">Ingrese una nueva contraseña:</label>
							<input type="password" class="form-control" id="nuevaContrasena1">
							<div id="valida-nuevaContrasena1"></div>
							<label for="nuevaContrasena2" class="col-form-label">Confirme la nueva contraseña:</label>
							<input type="password" class="form-control" id="nuevaContrasena2">
							<div id="valida-nuevaContrasena2"></div>
						</div>
					</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal" id="mail-cancel">Cancelar</button>
					<button type="button" class="btn btn-primary" id="mail-send" onclick="confirmarEnviar('{{ Session::get('noEmpleado') }}');">Enviar</button>
				</div>
			</div>
		</div>
	</div> --}}

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
			id: "{{ $id }}",
			rutaMostrarPreguntas: "{{ route('area.preguntasAJAX') }}",
			rutaGuardarRespuestas: "{{ route('encuesta.guardar') }}",
			rutaFinalizarEncuesta: "{{ route('encuesta.fin') }}",
			redireccionLogin: "{{ route('sistema.inicio') }}",
			contrasenaCorreo: "{{ route('usuario.recuperar') }}",
		}
		function Regresar(){
			window.location = "{{ route('encuesta.mostrarAreas') }}"
		}
	</script> 		
@endsection
