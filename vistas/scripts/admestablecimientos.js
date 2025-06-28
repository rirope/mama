//Declaramos la variable global para el manejo de las tablas en el datatable
var tabla;

//Funcion que se ejecuta al inicio
function init()
{
	var iddisa=$('#idDisaSel').val();
	var idred=$('#idRedSel').val();
	var idmred=$('#idMredSel').val();
	var idNiv = $('#idNiv').val();

	// De acuerdo al nivel del usuario se activran los filtro de acceso
	switch(idNiv)
	{
		case '06001':
		  //Nivel GERESA, se deshabilita el control de filtro de GERESA, los otros quedan activos
		  $("#iddisa").prop("disabled",true);
		  $("#iddisaUsu").prop("disabled",true);
		  break;

		case '06002':
		case '06007':
		  //Nivel Red o Consultor RED, se desactivan geresa y red, los demas quedan activos
		  $("#iddisa").prop("disabled",true);
		  $("#idred").prop("disabled",true);
		  $("#iddisaUsu").prop("disabled",true);
		  $("#idredUsu").prop("disabled",true);
		  break;

		case '06004':
		  //Nivel Microred, se desactivan geresa, red y microred, el establecimiento queda activo
		  $("#iddisa").prop("disabled",true);
		  $("#idred").prop("disabled",true);
		  $("#idmred").prop("disabled",true);
		  $("#iddisaUsu").prop("disabled",true);
		  $("#idredUsu").prop("disabled",true);
		  $("#idmredUsu").prop("disabled",true);
		  break;
		  
		case '06003':
		case '06005':
		case '06006':
		  //Nivel Establecimiento, se desactivan todos los filtros
		  $("#iddisa").prop("disabled",true);
		  $("#idred").prop("disabled",true);
		  $("#idmred").prop("disabled",true);
		  $("#idestab").prop("disabled",true);
		  $("#iddisaUsu").prop("disabled",true);
		  $("#idredUsu").prop("disabled",true);
		  $("#idmredUsu").prop("disabled",true);
		  break;
	}

	mostrarform(false);

	//Cargamos los items al select disa/geresa
	$.post("../ajax/admusuadmin.php?op=listaDisas",function(r){
	  $("#iddisa").html(r);
	  //Cargamos los items de redes
	  $.post("../ajax/admusuadmin.php?op=listaRedes", {iddisa:iddisa}, function(r){
      $("#idred").html(r);
      //Cargamos los items de las microredes
			$.post("../ajax/admusuadmin.php?op=listaMicRedes", {idred : idred}, function(r){
	      $("#idmred").html(r);
	      $('#iddisa').val(iddisa);
	      $('#idred').val(idred);
	      $('#idmred').val(idmred);

	      //Actualizamos los datos de los combos
	      $('#iddisa').selectpicker('refresh');
	      $('#idred').selectpicker('refresh');
	      $('#idmred').selectpicker('refresh');

		    listar();
			}); 
	  });
	});	

	$("#formulario").on("submit", function(e)
	{
		guardaryeditar(e);
	});

	// Cargamos los items al select lista de profesiones
	$.post("../ajax/admestablecimientos.php?op=listaTipoEstab", function(r){
        $("#idTipEstab").html(r);
        $('#idTipEstab').selectpicker('refresh');
	});

	// Cargamos la lista de niveles de acceso
	$.post("../ajax/admestablecimientos.php?op=listaCategEstab", function(r){
        $("#idCateg").html(r);
        $('#idCateg').selectpicker('refresh');
	});
}

