{{-- Hereda el contenido de plantillas/plantilla1 --}}
@extends('plantillas.plantilla1')

{{-- Cambia el titulo de la pagina --}}
@section('tituloPagina','Iniciar Sesion')

{{--Agrega al head--}}
@section('head')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

{{-- Contenido de <body> --}}
@section('cuerpoPagina')
    <div class="bg">
        <div class="cont" id="contenido-inicio">
            <div class="envolver" id="envolver-inicio">
                <h3 class="greet">Bienvenido</h3>
                <form method="POST" action="{{ route('sistema.verificarEmpleadoContrasena') }}">
                    {{method_field('PUT')}}     {{-- cambia de method="POST" a method="PUT" --}}
                    {!! csrf_field() !!}   
                    <div class="form-group form-st mb-1">
                        <label for="numeroEmpleado">Número de empleado</label>
                        <input type="number" name="numeroEmpleado" class="form-control form-inp" id="numeroEmpleado" aria-describedby="emailHelp" placeholder="Ingrese su número de empleado" value="{{ old('numeroEmpleado') }}">
                        <label class="mt-2 ml-3" for="numeroEmpleado" style="color:#ff3300;">
                            <small>
                                @if ($errors->has('numeroEmpleado'))
                                    {{$errors->first('numeroEmpleado')}}
                                @endif
                            </small>
                        </label>
                    </div>
                    <div class="form-group form-st mb-1">
                        <label for="contrasena">Contraseña</label>
                        <input type="password" name="contrasena" id="contrasena" class="form-control form-inp" placeholder="Ingrese su contraseña" >
                        <label class="mt-2 ml-3" for="contrasena" style="color:#ff3300;">
                            <small>
                                @if ($errors->has('contrasena'))
                                    {{$errors->first('contrasena')}}
                                @endif
                            </small>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary mb-3">Ingresar</button>
                    <button type="button" class="btn btn-link" onclick="gdc();" data-toggle="modal" data-target="#modalContrasena">Olvidé mi contraseña</button>
                  </form>
            </div>
        </div>
    </div>








<!--
    <div style="height:100px;"></div>
    <div class="container">
        <div class="row">
            <div id="box-iniciar-sesion" class="col-6 col-sm-8 col-md-6 col-lg-5 col-xl-4 mx-auto border px-5 py-5 text-light shadow-lg rounded-lg">
                <h2 class="d-flex justify-content-center mb-4">Bienvenido</h2>
                <form> {{-- al presionar "Ingresar" dirige la informacion a la ruta /verif 
                    {{method_field('PUT')}}      cambia de method="POST" a method="PUT" 
                    {!! csrf_field() !!}        Proteccion de ataques csrf--}}
                    <div class="form-group mb-2">
                        <label for="exampleInputEmail1">Número de empleado</label>
                        <input type="number" name="numeroEmpleado" id="numeroEmpleado" class="form-control" placeholder="Ingrese su número de empleado" value="{{ old('numeroEmpleado') }}"> {{-- mantiene el valor ingresado --}}
                        
                        {{-- Verifica si hay errores en la informacion ingresada 
                             de existir los muestra en texto color rojo --}}
                        <label class="mt-3 ml-3" for="numeroEmpleado" style="color:#ff3300;">
                        {{-- @if ($errors->has('numeroEmpleado'))
                            {{$errors->first('numeroEmpleado')}}
                        @endif --}}
                        </label>

                    </div>
                    <div class="form-group mb-4">
                        <label for="exampleInputPassword1">Contraseña</label>
                        <input type="password" name="contrasena" id="contrasena" class="form-control" placeholder="Ingrese su contraseña">
                        <label class="mt-3 ml-3" for="contrasena" style="color:#ff3300;">
                        {{-- @if ($errors->has('contrasena'))
                            {{$errors->first('contrasena')}}
                        @endif --}}
                        </label>

                    </div>
                    <div class="d-flex justify-content-center">
                        <button id="btn-ingresar-sistema" type="submit" class="btn btn-light py-3 btn-largo">Ingresar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div style="height:100px;"></div>-->

    <div class="modal fade" id="modalContrasena" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Recuperación de Contraseña</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <div class="modal-body">
                Para reinicio de contraseña contacte con el administrador del sistema
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function gdc() {
            $.ajax({
                url: '{{ route("olvidar.contrasena") }}',
                method: 'GET',
                success: ( respuesta )=>{
                }
            });
        }
    </script>
@endsection