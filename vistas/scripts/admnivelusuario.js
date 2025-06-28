//Declaramos la variable global para el manejo de las tablas en el datatable
var tabla;


//funcion que se ejecuta al incio
function init()
{
	$("#btnAgregar").hide();
	mostrarform(false);
	listar();

	$("#formulario").on("submit", function(e)
	{
		guardaryeditar(e);
	});

}


function listar()
{
	var estado= $("#estadof").val();

	if (estado=='-Todos-'){
		estado='';
	} else if (estado=='Activos'){
		estado='1'
	} else{
		estado='0'
	}

	tabla=$('#tbllistado').dataTable(
	{
		"aProcessing":true, //Activamos el procesamiento de datatables
		"aServerSide":true, //Paginación y filtrado realizados por el servidor
		dom: 'Bfrtip', //Definimos los elementos del control de tabla
		buttons:[
		],
		"ajax": {
			url: '../ajax/admnivelusuario.php?op=listar',
			data: {estado: estado},
			type: "get",
			dataType : "json",
			error: function (e){
				console.log(e.responseText);
			}
		},
		"bDestroy": true,
		"iDisplayLength": 10, //Paginacion
		"order": [[ 1, "asc" ]] //Ordenar (columna, orden)
	}).DataTable();
}


// Funcion que va a mostrar los datos de un registro en el formulario cuando se edita.
function mostrar(idNivelUsuario)
{
	$.post("../ajax/admnivelusuario.php?op=mostrar",{idNivelUsuario : idNivelUsuario}, function(data, status)
	{
		data = JSON.parse(data);
		mostrarform(true);

 		$("#idNivelUsuario").val(data.idNiv);
		$("#nomNivelUsuario").val(data.descripcion);
		$("#estado").val(data.activo);
		$("#estado").selectpicker('refresh');
	});
}


// Funcion mostrar formulario
function mostrarform(flag)
{
	limpiar();
	if (flag)
	{
		$("#listadoregistros").hide(); //Se va a crear un div con el nombre listadoregistros en html
		$("#formularioregistros").show();  //Se va a crear un div con el nombre formularioregistros en html
		$("#btnGuardar").prop("disabled", false); // Para el boton guardar.
		$("#btnAgregar").hide(); // Se oculta el boton agregar ya que estamos ingresando un nuevo registro
	}
	else
	{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#formulario_opciones").hide();
		//$("#btnAgregar").show(); // Se vuelve a mostrar el boton agregar ya que estamos mostrando la lista
	}
}

//Funcion limpiar
function limpiar()
{
	$("#idNivelUsuario").val("");  // Se hace esto para evitar chancar los datos cuando se agrega un nuevo registro.
	$("#nomNivelUsuario").val("");
	$("#estado").val("- Seleccione Estado -");
	$("#estado").selectpicker('refresh');
}

//Funcion cancelarform
function cancelarform()
{
	limpiar();
	mostrarform(false);
}

//Funcion cancelarform
function cancelarform1()
{
 	$("#lista_opciones").val('');
 	$("#listadoregistros").show();
	$("#formulario_opciones").hide();
}

//Funcion para ingresar a opciones
function opciones(idNivelUsuario)
{

 	$("#listadoregistros").hide();
	$("#formulario_opciones").show();
	$("#nivel_usuario").val(idNivelUsuario);
	$.post("../ajax/admnivelusuario.php?op=opciones", {idNivelUsuario : idNivelUsuario}, function(e){
		//bootbox.alert(e);
		$("#lista_opciones").html(e);
	});
}

//permitirá guardar las opciones
$(document).ready(function() {
    $('#btnGuardarOpciones').click(function(e){
    	e.preventDefault(); //No se activará la acción predeterminada del evento
		var selected = '';  
        $('#formularioo input[name=chkMod]').each(function(){
            if (this.checked) {
                selected += $(this).val()+', ';
            }
        }); 

        if (selected != '') {

			var nivel_usuario = $("#nivel_usuario").val();
			$.ajax({
	            type:'POST', 
	            url: "../ajax/admnivelusuario.php?op=grabaropciones",
	            data: {idNivelUsuario:nivel_usuario,data : selected},
	            cache: false,
	            success: function(response){
	            	bootbox.alert(response);
	            	cancelarform1();
	            },
	            error: function(objeto, quepaso, otroobj){//SI OCURRE UN ERROR 
	                bootbox.alert('Error: '+objeto +" - "+ quepaso+" -" + otroobj);//MENSAJE EN CASO DE ERROR
	            }
	        });
            
		}else{
			bootbox.alert('Debes seleccionar al menos una opción.');
        return false;
	}
    });         
});

// Funcion para guardar y editar
function guardaryeditar(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled",true);
	var formData = new 	FormData($("#formulario")[0]);

	$.ajax({
		url	: "../ajax/admnivelusuario.php?op=guardaryeditar",
		type : "POST",
		data : formData,
		contentType : false,
		processData : false,

		success: function(datos)
		{
			bootbox.alert(datos);
			mostrarform(false);
			tabla.ajax.reload();
		}
	});
	limpiar();
}

//Funcion para seleccionar detalle de modulo
function seleccionarDetalle(value){
	var registrar2="";
    if ($("#chkMod"+value).prop('checked')) {
         $("#detalle"+value+" input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
		  $("#detalle"+value+" input:checkbox:checked").each(function() {
             //alert($(this).val());
			  //registrar2=registrar($(this).val(),value, $("#txtRol").val());
		  });
		  //bootbox.alert("REGISTRADO CORRECTAMENTE");
    } else {
        $("#detalle"+value+" input[type=checkbox]").prop('checked', false); //solo los del objeto #diasHabilitados
    }
};

//Funcion para eliminar los registros
function eliminar(idNivelUsuario)
{
	bootbox.confirm("¿Está seguro que desea Eliminar el Nivel de Usuario?", function(result){
		if (result)
		{
			$.post("../ajax/admnivelusuario.php?op=eliminar", {idNivelUsuario : idNivelUsuario}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

//Función para desactivar registros
function desactivar(idNivelUsuario)
{
	bootbox.confirm("¿Está Seguro de Desactivar el Nivel de Usuario Seleccionado?", function(result){
		if(result)
        {
        	$.post("../ajax/admnivelusuario.php?op=desactivar", {idNivelUsuario : idNivelUsuario}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}



//Función para activar registros
function activar(idNivelUsuario)
{
	bootbox.confirm("¿Está Seguro de Activar el Nivel de Usuario?", function(result){
		if(result)
        {
        	$.post("../ajax/admnivelusuario.php?op=activar", {idNivelUsuario : idNivelUsuario}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

init();