//Funcion limpiar
function limpiar()
{
	var iddisa=$('#idDisaSel').val();
	var idred=$('#idRedSel').val();
	var idmred=$('#idMredSel').val();

	$("#idEstabl").val('');
	//Datos del usuario
	$("#codEstab").val('');
	$("#descEstab").val('');
	$("#idTipEstab").val('-Seleccione Tipo-');
	$("#idTipEstab").selectpicker('refresh');
	$("#idCateg").val('-Seleccione Categoria-');
	$("#idCateg").selectpicker('refresh');

	//Cargamos los items al select disa/geresa
	$.post("../ajax/admusuadmin.php?op=listaDisas",function(r){
	  $("#iddisaUsu").html(r);
	  //Cargamos los items de redes
	  $.post("../ajax/admusuadmin.php?op=listaRedes", {iddisa:iddisa}, function(r){
      $("#idredUsu").html(r);
      //Cargamos los items de las microredes
			$.post("../ajax/admusuadmin.php?op=listaMicRedes", {idred : idred}, function(r){
	      $("#idmredUsu").html(r);
	      $('#iddisaUsu').val(iddisa);
	      $('#idredUsu').val(idred);
	      $('#idmredUsu').val(idmred);

	      //Actualizamos los datos de los combos
	      $('#iddisaUsu').selectpicker('refresh');
	      $('#idredUsu').selectpicker('refresh');
	      $('#idmredUsu').selectpicker('refresh');
			}); 
	  });
	});	

	//Cargamos los items al select departamentos
	$.post("../ajax/admestablecimientos.php?op=listaDptos",function(r){
		$("#iddpto").html(r);
		$("#iddpto").selectpicker('refresh');
	});
	$("#idprov").val('-Seleccionar Provincia-');
	$("#idprov").selectpicker('refresh');
	$("#iddist").val('-Seleccionar Distrito-');
	$("#iddist").selectpicker('refresh');
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
		$("#btnAsignar").prop("disabled", true);
	}
	else
	{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
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
	var iddisa = $("#iddisa").val();
	var idred = $("#idred").val();
	var idmred = $("#idmred").val();
	var idestab = $("#idestab").val();
	var activo = $("#activo").val();

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
		dom: 'Bfrtip', //Definimos los elementos del control de tabla
		buttons:[
			'excelHtml5',
			'pdf'
		],
		"ajax": {
			url: '../ajax/admestablecimientos.php?op=listar',
			data:{iddisa:iddisa, idred:idred, idmred:idmred, activo:activo},
			type: "POST",
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

// Funcion para guardar y editar
function guardaryeditar(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled",true);
	// Datos nuevos o en edicion
	var idEstabl=$("#idEstabl").val();
	var codEstab=$("#codEstab").val();
	var descEstab=$("#descEstab").val();
	var idTipEstab=$("#idTipEstab").val();
	var idCateg=$("#idCateg").val();
	var iddisaUsu=$("#iddisaUsu").val();
	var idredUsu=$("#idredUsu").val();
	var idmredUsu=$("#idmredUsu").val();
	var iddpto=$("#iddpto").val();
	var idprov=$("#idprov").val();
	var iddist=$("#iddist").val();
	//Se rellena con ceros para tener el mismo formto que todos
	codEstab=codEstab.padStart(9,'0');

	if(validaDatosEstablecimiento())
	{
	  $.post('../ajax/admestablecimientos.php?op=guardaryeditar', {idEstabl:idEstabl,codEstab:codEstab,descEstab:descEstab,
	  		idTipEstab:idTipEstab,idCateg:idCateg,iddisaUsu:iddisaUsu,idredUsu:idredUsu,idmredUsu:idmredUsu,iddpto:iddpto,
	  		idprov:idprov,iddist:iddist}, function(data, status)
		{
			bootbox.alert(data);
			tabla.ajax.reload(null,false);
			limpiar();
			mostrarform(false);
		});
	}else{
		$("#btnGuardar").prop("disabled",false);
	}
}

// Funcion que va a mostrar los datos de un registro en el formulario cuando se edita.
function mostrar(idEstabl)
{
	$.post("../ajax/admestablecimientos.php?op=mostrar",{idEstabl : idEstabl}, function(data, status)
	{
		data = JSON.parse(data);
		mostrarform(true);

		$("#idEstabl").val(idEstabl);
		//Datos del usuario
		$("#codEstab").val(data.codEstab);
		$("#descEstab").val(data.descEstab);
		$("#idTipEstab").val(data.idTipEstab);
		$("#idTipEstab").selectpicker('refresh');
		$("#idCateg").val(data.idCateg);
		$("#idCateg").selectpicker('refresh');

		iddisa=data.disa_idDisa;
		idred=data.red_idRed;
		idmred=data.microRed_idMred;
		idDpto=data.dpto_idDpto;
		idProv=data.prov_idprov;
		idDist=data.dist_idDist;

		//Cargamos los items al select disa/geresa
		$.post("../ajax/admusuadmin.php?op=listaDisas",function(r){
		  $("#iddisaUsu").html(r);
		  //Cargamos los items de redes
		  $.post("../ajax/admusuadmin.php?op=listaRedes", {iddisa:iddisa}, function(r){
	      $("#idredUsu").html(r);
	      //Cargamos los items de las microredes
				$.post("../ajax/admusuadmin.php?op=listaMicRedes", {idred : idred}, function(r){
		      $("#idmredUsu").html(r);
		      $('#iddisaUsu').val(iddisa);
		      $('#idredUsu').val(idred);
		      $('#idmredUsu').val(idmred);

		      //Actualizamos los datos del los combos
		      $('#iddisaUsu').selectpicker('refresh');
		      $('#idredUsu').selectpicker('refresh');
		      $('#idmredUsu').selectpicker('refresh');
				}); 
		  });
		});	

		//Cargamos los items al select departamentos
		$.post("../ajax/admestablecimientos.php?op=listaDptos",function(r){
			$("#iddpto").html(r);
			$.post("../ajax/admestablecimientos.php?op=listaProv",{idDpto:idDpto},function(r){
				$("#idprov").html(r);
				$.post("../ajax/admestablecimientos.php?op=listaDist",{idProv:idProv},function(r){
					$("#iddist").html(r);
					$("#iddpto").val(idDpto);
					$("#idprov").val(idProv);
					$("#iddist").val(idDist);

					$("#iddpto").selectpicker('refresh');
					$("#idprov").selectpicker('refresh');
					$("#iddist").selectpicker('refresh');
				});
			});
		});
	});

}

//Función para desactivar registros
function desactivar(idEstabl)
{
	bootbox.confirm("¿Está Seguro de desactivar el Establecimiento?", function(result){
	if(result)
  {
  	$.post("../ajax/admestablecimientos.php?op=desactivar", {idEstabl : idEstabl}, function(e){
      tabla.ajax.reload(null,false);
  	});	
  }
})
}

//Función para activar registros
function activar(idEstabl)
{
	bootbox.confirm("¿Está Seguro de activar al Usuario?", function(result){
	if(result)
  {
  	$.post("../ajax/admestablecimientos.php?op=activar", {idEstabl : idEstabl}, function(e){
      tabla.ajax.reload(null,false);
  	});	
  }
})
}

//Funcion para eliminar los registros
function eliminar(idEstabl)
{
	bootbox.confirm("¿Está seguro que desea eliminar el Establecimiento?", function(result){
	if (result)
	{
		$.post("../ajax/admestablecimientos.php?op=eliminar", {idEstabl : idEstabl}, function(e){
			tabla.ajax.reload(null,false);
		});
	}
	})
}

//Funcion que carga las redes de acuerdo a la geresa seleccionada.
function cargarRedes(iddisa)
{
	  document.getElementById("idred").disabled=false; 
    //Cargamos los items al select red
	  $.post("../ajax/admusuadmin.php?op=listaRedes", {iddisa : iddisa}, function(r){
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
	$.post("../ajax/admusuadmin.php?op=listaMicRedes", {idred : idred}, function(r){
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
	$.post("../ajax/admusuadmin.php?op=listaEstablec", {idmred : idmred}, function(r){
      $("#idestab").html(r);
	    $('#idestab').selectpicker('refresh');
	});
}

//Funcion que carga las redes de acuerdo a la geresa seleccionada en el modal
function cargarRedesUsu(iddisa)
{
	  document.getElementById("idredUsu").disabled=false; 
    //Cargamos los items al select red
	  $.post("../ajax/admusuadmin.php?op=listaRedes", {iddisa : iddisa}, function(r){
        $("#idredUsu").html(r);
	      $('#idredUsu').selectpicker('refresh');
	    });
	  cargarMicroRedesUsu('0000');
}

//Funcion que carga las microredes de acuerdo a la red seleccionada.
function cargarMicroRedesUsu(idred)
{
	document.getElementById("idmredUsu").disabled=false; 
  //Cargamos los items al select microred
	$.post("../ajax/admusuadmin.php?op=listaMicRedes", {idred : idred}, function(r){
      $("#idmredUsu").html(r);
	    $('#idmredUsu').selectpicker('refresh');
	}); 
}

//Funcion que carga las provincias de acuerdo al departamento seleccionado
function cargarProv(idDpto)
{
  document.getElementById("idprov").disabled=false; 
  //Cargamos los items al select provincia
  $.post("../ajax/admestablecimientos.php?op=listaProv", {idDpto : idDpto}, function(r){
      $("#idprov").html(r);
      $('#idprov').selectpicker('refresh');
    });
  cargarDistr('000000');
}

//Funcion que carga los distritos de acuerdo a la provincia seleccionada.
function cargarDistr(idProv)
{
	document.getElementById("iddist").disabled=false; 
  //Cargamos los items al select microred
	$.post("../ajax/admestablecimientos.php?op=listaDist", {idProv : idProv}, function(r){
      $("#iddist").html(r);
	    $('#iddist').selectpicker('refresh');
	}); 
}

//Validar si los datos estan completo en lugar de acceso 
function validaDatosEstablecimiento()
{
	var iddisaUsu=$("#iddisaUsu").val();
	var idredUsu=$("#idredUsu").val();
	var idmredUsu=$("#idmredUsu").val();
	var iddpto=$("#iddpto").val();
	var idprov=$("#idprov").val();
	var iddist=$("#iddist").val();


	if (iddisaUsu=='00'){
		iddisaUsu='';
	}

  if (idredUsu=='0000'){
		idredUsu='';
	}

  if (idmredUsu=='000000' || idmredUsu==undefined){
		idmredUsu='';
	}

	if (iddpto=='00'){
		iddpto='';
	}

  if (idprov=='0000'){
		idprov='';
	}

  if (iddist=='000000' || iddist==undefined){
		iddist='';
	}


	if(iddisaUsu.length==0)
	{
		bootbox.alert('Seleccione la DISA/GERESA por favor.');
		return false;
	}

	if(idredUsu.length==0)
	{
		bootbox.alert('Seleccione la RED por favor.');
		return false;
	}

	if(idmredUsu.length==0)
	{
		bootbox.alert('Seleccione la MICRORED por favor.');
		return false;
	}

	if(iddpto.length==0)
	{
		bootbox.alert('Seleccione el DEPARTAMENTO por favor.');
		return false;
	}

	if(idprov.length==0)
	{
		bootbox.alert('Seleccione la PROVINCIA por favor.');
		return false;
	}

	if(iddist.length==0)
	{
		bootbox.alert('Seleccione el DISTRITO por favor.');
		return false;
	}

	return true;
}

init();