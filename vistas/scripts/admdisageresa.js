//Declaramos la variable global para el manejo de las tablas en el datatable
var tabla;
var tablaRedes;
var tablaMicroredes;

//Funcion que se ejecuta al inicio
function init()
{
	mostrarform(1);
	listar();
}

// Funcion mostrar formulario
function mostrarform(nro)
{
  switch (nro)
  {
  	case 1:
  	  // Visualizamos las disas/geresas
			$("#listageresa").show();
			$("#listaredes").hide();
			$("#listamicroredes").hide();
  	  break;
  	case 2:
  	  // Visualizamos las redes
			$("#listageresa").hide();
			$("#listaredes").show();
			$("#listamicroredes").hide();
  	  break;
  	case 3:
  	  // Visualizamos microredes
			$("#listageresa").hide();
			$("#listaredes").hide();
			$("#listamicroredes").show();
  	  break;  	  
  }
}

function listar()
{
	tabla=$('#tbllistado').dataTable(
	{
		"aProcessing":true, //Activamos el procesamiento de datatables
		"aServerSide":true, //Paginación y filtrado realizados por el servidor
		dom: 'Bfrtip', //Definimos los elementos del control de tabla
		buttons:[
			'excelHtml5',
			'pdf'
		],
		"ajax": {
			url: '../ajax/admdisageresa.php?op=listar',
			type: "get",
			dataType : "json",
			error: function (e){
				console.log(e.responseText);
			}
		},
		"bDestroy": true,
		"iDisplayLength": 5, //Paginacion de 5 en 5
		"order": [[ 0, "desc" ]] //Ordenar (columna, orden)
	}).DataTable();
}


function listarRedes(idDisa, descDisa)
{
	mostrarform(2);
	document.getElementById("lblGeresa").innerHTML = descDisa;
	tablaRedes=$('#tbllistared').dataTable(
	{
		"aProcessing":true, //Activamos el procesamiento de datatables
		"aServerSide":true, //Paginación y filtrado realizados por el servidor
		dom: 'Bfrtip', //Definimos los elementos del control de tabla
		buttons:[
			'excelHtml5',
			'pdf'
		],
		"ajax": {
			url: '../ajax/admdisageresa.php?op=listaRedes',
			type: "post",
			data: {idDisa:idDisa},
			dataType : "json",
			error: function (e){
				console.log(e.responseText);
			}
		},
		"bDestroy": true,
		"iDisplayLength": 5, //Paginacion de 5 en 5
		"order": [[ 0, "desc" ]] //Ordenar (columna, orden)
	}).DataTable();
}

function listarMicroRedes(idRed, descRed)
{
	mostrarform(3);
	document.getElementById("lblRed").innerHTML = descRed;

	tablaMicroredes=$('#tbllistamicrored').dataTable(
	{
		"aProcessing":true, //Activamos el procesamiento de datatables
		"aServerSide":true, //Paginación y filtrado realizados por el servidor
		dom: 'Bfrtip', //Definimos los elementos del control de tabla
		buttons:[
			'excelHtml5',
			'pdf'
		],
		"ajax": {
			url: '../ajax/admdisageresa.php?op=listaMicroRedes',
			type: "post",
			data: {idRed:idRed},
			dataType : "json",
			error: function (e){
				console.log(e.responseText);
			}
		},
		"bDestroy": true,
		"iDisplayLength": 5, //Paginacion de 5 en 5
		"order": [[ 0, "desc" ]] //Ordenar (columna, orden)
	}).DataTable();

}

init();