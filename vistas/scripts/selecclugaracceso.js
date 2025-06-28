var tabla;

function init()
{
  listar();

}

function listar()
{
    var idUsr=$('#idUsr').val();
	tabla=$('#tbllistado').dataTable(
	{
		"aProcessing":true, //Activamos el procesamiento de datatables
		"aServerSide":true, //Paginación y filtrado realizados por el servidor
		"bFilter": false, // Quitamos la busqueda del datatable
		dom: 'Bfrtip', //Definimos los elementos del control de tabla
		buttons:[
		],
		"ajax": {
			url: '../ajax/selecclugaracceso.php?op=listarLugares',
			type: "POST",
			data: {idUsr:idUsr},
			dataType : "json",
			error: function (e){
				console.log(e.responseText);
			}
		},
		"bDestroy": true,
		"iDisplayLength": 5, //Paginacion de 5 en 5
		"order": [[ 0, "asc" ]] //Ordenar (columna, orden)
	}).DataTable();
}

function mostrarApp(idEstabl, idNivel)
{
   //alert('IdEstabl:'+idEstabl+' IdNivel:'+idNivel);
}


init();