//Declaramos la variable global para el manejo de las tablas en el datatable
var tabla;

//Funcion que se ejecuta al inicio
function init()
{
	mostrarform(false);
	listar();

	$("#formulario").on("submit", function(e)
	{
		guardaryeditar(e);
	})
}

//Funcion limpiar
function limpiar()
{
	$("#idwsdat01").val("");  // Se hace esto para evitar chancar los datos cuando se agrega un nuevo registro.
	$("#descripcion").val("");
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
		$("#btnAgregar").show(); // Se vuelve a mostrar el boton agregar ya que estamos mostrando la lista
	}
}

//Funcion cancelarform
function cancelarform()
{
	limpiar();
	mostrarform(false);
}

function listar()
{
	tabla=$('#tbllistado').dataTable(
	{
		"aProcessing":true, //Activamos el procesamiento de datatables
		"aServerSide":true, //Paginación y filtrado realizados por el servidor
		dom: 'Bfrtip', //Definimos los elementos del control de tabla
		buttons:[

		],
		"ajax": {
			url: '../ajax/idiomas.php?op=listar',
			type: "get",
			dataType : "json",
			error: function (e){
				console.log(e.responseText);
			}
		},
		"bDestroy": true,
		"iDisplayLength": 5, //Paginacion de 5 en 5
		"order": [[ 1, "desc" ]] //Ordenar (columna, orden)
	}).DataTable();
}

// Funcion para guardar y editar
function guardaryeditar(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled",true);
	var formData = new 	FormData($("#formulario")[0]);

	$.ajax({
		url	: "../ajax/idiomas.php?op=guardaryeditar",
		type : "POST",
		data : formData,
		contentType : false,
		processData : false,
		success: function(datos)
		{
			switch (datos)
			{
				case '0':
				  mensaje='El idioma no se pudo Registrar.';
				  break;
				case '1':
				  mensaje='Idioma Registrado.';
				  break;
				case 'E':
				  mensaje='El Idioma que está Ingresando YA Existe.';
				  break;
			}
			bootbox.alert(mensaje);
			mostrarform(false);
			tabla.ajax.reload();
		}
	});
	limpiar();
	mostrarform(false);
	tabla.ajax.reload();
}

// Funcion que va a mostrar los datos de un registro en el formulario cuando se edita.
function mostrar(idwsdat01)
{
	$.post("../ajax/idiomas.php?op=mostrar",{idwsdat01 : idwsdat01}, function(data, status)
	{
		data = JSON.parse(data);
		mostrarform(true);

		$("#descripcion").val(data.descripcion);
 		$("#idwsdat01").val(data.idWsDat01);

	})
}

//Funcion para eliminar los registros
function eliminar(idwsdat01)
{
	bootbox.confirm("¿Está seguro que desea eliminar El Idioma?", function(result){
		if (result)
		{
			$.post("../ajax/idiomas.php?op=eliminar", {idwsdat01 : idwsdat01}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

init();