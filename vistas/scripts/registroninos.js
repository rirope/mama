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

	//Ocultamos el boton de impresion del formato
	$("#formatoNino").hide();
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

	$('#btnNvaGest').hide();
	mostrarform(false);

	//Cargamos los items al select disa/geresa
	$.post("../ajax/registrogestantes.php?op=listaDisas",function(r){
	  $("#iddisa").html(r);
	  //Cargamos los items de redes
	  $.post("../ajax/registrogestantes.php?op=listaRedes", {iddisa:iddisa}, function(r){
      $("#idred").html(r);
      //Cargamos los items de las microredes
			$.post("../ajax/registrogestantes.php?op=listaMicRedes", {idred : idred}, function(r){
	      $("#idmred").html(r);
	      //Cargamos los establecimientos
				$.post("../ajax/registrogestantes.php?op=listaEstablec", {idmred : idmred}, function(r){
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

	// Cargamos los items al select gradoinstruccion
	$.post("../ajax/registrogestantes.php?op=listaGrInst", function(r){
        $("#idGrInst").html(r);
        $('#idGrInst').selectpicker('refresh');
	});

	// Cargamos los items al select Tipos de Documentos de Identidad en formulario registro
	$.post("../ajax/registrogestantes.php?op=listaDocIdent", function(r){
	    $("#tipoDocIdent").html(r);
	    $('#tipoDocIdent').selectpicker('refresh');

	    $("#tipoDiNino").html(r);
	    $('#tipoDiNino').selectpicker('refresh');
	});


	// Cargamos los items al select Tipos de Documentos de Identidad en el modal para registro de nuevos niños
	$.post("../ajax/registrogestantes.php?op=listaDocIdent", function(r){
        $("#tipoDi").html(r);
        $('#tipoDi').selectpicker('refresh');

        $("#tipoDiImp").html(r);
        $('#tipoDiImp').selectpicker('refresh');

	});

	// Cargamos los items al select programa social
	$.post("../ajax/registrogestantes.php?op=listaProgSoc", function(r){
        $("#progSoc").html(r);
        $('#progSoc').selectpicker('refresh');

        $("#progSocNino").html(r);
        $('#progSocNino').selectpicker('refresh');
	});

	// Cargamos los items al select idioma para el mensaje de voz
	$.post("../ajax/registrogestantes.php?op=listaIdiomaMv", function(r){
        $("#msgVoz").html(r);
        $('#msgVoz').selectpicker('refresh');
	});

}

//Funcion limpiar
function limpiar()
{
	$("#idNino").val("");  // Se hace esto para evitar chancar los datos cuando se agrega un nuevo registro.
	$("#tipoDocIdent").val("DNI");
	$('#tipoDocIdent').selectpicker('refresh');
	$("#nroDocIdent").val("");
	$("#apePat").val("");
	$("#apeMat").val("");
	$("#nombres").val("");
	$("#fechaNacMama").val("");
	$("#idGrInst").val("(NO APLICA)");
	$("#idGrInst").selectpicker('refresh');
	$("#celularMadre").val("");
	$("#nroCelPrincipal").val("");
	$("#celularAcomp").val("");
	$("#progSoc").val("");
	$('#progSoc').selectpicker('refresh');
	$("#msgVoz").val("");
	$('#msgVoz').selectpicker('refresh');
	$("#nroDiNino").val("");
	$("#hClFam").val("");
	$("#hClNino").val("");
	$("#fechaNacNino").val("");
	$("#fechaAtcNino").val("");
	$("#progSocNino").val("");
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
	var fechaIni = $("#fechaIni").val();
	var fechaFin = $("#fechaFin").val();
	var iddisa = $("#iddisa").val();
	var idred = $("#idred").val();
	var idmred = $("#idmred").val();
	var idestab = $("#idestab").val();
	var estado= $("#estado").val();
	var region=$("#iddisa option:selected").text();

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
			'pdf'
		],
		"ajax": {
			url: '../ajax/registroninos.php?op=listar',
			data:{fechaIni:fechaIni, fechaFin:fechaFin, iddisa:iddisa, idred:idred, idmred:idmred, idestab:idestab, estado:estado, region:region},
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
	var region=$("#iddisa option:selected").text();
	
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled",true);

	var	idNino=$("#idNino").val();
	//Datos de la Mamá
	//var tipoDocIdent=$('#tipoDocIdent option:selected').text();
	var tipoDocIdent=$('#tipoDocIdent').val();
	var nroDocIdent=$("#nroDocIdent").val();
	var apePat=$("#apePat").val();
	var apeMat=$("#apeMat").val();
	var nombres=$("#nombres").val();
	var fechaNacMama=$("#fechaNacMama").val();
	var idGrInst=$("#idGrInst").val();
	var celularMadre=$("#celularMadre").val();
	var nroCelPrincipal=$("#nroCelPrincipal").val();
	var celularAcomp=$("#celularAcomp").val();
	var progSoc=$("#progSoc").val();
	var msgVoz=$("#msgVoz").val();
	//Datos de Niño
	//var tipoDiNino=$('#tipoDiNino option:selected').text();
	var tipoDiNino=$('#tipoDiNino').val();
	var nroDiNino=$("#nroDiNino").val();
	var hClFam=$("#hClFam").val();
	var hClNino=$("#hClNino").val();
	var fechaNacNino=$("#fechaNacNino").val();
	var fechaAtcNino=$("#fechaAtcNino").val();
	var progSocNino=$("#progSocNino").val();
	//Esta variable es para cuando el usuario que registra es administrador, para el operador se usa la varible de sesión.
	var idestab=$('#idestab').val();

	if (validaRegistroDatosNinos(idNino, tipoDiNino, nroDiNino, celularMadre, celularAcomp, fechaNacNino, fechaAtcNino))
	{
 		//Verificamos si el niño es nuevo o se esta modificando datos
		if(idNino.length==0)
		{
			//Se está registrando un nuevo niño.
			//Verificamos que el dni del niño no exista en la BD
			$.post('../ajax/registroninos.php?op=existedninino',{nroDiNino:nroDiNino},function(data, status){
				if(data=='1')
				{
					//El niño existe en la BD
					bootbox.alert('El DNI del niño que esta ingresando YA EXISTE en la Base de Datos.</br>Verifique por Favor.');
					$("#btnGuardar").prop("disabled",false);
				}else{
					//Como no existe registramos los datos del niño
				  $.post('../ajax/registroninos.php?op=guardaryeditar', {idNino:idNino, tipoDocIdent:tipoDocIdent, nroDocIdent:nroDocIdent, 
				  		apePat:apePat, apeMat:apeMat, nombres:nombres, fechaNacMama:fechaNacMama, idGrInst:idGrInst, 
							celularMadre:celularMadre, celularAcomp:celularAcomp, progSoc:progSoc, msgVoz:msgVoz, tipoDiNino: tipoDiNino, 
							nroDiNino:nroDiNino, hClFam:hClFam, hClNino:hClNino, fechaNacNino:fechaNacNino, fechaAtcNino:fechaAtcNino, 
							progSocNino:progSocNino, idestab:idestab}, function(data, status)
					{
						var nvoId=data;
						if (nvoId>0 || nvoId!=undefined)
						{
							$("#idNino").val(nvoId);
							$("#formatoNino").show();
							bootbox.alert('Los Datos del Niño han sido Registrados Correctamente.');
							$("#formatoNino").attr("href", "../reportes/formatonino.php?idNino="+nvoId+"&region="+region);
						}else{
							bootbox.alert('No se pudo registrar los Datos del Niño. Salga del sistema e intente de Nuevo.');
						}
						//bootbox.alert(data);
						//mostrarform(false);
						//limpiar();
						tabla.ajax.reload(null,false);
				  });
				}
			});
		}else{
			//Se está modificando datos del niño.
			var nroDiNinoAnt=$('#nroDiNinoAnt').val();
			//Verificamos si hubo cambios en el numero de documento de identidad
			if(nroDiNino!=nroDiNinoAnt)
			{
				//El numero de documento de identificacion del niño ha sido modificado, verificamos si el numero modificado existe
				$.post('../ajax/registroninos.php?op=existedninino',{nroDiNino:nroDiNino},function(data, status){
					if(data=='1')
					{
						//El niño existe en la BD
						bootbox.alert('El DNI del niño que esta ingresando YA EXISTE en la Base de Datos.</br>Verifique por Favor.');
						$("#btnGuardar").prop("disabled",false);
					}else{
						//Como no existe registramos los datos del niño
					  $.post('../ajax/registroninos.php?op=guardaryeditar', {idNino:idNino, tipoDocIdent:tipoDocIdent, nroDocIdent:nroDocIdent, 
					  		apePat:apePat, apeMat:apeMat, nombres:nombres, fechaNacMama:fechaNacMama, idGrInst:idGrInst, 
								celularMadre:celularMadre, celularAcomp:celularAcomp, progSoc:progSoc, msgVoz:msgVoz, tipoDiNino: tipoDiNino, 
								nroDiNino:nroDiNino, hClFam:hClFam, hClNino:hClNino, fechaNacNino:fechaNacNino, fechaAtcNino:fechaAtcNino, 
								progSocNino:progSocNino, idestab:idestab}, function(data, status)
						{
							bootbox.alert(data);
							mostrarform(false);
							limpiar();
							tabla.ajax.reload(null,false);
					  });
					}
				});
			}else{
				//No se hizo modificaciones en el numero de identificacion del niño
			  $.post('../ajax/registroninos.php?op=guardaryeditar', {idNino:idNino, tipoDocIdent:tipoDocIdent, nroDocIdent:nroDocIdent, 
			  		apePat:apePat, apeMat:apeMat, nombres:nombres, fechaNacMama:fechaNacMama, idGrInst:idGrInst, 
						celularMadre:celularMadre, celularAcomp:celularAcomp, progSoc:progSoc, msgVoz:msgVoz, tipoDiNino: tipoDiNino, 
						nroDiNino:nroDiNino, hClFam:hClFam, hClNino:hClNino, fechaNacNino:fechaNacNino, fechaAtcNino:fechaAtcNino, 
						progSocNino:progSocNino, idestab:idestab}, function(data, status)
				{
					bootbox.alert(data);
					mostrarform(false);
					limpiar();
					tabla.ajax.reload(null,false);
			  });
			}
		}
	}else{
		$("#btnGuardar").prop("disabled",false);
	}
}

//Funcion que valida el ingreso de datos del niño
function validaRegistroDatosNinos(idNino, tipoDiNino, nroDiNino, celularMadre, celularAcomp, fechaNacNino, fechaAtcNino)
{
	if(celularMadre.length!=9)
	{
		bootbox.alert('El numero de digitos del CELULAR DE LA MADRE debe ser 9, verifique por favor.');
		return false;
	}

	if(celularAcomp.length!=0)
	{
		if(celularAcomp.length!=9)
		{
			bootbox.alert('El numero de digitos del CELULAR DEL ACOMPAÑANTE debe ser 9, verifique por favor.');
			return false;
		}
	}

	if(tipoDiNino=='08001' && nroDiNino.length!=8)
	{
		bootbox.alert('El numero de digitos del DNI del Niño debe ser 8, verifique por favor.');
		return false;
	}

	if(validarFechaMenorIgualActual(fechaNacNino))
	{
		bootbox.alert('La Fecha de Nacimiento del Niño no puede ser mayor a la de HOY.');
		return false
	}

	if(validarFechaMenorIgualActual(fechaAtcNino))
	{
		bootbox.alert('La Fecha de Atenciòn del Niño no puede ser mayor a la de HOY.');
		return false
	}

	if(!validarFnHasta300ias(fechaNacNino))
	{
		bootbox.alert('La Fecha de Nacimiento del Niño NO debe ser menor a 360 dias (12 meses).');
		return false
	}

	return true;
}

//Validar que la fecha de nacimiento del niño no sea menor a 11 meses o 360 dias (dandole unos dias mas)
function validarFnHasta300ias(fechaNacNino)
{
	var fechaInicio = new Date().getTime();
	var fechaFin    = new Date(fechaNacNino).getTime();

	var diff = fechaInicio - fechaFin;

	nroDias=diff/(1000*60*60*24);
	if(nroDias>360)
	{ 
		return false;
	}else{
		return true;
	}

}

//Validar si fecha pasada es menor o mayor igual
function validarFechaMenorIgualActual(fechaValidar)
{
	//Funcion que verifica si la 'fechaValidar' es menor o igual a la fecha actual, en cuyo caso devuelve false;
	//si la fecha actual es mayor devuelve true.
	var fechaHoy = new Date();
	var annioHoy=fechaHoy.getFullYear();
	var mesHoy=fechaHoy.getMonth()+1;
	var diaHoy=fechaHoy.getDate();

	var annioValidar=parseInt(fechaValidar.substr(0,4));
  var mesValidar=parseInt(fechaValidar.substr(5,2));
	var diaValidar=parseInt(fechaValidar.substr(8,2));

	if (annioValidar < annioHoy){
		// Fecha de hoy mayor a la fecha a validar
		return false;
	}else{
	  if (annioValidar == annioHoy && mesValidar < mesHoy){
			// Fecha de hoy mayor a la fecha a validar
			return false;
	  }else{
      if (annioValidar == annioHoy && mesValidar == mesHoy && diaValidar < diaHoy){
				// Fecha de hoy menor a la fecha a validar
				return false;
      }else{
        if (annioValidar == annioHoy && mesValidar == mesHoy && diaValidar == diaHoy){
        	// Fecha de hoy es igual a la fecha a validar
        	return false;
        }else{
        	// Fecha de hoy es menor a la fecha a validar
        	return true;
        }
      }
	  }
	}
}

// Funcion que va a mostrar los datos de un registro en el formulario cuando se edita.
function mostrar(idNino)
{
	$("#formatoNino").hide();
	$.post("../ajax/registroninos.php?op=mostrar",{idNino : idNino}, function(data, status)
	{
		data = JSON.parse(data);
		mostrarform(true);
		var fechaAtc=data.fecAtcNino;
		let fecha=new Date(fechaAtc);
		var annio=fecha.getFullYear();
		if (annio<2000)
		{
			fechaAtc="";
		}

		$("#idNino").val(idNino);
		//Datos de la Mamá
		$("#tipoDocIdent").val(data.tipoDiMa);
		$("#tipoDocIdent").selectpicker('refresh');
		$("#nroDocIdent").val(data.nroDiMa);
		$("#apePat").val(data.apePat);
		$("#apeMat").val(data.apeMat);
		$("#nombres").val(data.nombres);
		$("#fechaNacMama").val(data.fechaNacMama);
		//Inhabilitamos los datos basicos
		$("#apePat").prop("disabled",true);
		$("#apeMat").prop("disabled",true);
		$("#nombres").prop("disabled",true);
		$("#fechaNacMama").prop("disabled",true);

		$("#idGrInst").val(data.idGrInst);
		$("#idGrInst").selectpicker('refresh');
		$("#celularMadre").val(data.celMa);
		$("#nroCelPrincipal").val(data.celMa);  //Para verificar si se hizo cambio en el campo celMa
		$("#celularAcomp").val(data.celAcomp);
		$("#progSoc").val(data.idProgGe);
		$("#progSoc").selectpicker('refresh');
		$("#msgVoz").val(data.idIdioma);
		$("#msgVoz").selectpicker('refresh');

		//Datos de Niño
		$("#tipoDiNino").val(data.tipoDiNino);
		$("#tipoDiNino").selectpicker('refresh');
		$("#nroDiNino").val(data.nroDiNino);
		$("#nroDiNinoAnt").val(data.nroDiNino);
		$("#hClFam").val(data.nhFam);
		$("#hClNino").val(data.nhNino);
		$("#fechaNacNino").val(data.fecNacNino);
		$("#fechaAtcNino").val(fechaAtc);
		$("#progSocNino").val(data.idProgNi);
		$("#progSocNino").selectpicker('refresh');
	});

}

//Función para desactivar registros
function desactivar(idGest)
{
	bootbox.confirm("¿Está Seguro de desactivar datos del Niño?", function(result){
	if(result)
  {
  	$.post("../ajax/registrogestantes.php?op=desactivar", {idGest : idGest}, function(e){
      tabla.ajax.reload(null,false);
  	});	
  }
})
}

//Función para activar registros
function activar(idGest)
{
	bootbox.confirm("¿Está Seguro de activar datos del Niño?", function(result){
	if(result)
  {
  	$.post("../ajax/registrogestantes.php?op=activar", {idGest : idGest}, function(e){
      tabla.ajax.reload(null,false);
  	});	
  }
})
}

//Funcion para eliminar los registros
function eliminar(idGest)
{
	bootbox.confirm("¿Está seguro que desea eliminar El Registro del Niño?", function(result){
	if (result)
	{
		$.post("../ajax/registrogestantes.php?op=eliminar", {idGest : idGest}, function(e){
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
	  $.post("../ajax/registrogestantes.php?op=listaRedes", {iddisa : iddisa}, function(r){
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
	$.post("../ajax/registrogestantes.php?op=listaMicRedes", {idred : idred}, function(r){
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
	$.post("../ajax/registrogestantes.php?op=listaEstablec", {idmred : idmred}, function(r){
      $("#idestab").html(r);
	    $('#idestab').selectpicker('refresh');
	});
}

// Funcion que busca a la mama del niño a registrar dado su tipo de documento y numero.
function buscarUsuariaMama()
{
  var llEncontrado=1;
  //Si se va a registrar una mamá nueva se necesita el id del establecimiento de salud
	var idEstab=$('#idestab').val();
	var rolUsuario=$('#rolUsuario').val();
	//Limpiamos los campos del formulario de registro de datos del niño
	limpiar();
	//reiniciamos los controles del modal de busqueda de la mama del niño
	$('#tipoDi').val('DNI');
	$('#tipoDi').selectpicker('refresh');
	$('#nroDoc').show();
	$('#nroDi').val('');
	$('#buscarDatosUsuaria').show();
	$('#msgNoEncontrada').hide();

	limpiarResultadosBuscaUsuaria();

	if(rolUsuario=='ADMINISTRADOR' && idEstab=='0')
	{
		bootbox.alert('Debe seleccionar un establecimiento de salud para Registro del Niño.');
	}else{
		// Solo se muestra la ventana de busqueda si se ha seleccionado el establecimiento de salud para 
		// el registro del niño
  	// Mostramos el formulario de busqueda
	  $('#buscarUsuaria').modal('show');
	  $('#nroDi').val('');
	  $('#btnNvaMama').hide();

	  $(document).ready(function(){
	  	$('#buscarDatosUsuaria').click(function(e){
	  		e.preventDefault();
	  		e.stopPropagation();

	  		//var tipoDoc=$('#tipoDi option:selected').text();
	  		var tipoDoc=$('#tipoDi').val();
	  		var nroDoc=$('#nroDi').val();
	  		if(tipoDoc=='08001' && nroDoc.length!=8)
	  		{
	  			bootbox.alert('El numero de digitos del DNI debe ser 8, verifique por favor');
	  		}else{

		  		$.post('../ajax/registroninos.php?op=listaMamaBusq',{tipoDoc:tipoDoc, nroDoc:nroDoc},function(data, status)
		  		{
		  			limpiarResultadosBuscaUsuaria();
		 
				    data = JSON.parse(data);
				    if(data.length!=0)
				    {
					    $('#btnNvaMama').hide();
					    $('#msgNoEncontrada').hide();
					    for (var i=0; i<data.length; i++)
					    {
					    	var idMa=data[i][0];
					    	var estado=data[i][1];
					    	var nomApeMama=data[i][2];
					    	var diMa=data[i][3];
					    	var celular=data[i][4];
					    	var fecNacMa=data[i][5];
					    	var descEstab=data[i][6];

								//El valor de 'diMa' viene con el formato: 'DNI 76767676', se extrae solo el numero para hacer la busqueda
								var posEspacio=diMa.lastIndexOf(" ");
								var nroDocIdent = diMa.slice(posEspacio+1);

				    		opcion='<button type="button" class="btn btn-xs btn-primary" data-dismiss="modal" onclick="mostrarDatosNuevoNino('+nroDocIdent+')"><i class="fa fa-check"></i> Registrar Niño</button>';

					    	var fila="<tr><td>"+opcion+"</td>"+"<td>"+nomApeMama+"</td>"+"<td>"+diMa+"</td>"+"<td>"+celular+
					    	   "</td>"+"<td>"+fecNacMa+"</td>"+"<td>"+descEstab+"</td></tr>";
					    	var item = document.createElement("tr");
			   				
			   				item.innerHTML=fila;
			    			document.getElementById("listaUsu").appendChild(item);
					    }
					  }else{
					  	// Buscar en la tabla de reniec, por el momento queda como esta
					  	llEncontrado=0;
					  	$('#msgNoEncontrada').show().hide(7000);
					  	$('#btnNvaMama').show();
					  }

		  		});

	  		}
	  	});
	  });
	}
}

// Funcion que limpia la tabla de resultados de busqueda del formnulario modal
function limpiarResultadosBuscaUsuaria()
{
	 var tb = document.getElementById('tblListaGest'); 
	 // Quitamos todas las filas de la tabla
	 while(tb.rows.length > 1) 
	 	{ tb.deleteRow(1); } 
}

// Funcion que limpia la tabla de resultados de busqueda del formnulario modal que importa datos de un niño
function limpiarTablaImportarNino()
{
	 var tb = document.getElementById('tblListaImportarNino'); 
	 // Quitamos todas las filas de la tabla
	 while(tb.rows.length > 1) 
	 	{ tb.deleteRow(1); } 
}

// Funcion que va a mostrar los datos de una usuaria para nueva gestacion, solo se busca en la tabla mamaspiloto
function mostrarDatosNuevoNino(nroDiMama)
{
  var nroDocIdent=nroDiMama;
	var fechaHoy = new Date();
	//Año
	y = fechaHoy.getFullYear();
	//Mes
	m = fechaHoy.getMonth() + 1;
	//Día
	d = fechaHoy.getDate();

	if(m<10)
	{
		m='0'+m;
	}

	if(d<10)
	{
		d='0'+d;
	}
	var fecAtc=y+'-'+m+'-'+d;

	// Nos traemos los datos de la mamá seleccionada para mostrar y hacer el registro del nuevo niño
	$.post("../ajax/registroninos.php?op=buscarMamaExiste",{nroDocIdent : nroDocIdent}, function(data, status)
	{
		data = JSON.parse(data);
		mostrarform(true);

		$("#idNino").val('');
		//Datos de la Mamá
		$("#tipoDocIdent").val(data.tipoDiMa);
		$("#tipoDocIdent").selectpicker('refresh');
		$("#nroDocIdent").val(data.nroDiMa);
		$("#apePat").val(data.apePat);
		$("#apeMat").val(data.apeMat);
		$("#nombres").val(data.nombres);
		$("#fechaNacMama").val(data.fechaNacMama);
		$("#idGrInst").val(data.idGrInst);
		$("#idGrInst").selectpicker('refresh');
		$("#celularMadre").val(data.celMa);
		$("#celularAcomp").val(data.celAcomp);
		$("#progSoc").val(data.idProgGe);
		$("#progSoc").selectpicker('refresh');
		$("#msgVoz").val(data.idIdioma);
		$("#msgVoz").selectpicker('refresh');

		//Datos de Niño
		$("#tipoDiNino").val('DNI');
		$("#tipoDiNino").selectpicker('refresh');
		$("#nroDiNino").val('');
		$("#hClFam").val('');
		$("#hClNino").val('');
		$("#fechaNacNino").val('');
		//Mostramos la fecha de hoy
		$('#fechaAtcNino').val(fecAtc);
		//document.getElementById("fechaAtcNino").innerHTML = d + "/" + m + "/" + y;
		$("#progSocNino").val('');
		$("#progSocNino").selectpicker('refresh');

	});

}

//Funcion para registro de datos de la nueva gestante mama
function nuevaMama(tipoDi, nroDi)
{
	mostrarform(true);
	var fechaHoy=$("#fechaHoy").val();
	//Indicamos que se va a registrar una usuaria nueva para el eess seleccionado
    
	$("#idGest").val("");  // Se hace esto para evitar chancar los datos cuando se agrega un nuevo registro.
	$("#tipoDocIdent").val(tipoDi);
	$("#tipoDocIdent").selectpicker('refresh');
	if(tipoDi=='00000')
	{
		//No tiene documento de identificacion
	  $("#nroDocIdent").val('');	
	}else{
	  $("#nroDocIdent").val(nroDi);		
	}
	$("#apePat").val("");
	$("#apeMat").val("");
	$("#nombres").val("");

	//Habilitamos los datos basicos
	$("#apePat").prop("disabled",false);
	$("#apeMat").prop("disabled",false);
	$("#nombres").prop("disabled",false);
	$("#fechaNacMama").prop("disabled",false);


	$("#fechaNacMama").val("");
	$("#idGrInst").val("(NO APLICA)");
	$("#idGrInst").selectpicker('refresh');
	$("#fecProbParto").val("");
	//$("#fechaAtc").val(fechaHoy);
	$("#hClFam").val("");
	$("#hClGest").val("");
	$("#celularMadre").val("");
	$("#celularAcomp").val("");
	$("#progSoc").val("");
	$('#progSoc').selectpicker('refresh');
	$("#msgVoz").val("");
	$('#msgVoz').selectpicker('refresh');

	//Limpiamos los datos del niño
	$("#tipoDiNino").val("DNI");
	$('#tipoDiNino').selectpicker('refresh');
	$("#nroDiNino").val("");
	$("#hClFam").val("");
	$("#hClNino").val("");
	$("#fechaNacNino").val("");
	$("#fechaAtcNino").val(fechaHoy);
	$("#progSocNino").val("-No Tiene-");
	$('#progSocNino').selectpicker('refresh');

}

//Verificar si el usuario ha seleccionado tipo de documento '-No Tiene-', para realizar acciones en este caso
function validaTipoDoc()
{
	var tipoDoc=$('#tipoDi option:selected').text();
	if(tipoDoc=='-No Tiene-')
	{
		$('#btnNvaMama').show();
		$('#buscarDatosUsuaria').hide();
		$('#nroDoc').hide();
	}else{
		$('#btnNvaMama').hide();
		$('#buscarDatosUsuaria').show();
		$('#nroDoc').show();
	}
}

//Código que va a ocultar el boton de agregar en la ventana modal cuando se digite nuero de documento,
//esto para evitar duplicidad de documento de identidad
$(document).ready(function(){
	$('#nroDi').keypress(function(){
    $('#btnNvaMama').hide();
	})
})


// Funcion que busca a la mama del niño que se quiere importar.
function mostrarFormImportarNino()
{
  var llEncontrado=1;
  //Si se va a importar un niño se necesita el id del establecimiento de salud al que se va adherir
	var idEstab=$('#idestab').val();
	var rolUsuario=$('#rolUsuario').val();

	//reiniciamos los controles del modal de busqueda de la mama del niño
	$('#tipoDiImp').val('DNI');
	$('#tipoDiImp').selectpicker('refresh');
	$('#nroDocImp').show();
	$('#nroDiImp').val('');
	$('#buscarDatosMamaNino').show();
	$('#msgMamaNinoNoEncontrada').hide();

	limpiarTablaImportarNino();

	if(rolUsuario=='ADMINISTRADOR' && idEstab=='0')
	{
		bootbox.alert('Debe seleccionar un establecimiento de salud para Importar datos de un Niño.');
	}else{
		// Solo se muestra la ventana de busqueda si se ha seleccionado el establecimiento de salud para 
		// importar al niño
  	// Mostramos el formulario de busqueda
	  $('#importarNino').modal('show');
	  $('#nroDiImp').val('');

	  $(document).ready(function(){
	  	$('#buscarDatosMamaNino').click(function(e){
	  		e.preventDefault();
	  		e.stopPropagation();

	  		//var tipoDoc=$('#tipoDi option:selected').text();
	  		var tipoDoc=$('#tipoDiImp').val();
	  		var nroDoc=$('#nroDiImp').val();
	  		if(tipoDoc=='08001' && nroDoc.length!=8)
	  		{
	  			bootbox.alert('El numero de digitos del DNI debe ser 8, verifique por favor');
	  		}else{
	  			limpiarTablaImportarNino();
	  			// Verificamos la existencia del nro de DNI de la mamá que se esta ingresando
	  			$.post("../ajax/registroninos.php?op=verificarDatosMamaImportarNino",{nroDoc:nroDoc},function(data, status){
	  				var resp=data;
	  				if(resp=='0')
	  				{
	  					$('#msgMamaNinoNoEncontrada').show();
	  					 document.getElementById('msgRespuesta').innerHTML = 'El número de DNI de la mama ingresado, NO EXISTE o NO está ACTIVO, verifique por favor.';
	  					 limpiarTablaImportarNino();
	  				}else{
	  					// El nro de DNI de la mama si existe, obtenemos los datos si cumple con los criterios: registro activo, registro debe ser del niño 'N' y debe tener mas de 2 meses de registro en el sistema
				  		$.post('../ajax/registroninos.php?op=listaImportarNino',{tipoDoc:tipoDoc, nroDoc:nroDoc},function(data, status)
				  		{
				  			limpiarTablaImportarNino();
				 
						    data = JSON.parse(data);
						    if(data.length!=0)
						    {
							    $('#msgMamaNinoNoEncontrada').hide();
							    for (var i=0; i<data.length; i++)
							    {
							    	var idMa=data[i][0];
							    	var nomApeMama=data[i][1];
							    	var diMa=data[i][2];
							    	var celular=data[i][3];
							    	var fecNacNi=data[i][4];
							    	var diNi=data[i][5];
							    	var fecReg=data[i][6];
							    	var nroDiasTrans=data[i][7];
							    	var descEstab=data[i][8];

										//El valor de 'diMa' viene con el formato: 'DNI 76767676', se extrae solo el numero para hacer la busqueda
										var posEspacio=diMa.lastIndexOf(" ");
										var nroDocIdent = diMa.slice(posEspacio+1);
										debugger;
						    		if(parseInt(nroDiasTrans)>=60)
						    		{
							    		opcion='<button type="button" class="btn btn-xs btn-success" data-dismiss="modal" onclick="importarNino('+idMa+')"><i class="fa fa-check"></i> Importar Niño</button>';
						    		}else{
						    			opcion='<button type="button" class="btn btn-xs btn-danger"> <i class="fa fa-hand-stop-o"></i> Alto</button>';
							    		document.getElementById('msgRespuesta').innerHTML = 'Los datos del Niño no pueden ser importados, debe transcurrir por lo menos 60 días después de la fecha de Registro para hacerlo.';
							    		$('#msgMamaNinoNoEncontrada').show();
						    		}

							    	var fila="<tr><td>"+opcion+"</td>"+"<td>"+nomApeMama+"</td>"+"<td>"+diMa+"</td>"+"<td>"+celular+
							    	   "</td>"+"<td>"+fecNacNi+"</td>"+"<td>"+diNi+"</td>"+"<td>"+fecReg+"</td>"+"<td>"+nroDiasTrans+"</td>"+"<td>"+descEstab+"</td></tr>";
							    	var item = document.createElement("tr");
					   				
					   				item.innerHTML=fila;
					    			document.getElementById("listaNino").appendChild(item);
							    }
							  }else{
							  	// Buscar en la tabla de reniec, por el momento queda como esta
							  	llEncontrado=0;
							  	$('#msgMamaNinoNoEncontrada').show().hide(7000);
							  }
				  		});
	  				}
	  			});
	  		}
	  	});
	  });
	}
}

// Funcion que ejecuta la importacion de los datos del niño al ee.ss de donde se está invocando los datos
function importarNino(idMa)
{
	// idMa, id de la tabla mamaspiloto que utilizaremos para identificar el registro donde se van a hacer los cambios
	var idRegMa=idMa;
	// ID del eess a donde se van a importar los datos del niño
	var idEstablDestino=$('#idestab').val();
	debugger;

	$.post('../ajax/registroninos.php?op=importarNino',{idRegMa:idRegMa, idEstablDestino:idEstablDestino}, function(data, status)
	{
		var resp=data;
		if(resp=='0')
		{
			bootbox.alert('No se pudo hacer la importación, intente de nuevo por favor');
		}else{
			bootbox.alert('Los datos del niño fueron importados existosamente.');
			tabla.ajax.reload(null,false);
		}
	});
}

// Funcion que permite ingresar hasta 11 numeros en el campo RUC
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

// Funcion que permite validar el campo celular de la mama o gestante
function validaCeluMama(e, tnTam, tcNombreCtrl)
{
	// tnTam: El numero de digitos que se quiere controlar
	// tcNombreCtrl: Nombre del control que se quiere validar
	var celularMadre=$("#celularMadre").val();
	var nroCelPrincipal=$("#nroCelPrincipal").val();

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
			// Verificamos si hubo cambio en el registro del numero de celular de la gestante, solo en ese caso se hace la validación
			if(nroCelPrincipal!=celularMadre)
			{
		  	// Se procede a verificar si el numero de celular existe en la bd
		  	celularGest=celularMadre;
			  $.post('../ajax/registrogestantes.php?op=existeNroCelular', {celularGest:celularGest}, function(data, status)
				{
					// Si existe el numero de celular en algun beneficiario tipo G o N y esta activo devolvemos 1 sino 0
					resp=data;
					if(resp=='1')
					{
						bootbox.alert('El numero de Celular de la Mamá YA ESTA REGISTRADA en la Base de Datos, verifique por favor.');
						$("#btnGuardar").prop("disabled",true);
					}else{
						$("#btnGuardar").prop("disabled",false);
					}
				});
			}
      return false;
	}
}

// Si el campo "celularMadre" pierde el enfoque forzamos a validar el valor del campo.
$(document).ready(function()
	{
	$("#celularMadre").blur(function(){
		$("#btnGuardar").prop("disabled",false);
    return validaCeluMama(event,9,'celularMadre');
	});
});


init();