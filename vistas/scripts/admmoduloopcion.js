//Declaramos la variable global para el manejo de las tablas en el datatable
var tabla;

var tablaO;

//funcion que se ejecuta al incio
function init()
{
	mostrarform(false);
	listar();

	$("#formulario").on("submit", function(e)
	{
		guardaryeditar(e);
	});

	$("#formularioo").on("submit", function(e)
	{
		guardaryeditarO(e);
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
			'excelHtml5',
			'csvHtml5',
			'pdf'
		],
		"ajax": {
			url: '../ajax/admmoduloopcion.php?op=listar',
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

//Funcion para listar opciones
function listarOpciones(idModulo)
{
	var idmodulo= idModulo;

	tablaO=$('#tbllistado_opciones').dataTable(
	{
		"aProcessing":true, //Activamos el procesamiento de datatables
		"aServerSide":true, //Paginación y filtrado realizados por el servidor
		dom: 'Bfrtip', //Definimos los elementos del control de tabla
		buttons:[
			'excelHtml5',
			'csvHtml5',
			'pdf'
		],
		"ajax": {
			url: '../ajax/admmoduloopcion.php?op=listarOpciones',
			data: {idModulo: idmodulo},
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
function mostrar(idModulo)
{
	$.post("../ajax/admmoduloopcion.php?op=mostrar",{idModulo : idModulo}, function(data, status)
	{
		data = JSON.parse(data);
		mostrarform(true);

 		$("#idModulo").val(data.idModulo);
		$("#nomModulo").val(data.nombre);
		$("#descModulo").val(data.descripcion); 
		$("#clase").val(data.class); 
		$("#estado").val(data.vigencia);
		$("#estado").selectpicker('refresh'); 
	});
}

// Funcion que va a mostrar los datos de un registro en el formulario cuando se edita.
function mostrarOpcion(idOpcion)
{
	$.post("../ajax/admmoduloopcion.php?op=mostrarOpcion",{idOpcion : idOpcion}, function(data, status)
	{
		data = JSON.parse(data);
		$("#listadoregistros").hide();
		$("#formularioregistros").hide();
		$("#formulario_opciones1").hide();
		$("#formulario_opciones2").show();

 		$("#idOpcion").val(data.idOpcion);
		$("#nomOpcion").val(data.nombre);
		$("#rutaOpcion").val(data.url);
	});
}

// Funcion mostrar formulario
function mostrarform(flag)
{
	limpiar();
	if (flag)
	{
		$("#listadoregistros").hide(); //Se va a crear un div con el nombre listadoregistros en html
		$("#formulario_opciones2").hide();
		$("#formulario_opciones1").hide();
		$("#formularioregistros").show();  //Se va a crear un div con el nombre formularioregistros en html
		$("#btnGuardar").prop("disabled", false); // Para el boton guardar.
		//$("#btnAgregar").hide(); // Se oculta el boton agregar ya que estamos ingresando un nuevo registro
	}
	else
	{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#formulario_opciones1").hide();
		$("#formulario_opciones2").hide();
		//$("#btnAgregar").show(); // Se vuelve a mostrar el boton agregar ya que estamos mostrando la lista
	}
}


function mostrarformO(flag)
{
	limpiar();
	$("#btnGuardarO").prop("disabled", false);
	if (flag)
	{	
		$("#listadoregistros").hide();
		$("#formularioregistros").hide();
		$("#formulario_opciones2").show();
		$("#formulario_opciones1").hide();
	}
	else
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").hide();
		$("#formulario_opciones2").hide();
		$("#formulario_opciones1").show();
	}
}

//Funcion limpiar
function limpiar()
{
	$("#idModulo").val("");  // Se hace esto para evitar chancar los datos cuando se agrega un nuevo registro.
	$("#nomModulo").val("");
	$("#descModulo").val("");
	$("#clase").val("");
	$("#estado").val("- Seleccione Estado -");
	$("#estado").selectpicker('refresh');
}

//Funcion limpiar
function limpiarO()
{
	$("#idOpcion").val("");
	$("#nomOpcion").val("");
	$("#rutaOpcion").val(""); 
}

//Funcion cancelarform
function cancelarform()
{
	limpiar();
	mostrarform(false);
}


//Funcion cancelarform
function cancelarformO()
{
	limpiarO();
	mostrarformO(false);
}


//Funcion para ingresar a opciones
function opciones(idModulo)
{

 	$("#listadoregistros").hide();
	$("#formulario_opciones2").hide();
	$("#formulario_opciones1").show();
	$("#idModuloo").val(idModulo);
	listarOpciones(idModulo);
}

// Funcion para guardar y editar
function guardaryeditarO(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardarO").prop("disabled",true);
	var formData = new 	FormData($("#formularioo")[0]);

	$.ajax({
		url	: "../ajax/admmoduloopcion.php?op=guardaryeditarO",
		type : "POST",
		data : formData,
		contentType : false,
		processData : false,

		success: function(datos)
		{
			bootbox.alert(datos);
			mostrarformO(false);
			tablaO.ajax.reload();
		}
	});
	limpiarO();
}

// Funcion para guardar y editar
function guardaryeditar(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled",true);
	var formData = new 	FormData($("#formulario")[0]);

	$.ajax({
		url	: "../ajax/admmoduloopcion.php?op=guardaryeditar",
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

//Funcion para eliminar los registros
function eliminar(idModulo)
{
	bootbox.confirm("¿Está seguro que desea Eliminar el Modulo?", function(result){
		if (result)
		{
			$.post("../ajax/admmoduloopcion.php?op=eliminar", {idModulo : idModulo}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

//Funcion para eliminar los registros
function eliminarO(idOpcion)
{
	bootbox.confirm("¿Está seguro que desea Eliminar la Opcion?", function(result){
		if (result)
		{
			$.post("../ajax/admmoduloopcion.php?op=eliminarO", {idOpcion : idOpcion}, function(e){
				bootbox.alert(e);
				tablaO.ajax.reload();
			});
		}
	})
}

//Función para desactivar registros
function desactivar(idModulo)
{
	bootbox.confirm("¿Está Seguro de Desactivar el Modulo Seleccionado?", function(result){
		if(result)
        {
        	$.post("../ajax/admmoduloopcion.php?op=desactivar", {idModulo : idModulo}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Función para desactivar registros
function desactivarO(idOpcion)
{
	bootbox.confirm("¿Está Seguro de Desactivar la Opcion Seleccionada?", function(result){
		if(result)
        {
        	$.post("../ajax/admmoduloopcion.php?op=desactivarO", {idOpcion : idOpcion}, function(e){
        		bootbox.alert(e);
	            tablaO.ajax.reload();
        	});	
        }
	})
}

//Función para activar registros
function activar(idModulo)
{
	bootbox.confirm("¿Está Seguro de Activar el Modulo Seleccionado?", function(result){
		if(result)
        {
        	$.post("../ajax/admmoduloopcion.php?op=activar", {idModulo : idModulo}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Función para activar registros
function activarO(idOpcion)
{
	bootbox.confirm("¿Está Seguro de Activar la Opcion Seleccionada?", function(result){
		if(result)
        {
        	$.post("../ajax/admmoduloopcion.php?op=activarO", {idOpcion : idOpcion}, function(e){
        		bootbox.alert(e);
	            tablaO.ajax.reload();
        	});	
        }
	})
}

init();