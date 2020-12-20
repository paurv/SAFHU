/**
 * Evita ataques CSRF
 */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


// validaciones;
var campos = [
    {id: 'nombre', valido: false},
    {id: 'descripcion', valido: false}
];

function validarCampoVacio(){
        campos.forEach(valor=>{
            ($('#'+valor.id).val() === '')?valor.valido = false:valor.valido = true;
        });
}

var idEliminar;

function validar(){
    validarCampoVacio();
    // console.log(campos);
    
    for(let i = 0;i<campos.length;i++)
        marcar(campos[i]);

    for(let i =0 ;i<campos.length;i++){
        if(campos[i].valido == false)
        return;
    }

    guardarMostrar();

}


function marcar(valor) {

    // (valor.valido == false)?console.log('no es valido'):console.log(' es valido');

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
function guardarMostrar() {
    let nombre = $('#'+campos[0].id).val();
    let descripcion = $('#'+campos[1].id).val();
    let parametros = `nombre=${nombre}&descripcion=${descripcion}`;
    $('#'+campos[0].id).val('');
    $('#'+campos[1].id).val('');

    $('#nombre').removeClass('is-valid','is-invalid');
    $('#descripcion').removeClass('is-valid','is-invalid');
    $('#valida-nombre').html('');
    $('#valida-descripcion').html('');
    $.ajax({
        // url: '/agregarArea',
        url: rutas.AgregarArea,
        method: 'GET',
        data: parametros,
        dataType: 'json',
        success: (res)=>{
            $('#areaPreguntas').html('');
            // $('#nombre').removeClass('is-valid','is-invalid');
            // $('#descripcion').removeClass('is-valid','is-invalid');
            // $('#valida-nombre').html('');
            // $('#valida-descripcion').html('');
            if(res.length == 0)
            {
                $('#areaPreguntas').html(`
                    <h4 class="ml-5">Sin áreas aún</h4>
                `);
            }else
            {
                res.forEach((e)=>{
                    mostrarAreas(e);
                });
            }
        }
    });
    $('#modalAgregarPregunta').modal('hide');
}

function mostrarAreas (elemento) {

    $('#areaPreguntas').append(`
    <div class="col-12 col-lg-6 col-xl-4"  style="z-index:1;">
    <div class="card card-style mb-3">
    <div class="card-header"><span class="mr-1 titulo-enc">Encuesta:</span>${elemento.nombre}</div>
    <div class="card-body"  id="${elemento.id_area}" onclick="mostrarPreguntas(this)">
    <h5 class="card-title descripcion-enc">Descripción</h5>
    <p class="card-text">${elemento.descripcion}</p>
    </div>
    <div class="card-footer d-flex justify-content-center">
    <button type="button" class="btn bg-cos bg-cos-list mb-2 mr-3"  onclick="editar(${elemento.id_area},'${elemento.nombre}','${elemento.descripcion}');" data-toggle="modal" > Editar </button>
    <button type="button" class="btn bg-cos-gray mb-2 mr-3"  onclick="eliminar(${elemento.id_area})" data-toggle="modal" > Eliminar </button>
    </div>
    </div>
    </div>
`);


}

function mostrarPreguntas(valor){
    //window.location.href = `/preguntas?id=${valor.id}`;
    window.location.href = `${rutas.MostrarPreguntas}?id=${valor.id}`;
}

function eliminar (id) {
    // console.log('se eliminara este elemento '+ id);
    $('#modal-eliminar').modal('show');
    $('#contenido-modal').html(`¿Esta seguro que desea eliminar el Area?`);
    idEliminar = id;
}

function confirmarEliminar(){
    let id = idEliminar;
    // console.log('entra aqui ' +id);
    $.ajax({
        url: `${rutas.principalAdmin}/${id}`,
        method: 'get',
        dataType: 'json',
        success: (res)=>{
            // console.log(res);
            $('#areaPreguntas').html('');
            if(res.length == 0)
            {
                $('#areaPreguntas').html(`
                    <h4 class="ml-5">Sin áreas aún</h4>
                `);
            }else
            {
                res.forEach((e)=>{
                mostrarAreas(e);
                });
            }
        }
    });
    $('#modal-eliminar').modal('hide');
    return;
};

var idEditar;

function editar(id,nom,des){
    // console.log('se editara este elemento '+ id);
    $('#modalEditarPregunta').modal('show');
    $('#nombre-editar').val(nom);
    $('#descripcion-editar').val(des);
    idEditar = id;
}

function confirmarEditar(){
    if ($('#nombre-editar').val() == '') {
        $('#nombre-editar').addClass('is-invalid');
        $('#valida-nombre-editar').removeClass('valid-feedback');
        $('#valida-nombre-editar').addClass('invalid-feedback');
        $('#valida-nombre-editar').html('El campo no debe de ir vacio');
    } else if($('#descripcion-editar').val() == '') {

        $('#descripcion-editar').addClass('is-invalid');
        $('#valida-descripcion-editar').removeClass('valid-feedback');
        $('#valida-descripcion-editar').addClass('invalid-feedback');
        $('#valida-descripcion-editar').html('El campo no debe de ir vacio');
    }else{
        let parametros = `id=${idEditar}&nombre=${$('#nombre-editar').val()}&descripcion=${$('#descripcion-editar').val()}`;
        $.ajax({
            url: rutas.editarArea,
            method: 'get',
            data: parametros,
            dataType: 'json',
            success: (res)=>{
                // console.log(res);
                $('#areaPreguntas').html('');
                $('#nombre-editar').removeClass('is-invalid','is-valid');
                $('#descripcion-editar').removeClass('is-invalid','is-valid');
                $('#valida-nombre-editar').html('');
                $('#valida-descripcion-editar').html('');
                if(res.length == 0)
                {
                    $('#areaPreguntas').html(`
                        <h4 class="ml-5">Sin áreas aún</h4>
                    `);
                }else
                {
                    res.forEach((e)=>{
                        mostrarAreas(e);
                    });
                }
            }
        });
        $('#modalEditarPregunta').modal('hide');
        return;
    }
};


function editarPF() {

    $('#modalEditarPreguntaFiltro').modal('show');
}




function confirmarEditarPF() {
    if ($('#preguntaF-editar').val() == '') {
        $('#preguntaF-editar').addClass('is-invalid');
        $('#valida-preguntaF-editar').removeClass('valid-feedback');
        $('#valida-preguntaF-editar').addClass('invalid-feedback');
        $('#valida-preguntaF-editar').html('El campo no debe de ir vacio');
    }else{
        let parametros = `pregunta=${$('#preguntaF-editar').val()}`;
        $.ajax({
            url: rutas.editarPreguntaFiltro,
            method: 'get',
            data: parametros,
            dataType: 'json',
            success: (res)=>{
                // console.log(res);
                $('#preguntaF-editar').removeClass('is-invalid','is-valid');
                $('#valida-preguntaF-editar').html('');
            }
        });
        $('#modalEditarPreguntaFiltro').modal('hide');
        return;
    }
}




