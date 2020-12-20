/**
 * Evita ataques CSRF
 */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});



/**
 * Al iniciar la pagina, muestra las preguntas previamente creadas
 * y los tipos de respuestas permitidas
 */
$(document).ready(function(){ 
    mostrarPreguntasDelArea();
    let parametros = `area=${AJAX.idArea}`;
    $.ajax({
        url: AJAX.rutaMostrarTiposRespuestas,
        method: 'GET',
        data: parametros,
        success: ( respuesta )=>{
            mostrarTipos(respuesta);
        }
    });
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
        event.preventDefault();
        return false;
        }
    });
});



var campo = {
    id: 'pregunta',
    valido: false
}



/**
 * valida la informacion del modal
 */
function validar() {
    ($('#'+campo.id).val() === '')?campo.valido = false: campo.valido=true;
    marcar(campo);
    if(campo.valido== false)
        return;
    guardarPregunta();
    mostrarPreguntasDelArea();
    $('#'+campo.id).val('');
}



/**
 * resalta si la informacion es valida o invalida
 */
function marcar(valor) {
    if(valor.valido == false){
        $('#'+valor.id).addClass('is-invalid');
        $('#'+valor.id).removeClass('is-valid');
        $('#valida-'+valor.id).removeClass('valid-feedback');
        $('#valida-'+valor.id).addClass('invalid-feedback');
        $('#valida-'+valor.id).html('El campo no debe de ir vacio');
    }else{
        $('#'+valor.id).addClass('is-valid');
        $('#'+valor.id).removeClass('is-invalid');
        $('#valida-'+valor.id).removeClass('invalid-feedback');
        $('#valida-'+valor.id).addClass('valid-feedback');
        $('#valida-'+valor.id).html('Campo Correcto');
    } 
}



/**
 * muestra una pregunta
 */
function mostrar (contenido,id,idTipo) {    
    $('#areaPreguntas').append(`
      <div class="card">
        <div class="row">
          <div class="col-12">
            <div class="row mb-4 d-flex bd-highlight align-items-center mb-3">
              <h3 class="mr-auto p-2 bd-highlight" id="pregunta${id}">${contenido}</h3>
              <button type="button" class="p-2 bd-highlight mr-3 btn bg-cos bg-cos-list" onclick="editarPregunta(${id},'${contenido}',${idTipo});" >editar</button>
              <button type="button" class="btn bg-cos-gray p-2 bd-highlight mr-4" onclick="eliminarPregunta(${id});" >eliminar</button>
            </div>
            <ul class="list-group list-group-flush" id="respuestasPregunta${id}">
            </ul>
          </div>
        </div>
      </div> 
    `);
    mostrarRespuestasDelTipo(id,idTipo);
}



/**
 * Muestra todas las preguntas del area seleccionada
 */
function mostrarPreguntasDelArea() {
    $('#areaPreguntas').html('');
    let parametros = `area=${AJAX.idArea}`;
    $.ajax({
        url: AJAX.rutaMostrarPreguntas,
        method: 'GET',
        data: parametros,
        success: ( respuesta )=>{
            if(respuesta.length == 0){
                $('#areaPreguntas').html(`
                <h4 class="ml-5">Sin preguntas aún</h4>
                `);
            }else{
                respuesta.forEach(element => {
                    mostrar(element.contenido,element.id_pregunta,element.id_tipo);
                });
            }
        }
    });
}



/**
 * muestra los tipos de respuestas en el modal
 */
function mostrarTipos(array) {
    array.forEach(element => {
        $('#inputState').append(
            `<option value="${element.id_tipo}">${element.tipo}</option>`
        );
        $('#inputState2').append(
            `<option value="${element.id_tipo}">${element.tipo}</option>`
        );
    });
}



/**
 * muestra las respuestas que tiene una pregunta
 */
function mostrarRespuestasDelTipo(idPregunta,tipo) {
    let parametros = `id_tipo=${tipo}`;
    $.ajax({
        url: AJAX.rutaMostrarRespuestasDelTipo,
        method: 'GET',
        data: parametros,
        success: ( respuesta )=>{
            respuesta.forEach(element => {
                $('#respuestasPregunta'+idPregunta).append(`
                <li class="list-group-item">
                  <div class="row">
                    <div class="col-8">
                      <span class="space">${element.contenido}</span>
                    </div>
                  </div>
                </li>
                `);
            });
        }
    });
}



function editar () {
    let elemento = document.querySelector('#titulo');
    elemento.toggleAttribute('disabled');
}



/**
 * cuando la informacion ingresada en el modal es correcta, se almacena en la base de datos
 */
function guardarPregunta(){
    let parametros = `area=${AJAX.idArea}&tipo=${$('#inputState').val()}&contenido=${encodeURIComponent($('#'+campo.id).val())}`;
    $.ajax({
        url: AJAX.rutaAgregarPreguntas,
        method: 'GET',
        data: parametros,
        success: ( respuesta )=>{
            $('#pregunta').removeClass('is-valid','is-invalid');
            $('#valida-pregunta').html('');
        }
    });
    $('#modalAgregarPregunta').modal('hide');
}



/**
 * edita una pregunta pasando los datos a un modal
 */
function editarPregunta(id, cont, tipo){
    $('#modalEditarPregunta').modal('toggle');
    $('#pregunta-editar').val(cont);
    $('#inputState2 option[value='+tipo+']').attr("selected", "selected");
    $('#id-pregunta-editar').val(id);
}



var campoEditar = {
    id: 'pregunta-editar',
    valido: false
}



/**
 * valida la informacion del modal editar
 */
function validarEditar() {
    ($('#'+campoEditar.id).val() === '')?campoEditar.valido = false: campoEditar.valido=true;
    marcar(campoEditar);
    if(campoEditar.valido== false)
    return;
    actualizarPregunta();
}



/**
 * Actualiza una pregunta
 */
function actualizarPregunta() {
    let parametros = `tipo=${$('#inputState2').val()}&contenido=${encodeURIComponent($('#pregunta-editar').val())}&id=${$('#id-pregunta-editar').val()}`;
    $.ajax({
        url: AJAX.rutaActualizarPreguntas,
        method: 'GET',
        data: parametros,
        success: ( respuesta )=>{
            $('#pregunta-editar').removeClass('is-valid','is-invalid');
            $('#valida-pregunta-editar').html('');            
            mostrarPreguntasDelArea();
            $('#modalEditarPregunta').modal('hide');
        }
    });
}



/**
 * Muestra el modal de eliminar una pregunta
 */
var idEliminar;
function eliminarPregunta(id) {
    $('#modalEliminarPregunta').modal('show');
    idEliminar = id;
    $('#modal-eliminar-contenido').html(`¿Está seguro de eliminar la pregunta?`);
}



/**
 * Elimina una pregunta de la base de datos
 */
function validarElminar(){
    let parametros = `id=${idEliminar}`;
    //console.log(parametros);
    $.ajax({
        url: `${AJAX.rutaEliminar}/${idEliminar}`,
        method: 'GET',
        data: parametros,
        success: ( respuesta )=>{
            // console.log(respuesta);
            mostrarPreguntasDelArea();
        }
    });
    $('#modalEliminarPregunta').modal('hide');
}






