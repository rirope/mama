//Declaramos la variable global para el manejo de las tablas en el datatable
var tabla;
var tablaDetUsu;

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
		  $("#idestabUsu").prop("disabled",true);
		  break;
	}

	//Se rellena con ceros para tener el mismo tipo que en el combo
	idestab=idestab.padStart(10,'0');

	//$('#btnNvaGest').hide();
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
	      //Cargamos los establecimientos
				$.post("../ajax/admusuadmin.php?op=listaEstablec", {idmred : idmred}, function(r){
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

	$("#formulario").on("submit", function(e)
	{
		guardaryeditar(e);
	});

	// Cargamos la lista de niveles de acceso
	$.post("../ajax/admusuoperador.php?op=listaNivel", function(r){
        $("#idNivel").html(r);
        $('#idNivel').selectpicker('refresh');
	});

	// Cargamos los items al select lista de profesiones
	$.post("../ajax/admusuoperador.php?op=listaProf", function(r){
        $("#idProf").html(r);
        $('#idProf').selectpicker('refresh');
	});

	// Cargamos la lista de los servicios en el eess
	$.post("../ajax/admusuoperador.php?op=listaServ", function(r){
        $("#idServ").html(r);
        $('#idServ').selectpicker('refresh');
	});

	//Cargamos los items al select disa/geresa del modal
	$.post("../ajax/admusuadmin.php?op=listaDisas",function(r){
	  $("#iddisaUsu").html(r);
	  $("#iddisaUsu").selectpicker('refresh');
	});

	//Cargamos los items al select redes del modal
	$.post("../ajax/admusuadmin.php?op=listaRedes",function(r){
	  $("#idredUsu").html(r);
	  $("#idredUsu").selectpicker('refresh');
	});

	//Cargamos los items al select microredes del modal
	$.post("../ajax/admusuadmin.php?op=listaMicRedes",function(r){
	  $("#idmredUsu").html(r);
	  $("#idmredUsu").selectpicker('refresh');
	});

	//Cargamos los items al select establecimientos del modal
	$.post("../ajax/admusuadmin.php?op=listaEstablec",function(r){
	  $("#idestabUsu").html(r);
	  $("#idestabUsu").selectpicker('refresh');
	});

}

//Funcion limpiar
function limpiar()
{
	$("#idUsu").val('');
	$("#apePat").val('');
	$("#apeMat").val('');
	$("#nombres").val('');
	$("#idProf").val('');
	$("#idProf").selectpicker('refresh');
	$("#nroDni").val('');
	$("#nroDniAnt").val('');
	$("#correoElec").val('');
	$("#nomUsu").val('');
	$("#passUsu").val('');
	// Limpiamos las filas de la tabla de detalles de lugares asignados
	// Hacemos esta llamada para limpiar el datatable
	var idUsu=0;
	tablaDetUsu=$('#tblListaDet').dataTable(
	{
		"aProcessing":true, //Activamos el procesamiento de datatables
		"aServerSide":true, //Paginación y filtrado realizados por el servidor
		"bFilter": false, // Quitamos la busqueda del datatable
		dom: 'Bfrtip', //Definimos los elementos del control de tabla
		buttons:[

		],
		"ajax": {
			url: '../ajax/admusuadmin.php?op=listaDetUsu',
			data:{idUsu : idUsu},
			type: "POST",
			dataType : "json",
			error: function (e){
				console.log(e.responseText);
			}
		},
		"bDestroy": true,
		"iDisplayLength": 10, //Paginacion de 10 en 10
		"order": [[ 1, "desc" ]] //Ordenar (columna, orden)
	}).DataTable();

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
			url: '../ajax/admusuoperador.php?op=listar',
			data:{iddisa:iddisa, idred:idred, idmred:idmred, idestab:idestab},
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
	var llSwNuevo=true;
	var llSwValidarDniAnt=false;
	var lcAccion='';

	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled",true);
	// Datos del usuario nuevo o en edicion
	var	idUsu=$("#idUsu").val();
	var apePat=$("#apePat").val();
	var apeMat=$("#apeMat").val();
	var nombres=$("#nombres").val();
	var idProf=$("#idProf").val();
	var nroDni=$("#nroDni").val();
	var nroDniAnt=$("#nroDniAnt").val();
	var correoElec=$("#correoElec").val();
	var nomUsu=$("#nomUsu").val();
	var passUsu=$("#passUsu").val();

	if(idUsu=='')
	{
		//Es un nuevo ingreso
		$("#btnAsignar").prop("disabled",false);
		lcAccion='N'
	}else{
		//Se hicieron cambios en los datos
		llSwNuevo=false;
		lcAccion='E'
	}

	if(!llSwNuevo)
	{ //Verificamos si hubo cambio en el nro de dni
		if(nroDni!=nroDniAnt)
		{
			llSwValidarDniAnt=true;
		}
	}

	if(llSwNuevo || llSwValidarDniAnt)
	{
		// Se lanza proceso para validar si el nro de dni ya existe en la tabla perssalud
		$.post('../ajax/admusuoperador.php?op=validarDni',{nroDni:nroDni}, function(data,status)
		{
			var rspta=data;
			if(rspta=='1')
			{
				// El nro de dni ya existe, no se puede grabar
				$("#btnGuardar").prop("disabled",false);
				$("#btnAsignar").prop("disabled",true);
				bootbox.alert('El Numero de DNI ya existe, verifique por favor.');
			}else{
				// Los datos pueden grabarse, no hay dni duplicado
				grabarDatosUsuarioOperador(idUsu,apePat,apeMat,nombres,idProf,nroDni,correoElec,nomUsu,passUsu,lcAccion)
			}
		})
	}else{
		grabarDatosUsuarioOperador(idUsu,apePat,apeMat,nombres,idProf,nroDni,correoElec,nomUsu,passUsu,lcAccion)
	}
}

//Funcion que busca los datos del operador en la tabla mstrpers para traerse los datos basicos ei existiera
function buscarDniOperador()
{
	var nroDni=$('#nroDni').val();
	debugger;
	if(nroDni.length==0)
	{
		//Si el campo esta vacio lanzamos mensaje de alerta
		bootbox.alert('Debe ingresar el número de DNI para buscar los datos del usuario.');
	}else{
		//Procedemos a la búsqueda
		$.post('../ajax/admusuoperador.php?op=buscarDniOperador',{nroDni:nroDni},function(data,status){
			data = JSON.parse(data);
			if(data==null)
			{
				$('#apePat').val('');
				$('#apeMat').val('');
				$('#nombres').val('');
				bootbox.alert('El número de DNI del Operador no esta en la tabla mstrpers del HIS.</br>Debe ingresar los datos completos.');
			}else{
				$('#apePat').val(data.apePat);
				$('#apeMat').val(data.apeMat);
				$('#nombres').val(data.nombres);
			}

		});
	}
}

//Funcion que graba los datos del usuario que se registra como nuevo o en modo de edicion
function grabarDatosUsuarioOperador(idUsu,apePat,apeMat,nombres,idProf,nroDni,correoElec,nomUsu,passUsu,tcAccion)
{
  $.post('../ajax/admusuoperador.php?op=guardaryeditar', {idUsu:idUsu,apePat:apePat,apeMat:apeMat,nombres:nombres,
  		idProf:idProf,nroDni:nroDni,correoElec:correoElec,nomUsu:nomUsu,passUsu:passUsu}, function(data, status)
	{
		var idUsu=data;
		$("#idUsu").val(idUsu);
		if (idUsu!='' || idUsu!=undefined)
		{
			if(tcAccion=='N')
			{ 
				lcMensaje='Los Datos del Nuevo Usuario fueron registrados. Debe asignar el lugar de trabajo.';
				tabla.ajax.reload();
				mostrarDetLugar(0,tcAccion);
			}else{
				lcMensaje='Los Datos del Usuario fueron actualizados.';
				tabla.ajax.reload(null,false);
				mostrarform(false);
				limpiar();
			}
			bootbox.alert(lcMensaje);
		}else{
			bootbox.alert('Los Datos del Nuevo Usuario No se pudieron registrar.');
		  mostrarform(false);
		  limpiar();
		}
  });	
}

// Funcion que va a mostrar los datos de un registro en el formulario cuando se edita.
function mostrar(idUsu)
{
	$.post("../ajax/admusuoperador.php?op=mostrar",{idUsu : idUsu}, function(data, status)
	{
		data = JSON.parse(data);
		mostrarform(true);
		$("#btnAsignar").prop("disabled", false);

		$("#idUsu").val(idUsu);
		//Datos del usuario
		$("#apePat").val(data.apePat);
		$("#apeMat").val(data.apeMat);
		$("#nombres").val(data.nombres);
		$("#idProf").val(data.idProf);
		$("#idProf").selectpicker('refresh');
		$("#nroDni").val(data.nroDni);
		$("#nroDniAnt").val(data.nroDni);
		$("#correoElec").val(data.email);
		$("#nomUsu").val(data.name);
		$("#passUsu").val(data.password);
		// Llamamos a los datos de detalle del usuario
		tablaDetUsu=$('#tblListaDet').dataTable(
		{
			"aProcessing":true, //Activamos el procesamiento de datatables
			"aServerSide":true, //Paginación y filtrado realizados por el servidor
			"bFilter": false, // Quitamos la busqueda del datatable
			dom: 'Bfrtip', //Definimos los elementos del control de tabla
			buttons:[

			],
			"ajax": {
				url: '../ajax/admusuoperador.php?op=listaDetUsu',
				data:{idUsu : idUsu},
				type: "POST",
				dataType : "json",
				error: function (e){
					console.log(e.responseText);
				}
			},
			"bDestroy": true,
			"iDisplayLength": 10, //Paginacion de 10 en 10
			"order": [[ 1, "desc" ]] //Ordenar (columna, orden)
		}).DataTable();

	});

}

//Eliminar al usuario seleccionado con todos sus datos de detalles
function eliminarUsuOper(idUsu)
{
	bootbox.confirm("¿Está Seguro de Eliminar al Usuario?</br>Perderá todos los datos y registros trabajados por este usuario.", function(result)
	{
		if(result)
	  {
	  	$.post("../ajax/admusuoperador.php?op=eliminarUsuOper", {idUsu : idUsu}, function(e){
	      tabla.ajax.reload(null, false);
	  	});	
	  }
	})	
}

//Función para desactivar registros
function desactivarDet(idDet)
{
	bootbox.confirm("¿Está Seguro de desactivar al Usuario?", function(result){
	if(result)
  {
  	$.post("../ajax/admusuoperador.php?op=desactivar", {idDet : idDet}, function(e){
      tablaDetUsu.ajax.reload(null,false);
      tabla.ajax.reload(null,false);
  	});	
  }
})
}

//Función para activar registros
function activarDet(idDet)
{
	bootbox.confirm("¿Está Seguro de activar al Usuario?", function(result){
	if(result)
  {
  	$.post("../ajax/admusuoperador.php?op=activar", {idDet : idDet}, function(e){
      tablaDetUsu.ajax.reload(null,false);
      tabla.ajax.reload(null,false);
  	});	
  }
})
}

//Funcion para eliminar los registros
function eliminarDet(idDet)
{
	bootbox.confirm("¿Está seguro que desea eliminar El Registro del Usuario?", function(result){
	if (result)
	{
		$.post("../ajax/admusuoperador.php?op=eliminar", {idDet : idDet}, function(e){
			tablaDetUsu.ajax.reload(null,false);
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

function mostrarDetLugar(idDet, tcAccion)
{
	//tcAccion: Va a tener 2 valores, E-Editar, N-Nuevo
  // Mostramos el formulario de detalles
  $('#nuevoUsuarioOperador').modal('show');
  if (tcAccion=='E')
  {
  	//Edicion del registro
  	//Indicador para grabar datos
  	$("#tcAccion").val('E');
		$("#btnGrabaUsuario").prop("disabled", false);
		$("#idNivel").prop("disabled", false);
		$.post("../ajax/admusuoperador.php?op=mostrarDetLugar",{idDet : idDet}, function(data, status)
		{
			var iddisa;
			var idred;
			var idmred;
			data = JSON.parse(data);

  		$("#idDet").val(idDet);
			//Datos del detalle
			$("#idNivel").val(data.idNivel);
			$("#idNivel").selectpicker('refresh');
			$("#idServ").val(data.idServ);
			$("#idServ").selectpicker('refresh');
			$("#fechaReg").val(data.fechaIni);

			iddisa=data.idDisa;
			idred=data.idRed;
			idmred=data.idMred;
			idestab=data.idEstabl;
			idestab=idestab.padStart(10,'0');

			//Cargamos los items al select disa/geresa
			$.post("../ajax/admusuadmin.php?op=listaDisas",function(r){
			  $("#iddisaUsu").html(r);
			  //Cargamos los items de redes
			  $.post("../ajax/admusuadmin.php?op=listaRedes", {iddisa:iddisa}, function(r){
		      $("#idredUsu").html(r);
		      //Cargamos los items de las microredes
					$.post("../ajax/admusuadmin.php?op=listaMicRedes", {idred : idred}, function(r){
			      $("#idmredUsu").html(r);
			      //Cargamos los items de los eess
			      $.post("../ajax/admusuadmin.php?op=listaEstablec", {idmred : idmred}, function(r){
			      	$("#idestabUsu").html(r);
				      $('#iddisaUsu').val(iddisa);
				      $('#idredUsu').val(idred);
				      $('#idmredUsu').val(idmred);
				      $('#idestabUsu').val(idestab);

				      //Actualizamos los datos del los combos
				      $('#iddisaUsu').selectpicker('refresh');
				      $('#idredUsu').selectpicker('refresh');
				      $('#idmredUsu').selectpicker('refresh');
				      $('#idestabUsu').selectpicker('refresh');

			      });
					}); 
			  });
			});	
		});
  }else{
  	// Nuevo Registro, limpianos los controles
		var iddisa=$("#iddisa").val();;
		var idred=$("#idred").val();
		var idmred=$("#idmred").val();
		var idEstab=$("#idestab").val();
		var fechaHoy=$("#fechaHoy").val();

		$("#btnGrabaUsuario").prop("disabled", false);
		$("#idNivel").prop("disabled", false);

		$("#fechaReg").val(fechaHoy);

		//Indicador para grabar datos
 		$("#tcAccion").val('N');
 		//Limpiamos controles
  	$("#idDet").val('');

		$("#idNivel").val('-Seleccionar Nivel-');
		$("#idNivel").selectpicker('refresh');

		$("#idServ").val('-Seleccionar Servicio-');
		$("#idServ").selectpicker('refresh');

		//Cargamos los items al select disa/geresa al que pertenece este usuario
		$.post("../ajax/admusuadmin.php?op=listaDisas",function(r){
		  $("#iddisaUsu").html(r);
		  $.post("../ajax/admusuadmin.php?op=listaRedes", {iddisa:iddisa}, function(r){
	      $("#idredUsu").html(r);
	      $.post("../ajax/admusuadmin.php?op=listaMicRedes",{idred : idred}, function(r){
	      	$("#idmredUsu").html(r);
	      	$.post('../ajax/admusuadmin.php?op=listaEstablec',{idmred:idmred},function(r){
	      		$("#idestabUsu").html(r);
				    $('#iddisaUsu').val(iddisa);
				    $('#idredUsu').val(idred);
				    $('#idmredUsu').val(idmred);
				    $('#idestabUsu').val(idestab);

				    $('#iddisaUsu').selectpicker('refresh');
				    $('#idredUsu').selectpicker('refresh');
				    $('#idmredUsu').selectpicker('refresh');
				    $('#idestabUsu').selectpicker('refresh');
	      	})
	      });
		  });
		});

  }
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
	cargarEstablUsu('0');
}

//Funcion que carga los establecimientos de acuerdo a la microred seleccionada.
function cargarEstablUsu(idmred)
{
	document.getElementById("idestabUsu").disabled=false; 
    //Cargamos los items al select establec
	$.post("../ajax/admusuadmin.php?op=listaEstablec", {idmred : idmred}, function(r){
      $("#idestabUsu").html(r);
	    $('#idestabUsu').selectpicker('refresh');
	});
}

//Funcion que graba los datos del lugar de acceso que se le asigna al usuario
function guardarLugarAcceso()
{
	//Validamos si tenemos los datos completos para grabar
	if(validaLugarAcceso())
	{
		//Primero verificamos si la accion de guardar es de edicion o nuevo registro
		var cAccion=$("#tcAccion").val();
		if(cAccion=='N')
		{
			//Se ingresa un NUEVO lugar de acceso
			var idUsu=$("#idUsu").val(); // Se obtiene el ID de nuevo usuario creado
			var idNivel=$("#idNivel").val();
			var idServ=$("#idServ").val();
			var iddisaUsu=$("#iddisaUsu").val();
			var idredUsu=$("#idredUsu").val();
			var idmredUsu=$("#idmredUsu").val();
			var idestabUsu=$("#idestabUsu").val();

			if (iddisaUsu=='00'){
				iddisaUsu='';
			}

		  if (idredUsu=='0000'){
				idredUsu='';
			}

		  if (idmredUsu=='000000' || idmredUsu==undefined){
				idmredUsu='';
			}

			$.post("../ajax/admusuoperador.php?op=nvoLugarAcceso",{idUsu:idUsu, idNivel:idNivel, idServ:idServ, iddisaUsu:iddisaUsu, idredUsu:idredUsu,
				idmredUsu:idmredUsu, idestabUsu:idestabUsu}, function(data, result)
				{
					//Cargamos la tabla con los nuevos datos
					tablaDetUsu=$('#tblListaDet').dataTable(
					{
						"aProcessing":true, 
						"aServerSide":true, 
						"bFilter": false, 
						dom: 'Bfrtip', 
						buttons:[

						],
						"ajax": {
							url: '../ajax/admusuoperador.php?op=listaDetUsu',
							data:{idUsu : idUsu},
							type: "POST",
							dataType : "json",
							error: function (e){
								console.log(e.responseText);
							}
						},
						"bDestroy": true,
						"iDisplayLength": 10, //Paginacion de 10 en 10
						"order": [[ 1, "desc" ]] //Ordenar (columna, orden)
					}).DataTable();
					//Recargamos la tabla con la lista de usuarios principal
				  tabla.ajax.reload(null,false);
				});
		}else{
			//Se está haciendo EDICION del lugar de acceso y se cambió algún dato, establecimiento o servicio
			var idDet=$("#idDet").val();
			var idestabUsu=$("#idestabUsu").val();
			var idServ=$("#idServ").val();
			var idNivel=$("#idNivel").val();

			$.post("../ajax/admusuoperador.php?op=edtLugarAcceso",{idDet:idDet, idNivel:idNivel, idServ:idServ, idestabUsu:idestabUsu}, function(e)
				{
					//bootbox.alert(e);
					tablaDetUsu.ajax.reload(null,false);
				});

		}
		$('#nuevoUsuarioOperador').modal('hide');
	}
}

//Validar si los datos estan completo en lugar de acceso 
function validaLugarAcceso()
{
	var idNivel=$("#idNivel").val();
	var idServ=$("#idServ").val();
	var iddisaUsu=$("#iddisaUsu").val();
	var idredUsu=$("#idredUsu").val();
	var idmredUsu=$("#idmredUsu").val();
	var idestabUsu=$("#idestabUsu").val();

	if (iddisaUsu=='00'){
		iddisaUsu='';
	}

  if (idredUsu=='0000'){
		idredUsu='';
	}

  if (idmredUsu=='000000' || idmredUsu==undefined){
		idmredUsu='';
	}

  if (idestabUsu=='0' || idestabUsu==undefined){
		idestabUsu='';
	}


	if(idNivel.length==0)
	{
		bootbox.alert('Seleccione el NIVEL por favor.');
		return false;
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
		bootbox.alert('Seleccione la MICRO RED por favor.');
		return false;
	}

	if(idestabUsu.length==0)
	{
		bootbox.alert('Seleccione el ESTABLECIMIENTO por favor.');
		return false;
	}

	if(idServ.length==0)
	{
		bootbox.alert('Seleccione el SERVICIO por favor.');
		return false;
	}

	return true;
}

//Llenamos los datos del nombre del usuario y clave con el valor del DNI que se esta ingresando
$(document).ready(function(){
	$('#nroDni').keyup(function(){
		var valorDni=$('#nroDni').val();
		var idUsu=$("#idUsu").val();
		//nSolo obtenemos el nombre de usuario y password igual del DNI cuando se esta ingresando nuevo usuario
		if (idUsu=='' || idUsu==undefined)
		  {$('#nomUsu').val(valorDni);}

		if (idUsu=='' || idUsu==undefined)
			{$('#passUsu').val(valorDni);}
	})
})

// Funcion que permite validar numeros en un campo especificado
function soloNumeros(e, tnTam, tcNombreCtrl)
{
	// tnTam: El numero de digitos que se quiere controlar
	// tcNombreCtrl: Nombre del control que se quiere validar

  // capturamos la tecla pulsada
   var teclaPulsada=window.event ? window.event.keyCode:e.which;
   // capturamos el contenido del input
   var valor=document.getElementById(tcNombreCtrl).value;

   if(valor.length<tnTam)
	{
	  // 13 = tecla enter
	  // Si el usuario pulsa la tecla enter o el punto y no hay ningun otro
	  // punto
	  if(teclaPulsada==13)
	  {
	    return true;
	  }

	   // devolvemos true o false dependiendo de si es numerico o no
	   return /\d/.test(String.fromCharCode(teclaPulsada));
	}else{
	    return false;
	}
}

init();