//Declaramos la variable global para el manejo de las tablas en el datatable
var tabla;

//Funcion que se ejecuta al inicio
function init()
{
	var iddisa=$('#idDisaSel').val();
	var idred=$('#idRedSel').val();
	var idmred=$('#idMredSel').val();
	var idestab=$('#idEstablSel').val();
	var idNiv = $('#idNiv').val();

	// De acuerdo al nivel del usuario se activran los filtro de acceso
	switch(idNiv)
	{
		case '06001':
		  //Nivel GERESA, se deshabilita el control de filtro de GERESA, los otros quedan activos
		  $("#iddisa").prop("disabled",true);
		  break;

		case '06002':
		case '06007':
		  //Nivel Red o Consultor RED, se desactivan geresa y red, los demas quedan activos
		  $("#iddisa").prop("disabled",true);
		  $("#idred").prop("disabled",true);
		  break;

		case '06004':
		  //Nivel Microred, se desactivan geresa, red y microred, el establecimiento queda activo
		  $("#iddisa").prop("disabled",true);
		  $("#idred").prop("disabled",true);
		  $("#idmred").prop("disabled",true);
		  break;
		  
		case '06003':
		case '06005':
		case '06006':
		  //Nivel Establecimiento, se desactivan todos los filtros
		  $("#iddisa").prop("disabled",true);
		  $("#idred").prop("disabled",true);
		  $("#idmred").prop("disabled",true);
		  $("#idestab").prop("disabled",true);
		  break;
	}

	//Se rellena con ceros para tener el mismo tipo que en el combo
	idestab=idestab.padStart(10,'0');

	//Cargamos los items al select disa/geresa
	$.post("../ajax/reporte08.php?op=listaDisas",function(r){
	  $("#iddisa").html(r);
	  //Cargamos los items de redes
	  $.post("../ajax/reporte08.php?op=listaRedes", {iddisa:iddisa}, function(r){
      $("#idred").html(r);
      //Cargamos los items de las microredes
			$.post("../ajax/reporte08.php?op=listaMicRedes", {idred : idred}, function(r){
	      $("#idmred").html(r);
	      //Cargamos los establecimientos
				$.post("../ajax/reporte08.php?op=listaEstablec", {idmred : idmred}, function(r){
			      $("#idestab").html(r);
			      $('#iddisa').val(iddisa);
			      $('#idred').val(idred);
			      $('#idmred').val(idmred);
			      $('#idestab').val(idestab);

			      //Actualizamos los datos del los combos
			      $('#iddisa').selectpicker('refresh');
			      $('#idred').selectpicker('refresh');
			      $('#idmred').selectpicker('refresh');
				    $('#idestab').selectpicker('refresh');

				    listar();
				});
			}); 
	  });
	});	


}

//Función que lista el reporte de acuerdo a los parámetros dados
function listar()
{
	var iddisa = $("#iddisa").val();
	var idred = $("#idred").val();
	var idmred = $("#idmred").val();
	var idestab = $("#idestab").val();

	if (iddisa=='00'){
		iddisa='';
	}

  if (idred=='0000'){
		idred='';
	}

  if (idmred=='000000'){
		idmred='';
	}

  if (idestab=='0'){
		idestab='';
	}

	tabla=$('#tbllistado').dataTable(
	{
		"aProcessing":true, //Activamos el procesamiento de datatables
		"aServerSide":true, //Paginación y filtrado realizados por el servidor
		"bFilter":false,
		dom: 'Bfrtip', //Definimos los elementos del control de tabla
		buttons:[
			'excelHtml5',
			'pdf'
		],
		"ajax": {
			url: '../ajax/reporte08.php?op=listar',
			data:{iddisa:iddisa, idred:idred, idmred:idmred, idestab:idestab},
			type: "POST",
			dataType : "json",
			error: function (e){
				console.log(e.responseText);
			}
		},
		"bDestroy": true,
		"iDisplayLength": 10,
		"order": [[ 5, "desc" ]] //Ordenar (columna, orden)
	}).DataTable();
}

//Funcion que carga las redes de acuerdo a la geresa seleccionada.
function cargarRedes(iddisa)
{
	  document.getElementById("idred").disabled=false; 
    //Cargamos los items al select red
	  $.post("../ajax/reporte08.php?op=listaRedes", {iddisa : iddisa}, function(r){
        $("#idred").html(r);
	      $('#idred').selectpicker('refresh');
	    });
	  cargarMicroRedes('0000');
	  cargarEstabl('0');
}

//Funcion que carga las redes de acuerdo a la geresa seleccionada.
function cargarMicroRedes(idred)
{
	document.getElementById("idmred").disabled=false; 
    //Cargamos los items al select microred
	$.post("../ajax/reporte08.php?op=listaMicRedes", {idred : idred}, function(r){
      $("#idmred").html(r);
	    $('#idmred').selectpicker('refresh');
	}); 
	cargarEstabl('0');
}

//Funcion que carga las redes de acuerdo a la geresa seleccionada.
function cargarEstabl(idmred)
{
	document.getElementById("idestab").disabled=false; 
    //Cargamos los items al select establec
	$.post("../ajax/reporte08.php?op=listaEstablec", {idmred : idmred}, function(r){
      $("#idestab").html(r);
	    $('#idestab').selectpicker('refresh');
	});
}


init();