//Funcion que se ejecuta al inicio
function init()
{
	limpiar();
	// Cargamos los items al select lista de profesiones
	$.post("../ajax/admmisdatosoperador.php?op=listaProf", function(r){
        $("#idProf").html(r);
        $('#idProf').selectpicker('refresh');
	});

	var idUsu=$('#idUsu').val();
	mostrar(idUsu);

	$("#formulario").on("submit", function(e)
	{
		guardaryeditar(e);
	});

}

//Funcion limpiar
function limpiar()
{
	//$("#idUsu").val('');
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
}

// Funcion para guardar y editar
function guardaryeditar(e)
{
	var llSwValidarDniAnt=false;

	e.preventDefault(); //No se activará la acción predeterminada del evento
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

	//Verificamos si hubo cambio en el nro de dni
	if(nroDni!=nroDniAnt)
	{
		llSwValidarDniAnt=true;
	}

	if(llSwValidarDniAnt)
	{
		// Se lanza proceso para validar si el nro de dni ya existe en la tabla perssalud
		$.post('../ajax/admmisdatosoperador.php?op=validarDni',{nroDni:nroDni}, function(data,status)
		{
			var rspta=data;
			if(rspta=='1')
			{
				// El nro de dni ya existe, no se puede grabar
				bootbox.alert('El Numero de DNI que está cambiando ya existe, verifique por favor.');
			}else{
				// Los datos pueden grabarse, no hay dni duplicado
				grabarDatosUsuarioOperador(idUsu,apePat,apeMat,nombres,idProf,nroDni,correoElec,nomUsu,passUsu)
			}
		})
	}else{
		grabarDatosUsuarioOperador(idUsu,apePat,apeMat,nombres,idProf,nroDni,correoElec,nomUsu,passUsu)
	}
}

//Funcion que graba los datos del usuario que se registra como nuevo o en modo de edicion
function grabarDatosUsuarioOperador(idUsu,apePat,apeMat,nombres,idProf,nroDni,correoElec,nomUsu,passUsu)
{
  $.post('../ajax/admmisdatosoperador.php?op=guardaryeditar', {idUsu:idUsu,apePat:apePat,apeMat:apeMat,nombres:nombres,
  		idProf:idProf,nroDni:nroDni,correoElec:correoElec,nomUsu:nomUsu,passUsu:passUsu}, function(data, status)
	{
		var idUsu=data;
		$("#idUsu").val(idUsu);
		if (idUsu!='' || idUsu!=undefined)
		{
			bootbox.alert('Los Datos del Usuario fueron actualizados.');
		}else{
			bootbox.alert('Los Datos del Nuevo Usuario No se pudieron actualizar.');
		}
  });	
}

// Funcion que va a mostrar los datos de un registro en el formulario cuando se edita.
function mostrar(idUsu)
{
	$.post("../ajax/admmisdatosoperador.php?op=mostrar",{idUsu : idUsu}, function(data, status)
	{
		data = JSON.parse(data);

		//$("#idUsu").val(idUsu);
		//Datos del usuario
		$("#apePat").val(data.apePat);
		$("#apeMat").val(data.apeMat);
		$("#nombres").val(data.nombres);
		$("#idProf").val(data.idProf);
		$("#nroDni").val(data.nroDni);
		$("#nroDniAnt").val(data.nroDni);
		$("#correoElec").val(data.email);
		$("#nomUsu").val(data.name);
		$("#passUsu").val(data.password);
		$("#idProf").selectpicker('refresh');
	});

}

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