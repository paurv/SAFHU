var idEliminar;
var porEditar;



// validaciones;
var campos = [
    {id: 'input-correo-editar', valido: false, corValido: true},
];



// Muestra el modal de eliminar usuario
function eliminarUsuario(id) {
    $('#modalEliminar').modal('show');
    idEliminar = id;
    $('#contenidoModal').html(`¿Está seguro de eliminar el usuario con el número de empleado ${id}?`);
}



// Se confirma que el usuario se eliminará
function confirmarEliminar () {
    $.ajax({
        url: `${ruta}/${idEliminar}`, //url: `pagPriAdm/${id}`,
        method: 'get',
        dataType: 'json',
        success: res=>{
            mostrar(res);
            $('#modalEliminar').modal('hide');
            location.reload();
        }
    });
}



// Muestra los usuarios registrados en el sistema
function mostrar(res) {
    $('#tbl-usuarios').html('');
    res.forEach((usuario)=>{
        $('#tbl-usuarios').append(`
        <tr>
        <th scope="row"> ${usuario.no_empleado}</th>
        <td> ${usuario.nombres} ${usuario.apellidos}</td>
        <td> ${usuario.posicion}</td>
        <td> ${usuario.turno}</td>
        <td> ${usuario.email}</td>
        <td class="form-inline">
        <button class="btn btn-black mr-1" onclick="editarUsuario(${usuario.no_empleado},'${usuario.email}','${usuario.posicion}');"> <i class="far fa-edit"></i></button>
        <button class="btn bg-cos-gray mr-1" onclick="eliminarUsuario(${usuario.no_empleado})"><i class="fas fa-user-minus"></i></button>
        <button class="btn btn-regresar mr-1" onclick="cambioClave(${usuario.no_empleado})"><i class="fas fa-unlock"></i></button>
        <form method="GET" action="${rutaEstadistica}">
        <button type="submit" class="btn bg-cos bg-cos-list" name="estadisticaEmpleado" value="${ usuario.no_empleado }"><i class="far fa-chart-bar"></i></button>
        </form>
        </td>
        </tr>
        `);
    });
}



// Muestra el modal de editar usuarios
function editarUsuario(noEmp,email,posicion) {
    porEditar = noEmp; 
    $('#modalEditarUsuario').modal('show');
    $('#input-correo-editar').val(email);
    $('#select-posicion').val(posicion).attr("selected", "selected");
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
    }else if(valor.corValido == false) {
        $('#'+valor.id).addClass('is-invalid');
        $('#'+valor.id).removeClass('is-valid');
        $('#valida-'+valor.id).removeClass('valid-feedback');
        $('#valida-'+valor.id).addClass('invalid-feedback');
        $('#valida-'+valor.id).html('El campo no es una dirección de correo electronico');
    }else {
        $('#'+valor.id).addClass('is-valid');
        $('#'+valor.id).removeClass('is-invalid');
        $('#valida-'+valor.id).removeClass('invalid-feedback');
        $('#valida-'+valor.id).addClass('valid-feedback');
        $('#valida-'+valor.id).html('Campo Correcto');
    }
    
}



function validarCampoVacio(){
    campos.forEach(valor=>{
        ($('#'+valor.id).val() === '') ? valor.valido = false : valor.valido = true;
        var x = $('#'+valor.id).val();
        var atposition=x.indexOf("@");  
        var dotposition=x.lastIndexOf(".");  
        ( atposition<1 || dotposition<atposition+2 /*|| dotposition+2>=x.length*/ ) ? valor.corValido = false : valor.corValido = true;
    });
}



//edita usuario
function validar(tipo){
    if (tipo == null) {

        validarCampoVacio();
        
        for(let i = 0;i<campos.length;i++)
            marcar(campos[i]);

        for(let i = 0;i<campos.length;i++){
            if(campos[i].valido == false || campos[i].corValido == false)
                return;
        }
        let parametros = `no_empleado=${porEditar}&email=${encodeURIComponent($('#input-correo-editar').val())}&posicion=${$('#select-posicion').val()}`;
        $.ajax({
            url: actualizar,
            method: 'get',
            dataType: 'json',
            data: parametros,
            success: res=>{
                $('#input-correo-editar').removeClass('is-valid','is-invalid');
                $('#valida-input-correo-editar').html('');

                mostrar(res);
                $('#modalEditarUsuario').modal('hide');
            }
        });
    } else {
        var x = $('#correo-agregar').val();
        var atposition=x.indexOf("@");  
        var dotposition=x.lastIndexOf(".");  
        if ( atposition<1 || dotposition<atposition+2) {
            $('#correo-agregar').addClass('is-invalid');
            $('#valida-correo-agregar').removeClass('valid-feedback');
            $('#valida-correo-agregar').addClass('invalid-feedback');
            $('#valida-correo-agregar').html('El campo es invalido');
            return;
        } else {
            let parametros = `no_empleado=${$('#noEmpleado-agregar').val()}&email=${$('#correo-agregar').val()}&posicion=${$('#posicion-agregar').val()}&turno=${$('#turno-agregar').val()}`;
            $.ajax({
                url: agregarUsuario,
                method: 'get',
                dataType: 'json',
                data: parametros,
                success: res=>{
                    $('#modalAgregarUsuario').modal('hide');
                    location.reload();
                }
            });
        }
    }
}



