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
	$("#formatoGestante").hide();
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

    $("#tipoDiImp").html(r);
    $('#tipoDiImp').selectpicker('refresh');
	});


	// Cargamos los items al select Tipos de Documentos de Identidad en el modal
	$.post("../ajax/registrogestantes.php?op=listaDocIdent", function(r){
    $("#tipoDi").html(r);
    $('#tipoDi').selectpicker('refresh');
	});

	// Cargamos los items al select programa social
	$.post("../ajax/registrogestantes.php?op=listaProgSoc", function(r){
    $("#progSoc").html(r);
    $('#progSoc').selectpicker('refresh');
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
	$("#idGest").val("");  // Se hace esto para evitar chancar los datos cuando se agrega un nuevo registro.
	$("#tipoDi").val("DNI");
	$('#tipoDi').selectpicker('refresh');
	$("#nroDi").val("");
	$("#apePat").val("");
	$("#apeMat").val("");
	$("#nombres").val("");
	$("#fechaNacGest").val("");
	$("#idGrInst").val("(NO APLICA)");
	$("#idGrInst").selectpicker('refresh');
	$("#fecProbParto").val("");
	//$("#fechaAtc").val("");
	$("#hClFam").val("");
	$("#hClGest").val("");
	$("#celularMadre").val("");
	$("#nroCelPrincipal").val("");
	$("#celularAcomp").val("");
	$("#progSoc").val("");
	$('#progSoc').selectpicker('refresh');
	$("#msgVoz").val("");
	$('#msgVoz').selectpicker('refresh');
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
			url: '../ajax/registrogestantes.php?op=listar',
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
	//var formData = new 	FormData($("#formulario")[0]);

	var idGest=$("#idGest").val();
	//var tipoDocIdent=$('#tipoDocIdent option:selected').text();
	var tipoDocIdent=$('#tipoDocIdent').val();
	var nroDocIdent=$("#nroDocIdent").val();
	var apePat=$("#apePat").val();
	var apeMat=$("#apeMat").val();
	var nombres=$("#nombres").val();
	var fechaNacGest=$("#fechaNacGest").val();
	var idGrInst=$('#idGrInst').val();
	var fecProbParto=$("#fecProbParto").val();
	var fechaAtc=$("#fechaAtc").val();
	var hClFam=$("#hClFam").val();
	var hClGest=$("#hClGest").val();
	var celularMadre=$("#celularMadre").val();
	var nroCelPrincipal=$("#nroCelPrincipal").val();
	var celularAcomp=$("#celularAcomp").val();
	var progSoc=$("#progSoc").val();
	var msgVoz=$("#msgVoz").val();
	//Esta variable es para cuando el usuario que registra es administrador, para el operador se usa la varible de sesión.
	var idestab=$('#idestab').val();

  // Validamos que el numero de celular de la gestante tenga 9 digitos
  if(validaRegistroDatosGestante(celularMadre, celularAcomp, fecProbParto, fechaNacGest, fechaAtc))
  {
  	// La verificación de la existencia del numero de celular se hace dentro de la funcion validaCeluGest() que está al final
	  $("#btnGuardar").prop("disabled",true);
	  $.post('../ajax/registrogestantes.php?op=guardaryeditar', {idGest:idGest, tipoDocIdent:tipoDocIdent, nroDocIdent:nroDocIdent, 
	  		apePat:apePat, apeMat:apeMat, nombres:nombres, fechaNacGest:fechaNacGest, idGrInst:idGrInst, 
				fecProbParto:fecProbParto, fechaAtc:fechaAtc, hClFam:hClFam, hClGest:hClGest, celularMadre:celularMadre,
				celularAcomp:celularAcomp, progSoc:progSoc, msgVoz:msgVoz, idestab:idestab}, function(data, status)
		{
			var nvoId=data;
			if (nvoId>0 || nvoId!=undefined)
			{
				$("#idGest").val(nvoId);
				$("#formatoGestante").show();
				bootbox.alert('Los datos han sido Registrados Correctamente.');
				$("#formatoGestante").attr("href", "../reportes/formatogestante.php?idGest="+nvoId+"&region="+region);
			}else{
				bootbox.alert('No se pudo Registrar los datos. Salga del Sistema e intente de nuevo.');
			}
			//bootbox.alert(data);
			//mostrarform(false);
			//limpiar();
			tabla.ajax.reload(null,false);
	  });
	}
}

//Funcion que valida el ingreso de datos de la gestante
function validaRegistroDatosGestante(celularGest, celularAcomp, fechaPpGest, fechaNacGest, fechaAtcGest)
{
  if(celularGest.length<9)
  {
  	bootbox.alert('El número de celular de la Gestante debe tener 9 dígitos. Verifique por favor.');
  	return false;
  }

  if(celularAcomp.length>0)
  {
  	if(celularAcomp.length<9)
  	{
	  	bootbox.alert('El número de celular del Acompañante debe tener 9 dígitos. Verifique por favor.');
	  	return false;
  	}
  }

	if(!validarFechaMenorIgualActual(fechaPpGest))
	{
		bootbox.alert('La Fecha Probable de Parto no puede ser menor a la de HOY.');
		return false
	}

	if(validarFechaMenorIgualActual(fechaNacGest))
	{
		bootbox.alert('La Fecha de Nacimiento de la Gestante No puede ser mayor a la de HOY.');
		return false
	}


	if(validarFechaMenorIgualActual(fechaAtcGest))
	{
		bootbox.alert('La Fecha de Atención de la Gestante no puede ser mayor a la de HOY.');
		return false
	}
	
	if(!validarFppHasta290Dias(fechaPpGest))
	{
		bootbox.alert('La Fecha PP de la gestante NO debe exceder a 290 dias (9 meses).');
		return false
	}

	return true;
}

//Validar que la fecha pp no sea mayor a 9 meses o 290 dias (dandole unos dias mas)
function validarFppHasta290Dias(fechaPpGest)
{
	var fechaInicio = new Date().getTime();
	var fechaFin    = new Date(fechaPpGest).getTime();

	var diff = fechaFin - fechaInicio;

	nroDias=diff/(1000*60*60*24);
	if(nroDias>290)
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
function mostrar(idGest)
{
	var rolUsuario=$('#rolUsuario').val();
	var nombreUsuLog=$('#nombreUsuLog').val();
	var nombreUsuReg='';
	debugger;
	$("#formatoGestante").hide();
	$.post("../ajax/registrogestantes.php?op=mostrar",{idGest : idGest}, function(data, status)
	{
		data = JSON.parse(data);
		mostrarform(true);
		var fechaAtc=data.fechaAtc;
		let fecha=new Date(fechaAtc);
		var annio=fecha.getFullYear();
		if (annio<2000)
		{
			fechaAtc="";
		}

		$("#idGest").val(idGest);
		$("#tipoDocIdent").val(data.tipoDi);
		$("#tipoDocIdent").selectpicker('refresh');
		$("#nroDocIdent").val(data.nroDi);
		$("#apePat").val(data.apePat);
		$("#apeMat").val(data.apeMat);
		$("#nombres").val(data.nombres);
		$("#fechaNacGest").val(data.fechaNacGest);
		$("#idGrInst").val(data.idGrInst);
		$("#idGrInst").selectpicker('refresh');
		$("#fecProbParto").val(data.fpp);
		$("#fechaAtc").val(fechaAtc);
		$("#hClFam").val(data.nhf);
		$("#hClGest").val(data.nh);
		$("#celularMadre").val(data.celGest);
		$("#nroCelPrincipal").val(data.celGest);  //Para verificar si se hizo cambio en el campo celGet
		$("#celularAcomp").val(data.celAcomp);
		$("#progSoc").val(data.idProgGe);
		$("#progSoc").selectpicker('refresh');
		$("#msgVoz").val(data.idIdioma);
		$("#msgVoz").selectpicker('refresh');
		nombreUsuReg=data.usuReg;
		if(nombreUsuLog==nombreUsuReg || (rolUsuario=='OPERADOR' || rolUsuario=='ADMINISTRADOR'))
		{
			//Si el usuario que registró la gestante que tiene rol operador está logeado, puede hacer cambios
			$("#btnGuardar").prop("disabled", false);
		}else{
			$("#btnGuardar").prop("disabled", true);
		}
	});

}

//Función para desactivar registros
function desactivar(idGest)
{
	bootbox.confirm("¿Está Seguro de desactivar el Registro de la Gestante?", function(result){
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
	bootbox.confirm("¿Está Seguro de activar el Registro de la Gestante?", function(result){
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
	bootbox.confirm("¿Está seguro que desea eliminar El Registro de la Gestante?", function(result){
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

// Funcion que busca a una usuaria dado su tipo de documento y numero.
function buscarUsuariaMama()
{
  var llEncontrado=1;
  //Si se va a registrar una gestante nueva se necesita el id del establecimiento de salud
	var idEstab=$('#idestab').val();
	var rolUsuario=$('#rolUsuario').val();
	//Limpiamos los campos del formulario de registro de datos del niño
	limpiar();
	//reiniciamos los controles del modal de busqueda de gestante
	$('#tipoDi').val('DNI');
	$('#tipoDi').selectpicker('refresh');
	$('#nroDoc').show();
	$('#nroDi').val('');
	$('#buscarDatosUsuaria').show();
	$('#msgNoEncontrada').hide();

	limpiarResultadosBuscaUsuaria();

	if(rolUsuario=='ADMINISTRADOR' && idEstab=='0')
	{
		bootbox.alert('Debe seleccionar un establecimiento de salud para Registro de Gestante Nueva.');
	}else{
		// Solo se muestra la ventana de busqueda si se ha seleccionado el establecimiento de salud para 
		// el registro de gestante

  	// Mostramos el formulario de busqueda
	  $('#buscarUsuaria').modal('show');
	  $('#nroDi').val('');
	  $('#btnNvaGest').hide();

	  $(document).ready(function(){
	  	$('#buscarDatosUsuaria').click(function(e){
	  		e.preventDefault();
	  		e.stopPropagation();

	  		var docIdent=$('#tipoDi option:selected').text(); // Capturamos el texto del tipo de documento
	  		var tipoDoc=$('#tipoDi').val();
	  		var nroDoc=$('#nroDi').val();
	  		var llBusca=true;

	  		docIdent=docIdent.trim();
	  		if(nroDoc.length==0)
	  		{
	  			bootbox.alert('Debe ingresar el número del Documento de Identificación');
	  			llBusca=false;
	  		}else{
		  		if(docIdent=='DNI')
		  		{
		  			if(nroDoc.length!=8)
		  			{
		  				bootbox.alert('El DNI debe tener 8 dígitos');
		  				llBusca=false;
		  			}
		  		}
		  	}

	  		if(llBusca)
	  		{
		  		$.post('../ajax/registrogestantes.php?op=listaGestBusq',{tipoDoc:tipoDoc, nroDoc:nroDoc},function(data, status)
		  		{
		  			limpiarResultadosBuscaUsuaria();
		 
				    data = JSON.parse(data);
				    debugger;
				    if(data.length!=0)
				    {
					    $('#btnNvaGest').hide();
					    $('#msgNoEncontrada').hide();
					    for (var i=0; i<data.length; i++)
					    {
					    	var id=data[i][0];
					    	var estado=data[i][1];
					    	var nomApeGest=data[i][2];
					    	var di=data[i][3];
					    	var celular=data[i][4];
					    	var fpp=data[i][5];
					    	var descEstab=data[i][6];
					    	var tipoPersona=data[i][7];
					    	var fecNacNino=convertDateFormat(data[i][8]);
					    	var disa=data[i][9];
					    	var fppAnnio;
					    	var fppMes;
					    	var fppDia;

					    	descEstab=descEstab + '-' + disa;

								//El valor de 'di' viene con el formato: 'DNI 76767676', se extrae solo el numero para hacer la busqueda
								var posEspacio=di.indexOf(" ", 1);
								var nroDocIdent = di.slice(posEspacio+1);
								if(fpp!=null)
								{
						    	fppAnnio=fpp.slice(6,10);
						    	fppMes=fpp.slice(3,5);
						    	fppDia=fpp.slice(0,2);
								}else{
									fpp='No Tiene';
								}

					    	if (estado=='0')
					    	{
					    		opcion='<button type="button" class="btn btn-xs btn-primary" data-dismiss="modal" onclick="mostrarDatosNuevaGestacion('+id+','+nroDocIdent+')"><i class="fa fa-check"></i> NG</button>';
					    	}else{
					    		if(tipoPersona=='G')
					    		{
					    			opcion='<span class="label bg-green">Activo</span>';
					    		}else{
					    			opcion='<button type="button" class="btn btn-xs btn-primary" data-dismiss="modal" onclick="mostrarDatosNuevaGestacion('+id+','+nroDocIdent+')"><i class="fa fa-check"></i> NG</button></br>' + '<h4><span class="label label-danger label-lg">Es mamá de un niño(a). </br><span>F.N. Niño:'+fecNacNino+'</span></h4></span>';
					    		}
					    		
					    	}

					    	var fila="<tr><td>"+opcion+"</td>"+"<td>"+nomApeGest+"</td>"+"<td>"+di+"</td>"+"<td>"+celular+
					    	   "</td>"+"<td>"+fpp+"</td>"+"<td>"+descEstab+"</td></tr>";
					    	var item = document.createElement("tr");
			   				
			   				item.innerHTML=fila;
			    			document.getElementById("listaUsu").appendChild(item);
					    }
					  }else{
					  	// Buscar en la tabla de reniec, por el momento queda como esta
					  	llEncontrado=0;
					  	$('#msgNoEncontrada').show().hide(7000);
					  	$('#btnNvaGest').show();
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

// Funcion que va a mostrar los datos de una usuaria para nueva gestacion, solo se busca en la tabla mamaspiloto
function mostrarDatosNuevaGestacion(idGest,nroDi)
{
  var nroDocIdent=nroDi;
	//Primero verificamos que no tenga alguna gestación activa
	$.post("../ajax/registrogestantes.php?op=verGestacionActiva", {nroDocIdent:nroDocIdent}, function(data, status)
	{
		rspta = JSON.parse(data);
		if(rspta==1)
		{
			//Existe alguna gestación activa no se puede ingresar otra.
			bootbox.alert('Hay una Gestación Activa, no se puede Registrar una Nueva.');
		}else{
			//Como no hay gestacion activa se procede con el registro de la nueva.
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

			// Nos traemos los datos de la gestante seleccionada para mostrar y hacer el registro de la nueva gestación
			$.post("../ajax/registrogestantes.php?op=buscarGestanteExiste",{idGest : idGest}, function(data, status)
			{
				data = JSON.parse(data);
				mostrarform(true);

				$("#idGest").val('');
				$("#tipoDocIdent").val(data.tipoDi);
				$("#tipoDocIdent").selectpicker('refresh');
				$("#nroDocIdent").val(data.nroDi);
				$("#apePat").val(data.apePat);
				$("#apeMat").val(data.apeMat);
				$("#nombres").val(data.nombres);
				//Inhabilitamos los datos basicos
				$("#apePat").prop("disabled",true);
				$("#apeMat").prop("disabled",true);
				$("#nombres").prop("disabled",true);

				$("#fechaNacGest").val(data.fechaNacGest);
				$("#idGrInst").val(data.idGrInst);
				$("#idGrInst").selectpicker('refresh');
				$("#fecProbParto").val('');
				//Mostramos la fecha de hoy
				$('#fechaAtc').val(fecAtc);
				//document.getElementById("fechaAtc").innerHTML = d + "/" + m + "/" + y;
				$("#hClFam").val(data.nhf);
				$("#hClGest").val(data.nh);
				$("#celularMadre").val(data.celGest);
				$("#celularAcomp").val(data.celAcomp);
				$("#progSoc").val(data.idProgGe);
				$("#progSoc").selectpicker('refresh');
				$("#msgVoz").val(data.idIdioma);
				$("#msgVoz").selectpicker('refresh');
			});
		}
	});

}

//Funcion para registro de datos de la nueva gestante mama
function nuevaGestante(tipoDi, nroDi)
{
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

	mostrarform(true);
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
	$("#fechaNacGest").val("");
	$("#idGrInst").val("(NO APLICA)");
	$("#idGrInst").selectpicker('refresh');
	$("#fecProbParto").val("");
	$("#fechaAtc").val(fecAtc);
	//document.getElementById("fechaAtc").innerHTML = y + "-" + m + "-" + d;
	$("#hClFam").val("");
	$("#hClGest").val("");
	$("#celularMadre").val("");
	$("#celularAcomp").val("");
	$("#progSoc").val("");
	$('#progSoc').selectpicker('refresh');
	$("#msgVoz").val("");
	$('#msgVoz').selectpicker('refresh');
}

//Verificar si el usuario ha seleccionado tipo de documento '-No Tiene-', para realizar acciones en este caso
function validaTipoDoc()
{
	var tipoDoc=$('#tipoDi option:selected').text();
	if(tipoDoc=='-No Tiene-')
	{
		$('#btnNvaGest').show();
		$('#buscarDatosUsuaria').hide();
		$('#nroDoc').hide();
	}else{
		$('#btnNvaGest').hide();
		$('#buscarDatosUsuaria').show();
		$('#nroDoc').show();
	}
}

//Código que va a ocultar el boton de agregar en la ventana modal cuando se digite nuero de documento,
//esto para evitar duplicidad de documento de identidad
$(document).ready(function(){
	$('#nroDi').keypress(function(){
    $('#btnNvaGest').hide();
	})
})

// Funcion que busca a la gestante que se quiere importar.
function mostrarFormImportarGestante()
{
  //Si se va a importar una gestante se necesita el id del establecimiento de salud al que se va adherir
	var idEstab=$('#idestab').val();
	var rolUsuario=$('#rolUsuario').val();

	//reiniciamos los controles del modal de busqueda de la gestante
	$('#tipoDiImp').val('DNI');
	$('#tipoDiImp').selectpicker('refresh');
	$('#nroDocImp').show();
	$('#nroDiImp').val('');
	$('#buscarDatosGestante').show();
	$('#msgMamaGestanteNoEncontrada').hide();

	limpiarTablaImportarGestante();

	if(rolUsuario=='ADMINISTRADOR' && idEstab=='0')
	{
		bootbox.alert('Debe seleccionar un establecimiento de salud para Importar datos de la Gestante.');
	}else{
		// Solo se muestra la ventana de busqueda si se ha seleccionado el establecimiento de salud para 
		// importar a la gestante
  	// Mostramos el formulario de busqueda
	  $('#importarGestante').modal('show');
	  $('#nroDiImp').val('');

	  $(document).ready(function(){
	  	$('#buscarDatosGestante').click(function(e){
	  		e.preventDefault();
	  		e.stopPropagation();

	  		//var tipoDoc=$('#tipoDi option:selected').text();
	  		var tipoDoc=$('#tipoDiImp').val();
	  		var nroDoc=$('#nroDiImp').val();
	  		if(tipoDoc=='08001' && nroDoc.length!=8)
	  		{
	  			bootbox.alert('El numero de digitos del DNI debe ser 8, verifique por favor');
	  		}else{
	  			limpiarTablaImportarGestante();
	  			debugger;
	  			// Verificamos la existencia del nro de DNI de la gestante que se esta ingresando
	  			$.post("../ajax/registrogestantes.php?op=verificarDatosGestante",{tipoDoc:tipoDoc, nroDoc:nroDoc},function(data, status){
	  				var resp=data;
	  				if(resp=='0')
	  				{
	  					$('#msgMamaGestanteNoEncontrada').show();
	  					 document.getElementById('msgRespuesta').innerHTML = 'El número de Documento de la gestante ingresada, NO EXISTE.';
	  					 limpiarTablaImportarGestante();
	  				}else{
	  					// El nro de Documento de la gestante si existe, obtenemos los datos si cumple con los criterios: tipo registro debe ser 'G'
				  		$.post('../ajax/registrogestantes.php?op=listaImportarGestante',{tipoDoc:tipoDoc, nroDoc:nroDoc},function(data, status)
				  		{
				  			limpiarTablaImportarGestante();
				 
						    data = JSON.parse(data);
						    if(data.length!=0)
						    {
							    $('#msgMamaGestanteNoEncontrada').hide();
							    for (var i=0; i<data.length; i++)
							    {
							    	var idGest=data[i][0];
							    	var nomApeGest=data[i][1];
							    	var diGest=data[i][2];
							    	var celular=data[i][3];
							    	var fpp=data[i][4];
							    	var descEstab=data[i][5];
							    	var fecReg=data[i][6];
							    	var estado=data[i][7];

										debugger;
						    		if(estado=='1')
						    		{
							    		opcion='<button type="button" class="btn btn-xs btn-success" data-dismiss="modal" onclick="importarGestante('+idGest+')"><i class="fa fa-check"></i> Importar Gestante</button>';
							    		lcEstadoGest='<span class="label bg-green">Activo</span>';
						    		}else{
						    			opcion='<button type="button" class="btn btn-xs btn-danger"> <i class="fa fa-hand-stop-o"></i> Alto</button>';
						    			lcEstadoGest='<span class="label bg-red">Inactivo</span>';
							    		document.getElementById('msgRespuesta').innerHTML = 'Los datos de la Gestante no pueden ser importados, ésta ya NO se encuentra activa.';
							    		$('#msgMamaGestanteNoEncontrada').show();
						    		}

							    	var fila="<tr><td>"+opcion+"</td>"+"<td>"+nomApeGest+"</td>"+"<td>"+diGest+"</td>"+"<td>"+celular+
							    	   "</td>"+"<td>"+fpp+"</td>"+"<td>"+descEstab+"</td>"+"<td>"+fecReg+"</td>"+"<td>"+lcEstadoGest+"</td></tr>";
							    	var item = document.createElement("tr");
					   				
					   				item.innerHTML=fila;
					    			document.getElementById("listaGestante").appendChild(item);
							    }
							  }else{
							  	// Buscar en la tabla de reniec, por el momento queda como esta
							  	llEncontrado=0;
							  	$('#msgMamaGestanteNoEncontrada').show().hide(7000);
							  }
				  		});
	  				}
	  			});
	  		}
	  	});
	  });
	}
}

// Funcion que limpia la tabla de resultados de busqueda del formnulario modal que importa datos de la gestante
function limpiarTablaImportarGestante()
{
	 var tb = document.getElementById('tblListaImportarGestante'); 
	 // Quitamos todas las filas de la tabla
	 while(tb.rows.length > 1) 
	 	{ tb.deleteRow(1); } 
}

// Funcion que ejecuta la importacion de los datos de la Gestante al ee.ss de donde se está invocando los datos
function importarGestante(idGest)
{
	// idMa, id de la tabla mamaspiloto que utilizaremos para identificar el registro donde se van a hacer los cambios
	var idRegGest=idGest;
	// ID del eess a donde se van a importar los datos del Gestante
	var idEstablDestino=$('#idestab').val();
	debugger;

	$.post('../ajax/registrogestantes.php?op=importarGestante',{idRegGest:idRegGest, idEstablDestino:idEstablDestino}, function(data, status)
	{
		var resp=data;
		if(resp=='0')
		{
			bootbox.alert('No se pudo hacer la importación, intente de nuevo por favor');
		}else{
			bootbox.alert('Los datos de la Gestante fueron importados existosamente.');
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
function validaCeluGest(e, tnTam, tcNombreCtrl)
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
						bootbox.alert('El numero de Celular de la Gestante YA ESTA REGISTRADA en la Base de Datos, verifique por favor.');
						$("#btnGuardar").prop("disabled",true);
					}else{
						$("#btnGuardar").prop("disabled",false);
					}
				});
			}
      return false;
	}
}

// Funcion que convierte la fecha del formato YYYY-MM-DD al formato DD-MM-YYYY
function convertDateFormat(string) {
  var info;
  if(string==null)
  {
  	return true;
  }else{
  	info = string.split('-');
    return info[2] + '/' + info[1] + '/' + info[0];
  }
}

// Si el campo "celularMadre" pierde el enfoque forzamos a validar el valor del campo.
$(document).ready(function()
	{
	$("#celularMadre").blur(function(){
		$("#btnGuardar").prop("disabled",false);
    return validaCeluGest(event,9,'celularMadre');
	});
});

init();