function nuevaOportunidadDeEncuesta() {
    let parametros = `no_empleado=${$('#noEmpleado-nueOpo').val()}`;
    $.ajax({
        url: nuevaEncuesta,
        method: 'get',
        data: parametros,
        success: res=>{
            // alert('Nueva oportunidad asignada con éxito!');
            $('#modalNuevaEncuesta').modal('hide');
        }
    });
}



//--------------------------------------------------------------------------------------------------------------------------------------
// Muestra el modal de cambiar clave
function cambioClave(codigo) {
    noEmp = codigo;
    $('#modalCambioClave').modal('show');
    // $('#input-correo-editar').val(email);
    // $('#select-posicion').val(posicion).attr("selected", "selected");
}



//JS para cambio de clave
$('#vc').on('click',function(){
    var tipo=$('#con1').attr('type');
    if(tipo== 'password'){
        $('#con1').attr('type','text');
        $('#vc').html('<i class="far fa-eye"></i>');
    }else{
        $('#con1').attr('type','password');
        $('#vc').html('<i class="far fa-eye-slash"></i>');
    }
});

$('#vcc').on('click',function(){
    var tipo=$('#con2').attr('type');
    if(tipo== 'password'){
        $('#con2').attr('type','text');
        $('#vcc').html('<i class="far fa-eye"></i>');
    }else{
        $('#con2').attr('type','password');
        $('#vcc').html('<i class="far fa-eye-slash"></i>');
    }

});
//Asegurarse de que la nueva clave cumpla con los requisitos
function val(valor){
    var con = valor.value;
    // var pattmay = /^(?=.*[A-Z]).*$/;
    // var resmay = pattmay.test(cont);
    // var pattmin =/^(?=.*[a-z]).*$/;
    // var resmin = pattmin.test(cont);
    // var pattnum = /^(?=.*\d).*$/;
    // var resnum =pattnum.test(cont);
    // var pattchar =/^(?=.*[!@#$%&*,.?]).*$/
    // var reschar = pattchar.test(cont);
    // if (!resmay){
    //     $('val-feed').html('La contraseña debe contener al menos una mayuscula');
    //     document.getElementById('con1').classList.add('is-invalid');
    //     document.getElementById('con1').classList.remove('is-valid');
    // }else if(!resmin){
    //     $('val-feed').html('La contraseña debe contener al menos una minuscula');
    //     document.getElementById('con1').classList.add('is-invalid');
    //     document.getElementById('con1').classList.remove('is-valid');
    // }else if(!resnum){
    //     $('val-feed').html('La contraseña debe contener al menos un numero');
    //     document.getElementById('con1').classList.add('is-invalid');
    //     document.getElementById('con1').classList.remove('is-valid');
    // }else if(!reschar){
    //     $('val-feed').html('La contraseña debe contener al menos un caracter especial(!@#$%&*,.?)');
    //     document.getElementById('con1').classList.add('is-invalid');
    //     document.getElementById('con1').classList.remove('is-valid');
    // }else{
    //     document.getElementById('con1').classList.add('is-valid');
    //     document.getElementById('con1').classList.remove('is-invalid');        
    // }
    var patt= /^(?=.*[!@#$%&*,.?])(?=.*[A-Z])(?=.*[a-z])(?=.*\d).*$/;
    var res = patt.test(con);
    // console.log(con);
    // console.log(res);
    if(!res){
        document.getElementById('con1').classList.add('is-invalid');
        document.getElementById('con1').classList.remove('is-valid');
    }else{
        document.getElementById('con1').classList.add('is-valid');
        document.getElementById('con1').classList.remove('is-invalid');
    }
}

/*$('#btn-env').on('click',*/
function cambiarContrasena(ruta){
    var linea= document.getElementById('con1').value;
    var confir = document.getElementById('con2').value;

    // console.log(`${linea} == ${confir}`);
    if (confir !== linea){
        // alert("las contraseñas no coinciden");
    }else{
       if (confir != '') {
            // alert("Contraseñas correctas");
            let parametros = `noEmp=${ noEmp }&contrasena=${ encodeURIComponent(confir) }`;
            // console.log(ruta);
            // console.log(parametros);
            $.ajax({
              url: ruta,
              method: 'GET',
              data: parametros,
              success: ( respuesta )=>{
                console.log(respuesta);
                // alert('Contraseña cambiada exitosamente');
                $('#modalCambioClave').modal('hide');
              }
            });
       } else {
        //   alert('Las contraseña no puede ser nula') 
       }
    }
}
//--------------------------------------------------------------------------------------------------------------------------------------



// llenar encuesta de un controlador
function llenarEncuesta() {
    if($('#txta-razon').val()==''){
      alert('Ingrese la razón de llenar la encuesta');
      return false;
    }
    document.getElementById("FormularioLlenarEncuesta").submit();
  }


