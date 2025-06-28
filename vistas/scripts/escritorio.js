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

				    generaDashboard();
				});
			}); 
	  });
	});	

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

// Funcion que genera los datos que se visualizaran en el dashboard
function generaDashboard()
{
	var iddisa = $("#iddisa").val();
	var idred = $("#idred").val();
	var idmred = $("#idmred").val();
	var idestab = $("#idestab").val();
	var annio = $("#annio").val();
	var fechaIni = $("#fechaIni").val();
	var fechaFin = $("#fechaFin").val();

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

	obtenerTotalUsuariosMama(fechaIni,fechaFin,iddisa,idred,idmred,idestab)
	obtenerTotalNinos(fechaIni,fechaFin,iddisa,idred,idmred,idestab)
	obtenerTotalGest(fechaIni,fechaFin,iddisa,idred,idmred,idestab)
	obtenerNinosMen30Dias(iddisa,idred,idmred,idestab);
	obtenerGestFpp30Dias(iddisa,idred,idmred,idestab);
	obtenerNinos2mVRNP(iddisa,idred,idmred,idestab);
	obtenerNinos31y60(iddisa,idred,idmred,idestab);
	obtenerNinos61y90(iddisa,idred,idmred,idestab);
	obtenerNinos110y130(iddisa,idred,idmred,idestab);
	generaGrafBarras(iddisa,idred,idmred,idestab);
	generaGrafLineas(fechaIni,iddisa,idred,idmred,idestab);
}

//Función para obtener el total de usuarios activos registrados en el rango de fecha seleccionado
function obtenerTotalUsuariosMama(fechaIni,fechaFin,iddisa,idred,idmred,idestab)
{
	$.post("../ajax/escritorio.php?op=obtenerTotalUsuariosMama",{fechaIni:fechaIni, fechaFin:fechaFin, iddisa:iddisa, idred:idred, idmred:idmred, idestab:idestab}, function(data, result)
		{
			var totUsuMama;
			if(data==undefined || data==null)
			{
				totUsuMama=0; 
			}else{
				totUsuMama=data;
			}
			$('#totUsu').html(totUsuMama);
		});
}

//Función para obtener el total de niños activos registrados en el rango de fecha seleccionado
function obtenerTotalNinos(fechaIni,fechaFin,iddisa,idred,idmred,idestab)
{
	$.post("../ajax/escritorio.php?op=obtenerTotalNinos",{fechaIni:fechaIni, fechaFin:fechaFin, iddisa:iddisa, idred:idred, idmred:idmred, idestab:idestab}, function(data, result)
		{
			var totNinos;
			if(data==undefined || data==null)
			{
				totNinos=0; 
			}else{
				totNinos=data;
			}
			$('#totNinos').html(totNinos);
		});
}

//Función para obtener el total de gestantes activas registrados en el rango de fecha seleccionado
function obtenerTotalGest(fechaIni,fechaFin,iddisa,idred,idmred,idestab)
{
	$.post("../ajax/escritorio.php?op=obtenerTotalGest",{fechaIni:fechaIni, fechaFin:fechaFin, iddisa:iddisa, idred:idred, idmred:idmred, idestab:idestab}, function(data, result)
		{
			var totGest;
			if(data==undefined || data==null)
			{
				totGest=0; 
			}else{
				totGest=data;
			}
			$('#totGest').html(totGest);
		});
}

//Función para obtener el numero de niños menores de 30 dias de nacido a la fecha actual
function obtenerNinosMen30Dias(iddisa,idred,idmred,idestab)
{
	$.post("../ajax/escritorio.php?op=obtenerNinosMen30Dias",{iddisa:iddisa, idred:idred, idmred:idmred, idestab:idestab}, function(data, result)
		{
			var numNinos;
			if(data==undefined || data==null)
			{
				numNinos=0; 
			}else{
				numNinos=data;
			}
			$('#ninosMen30').html(numNinos);
		});
}

//Función para obtener el numero de niños que cumplieron 2 meses fecha actual, se toma en cuenta
//los que van a cumplir una semana despues y los que cumplieron una semana antes.
function obtenerNinos2mVRNP(iddisa,idred,idmred,idestab)
{
	$.post("../ajax/escritorio.php?op=obtenerNinos2mVRNP",{iddisa:iddisa, idred:idred, idmred:idmred, idestab:idestab}, function(data, result)
		{
			var numNinos2m;
			if(data==undefined || data==null)
			{
				numNinos2m=0; 
			}else{
				numNinos2m=data;
			}
			$('#ninos2m').html(numNinos2m);
		});
}

//Función para obtener el numero de niños que van a cumplir entre 31 y 60 dias de nacido
function obtenerNinos31y60(iddisa,idred,idmred,idestab)
{
	$.post("../ajax/escritorio.php?op=obtenerNinos31y60",{iddisa:iddisa, idred:idred, idmred:idmred, idestab:idestab}, function(data, result)
		{
			var numNinos3160;
			if(data==undefined || data==null)
			{
				numNinos3160=0; 
			}else{
				numNinos3160=data;
			}
			$('#ninos3160').html(numNinos3160);
		});
}

//Función para obtener el numero de niños que van a cumplir entre 61 y 90 dias de nacido
function obtenerNinos61y90(iddisa,idred,idmred,idestab)
{
	$.post("../ajax/escritorio.php?op=obtenerNinos61y90",{iddisa:iddisa, idred:idred, idmred:idmred, idestab:idestab}, function(data, result)
		{
			var numNinos6190;
			if(data==undefined || data==null)
			{
				numNinos6190=0; 
			}else{
				numNinos6190=data;
			}
			$('#ninos6190').html(numNinos6190);
		});
}

//Función para obtener el numero de niños que tiene entre 110 y 130 dias de nacido
function obtenerNinos110y130(iddisa,idred,idmred,idestab)
{
	$.post("../ajax/escritorio.php?op=obtenerNinos110y130",{iddisa:iddisa, idred:idred, idmred:idmred, idestab:idestab}, function(data, result)
		{
			var numNinos110130;
			if(data==undefined || data==null)
			{
				numNinos110130=0; 
			}else{
				numNinos110130=data;
			}
			$('#ninos110130').html(numNinos110130);
		});
}

//Función para obtener el numero de gestantes con FPP en los siguientes 30 dias a la fecha actual
function obtenerGestFpp30Dias(iddisa,idred,idmred,idestab)
{
	$.post("../ajax/escritorio.php?op=obtenerGestFpp30Dias",{iddisa:iddisa, idred:idred, idmred:idmred, idestab:idestab}, function(data, result)
		{
			var numGestFpp;
			if(data==undefined || data==null)
			{
				numGestFpp=0; 
			}else{
				numGestFpp=data;
			}
			$('#gestFpp').html(numGestFpp);
		});
}

//Función que genera el grafico de barras
function generaGrafBarras(iddisa,idred,idmred,idestab)
{
	$.post("../ajax/escritorio.php?op=generaGrafBarras",{iddisa:iddisa, idred:idred, idmred:idmred, idestab:idestab}, function(rsptaData, result)
		{
			var arregloAnnio=new Array();
			var arregloGest=new Array();
			var arregloNin=new Array();

			rspta = JSON.parse(rsptaData);
			debugger;
			annioAnt=rspta[0].annio;
			annioNvo=annioAnt;
			numGest=0;
			numNin=0;
			//arregloAnnio.push(annioAnt);
			//arregloGest.push(numGest);
			//arregloNin.push(numNin);
			for (var i =0; i < rspta.length ; i++)
			{
				if(annioAnt!=rspta[i].annio)
				{
					//annioAnt=rspta[i].annio;
					annioNvo=rspta[i].annio;
					//arregloAnnio.push(rspta[i].annio);
					arregloAnnio.push(annioAnt);
					arregloGest.push(numGest);
					arregloNin.push(numNin);
					numGest=0;
					numNin=0;
				}
				annioAnt=annioNvo;
				numGest=numGest + parseInt(rspta[i].benefG);
				numNin=numNin + parseInt(rspta[i].benefN);
			}
			arregloAnnio.push(annioAnt);
			arregloGest.push(numGest);
			arregloNin.push(numNin);

		  var datos = {
		    labels : arregloAnnio,
		    datasets : [
		      {
		        label : "Gestantes",
		        backgroundColor : "rgba(255, 99, 132, 0.6)",
		        data : arregloGest
		      },
		      {
		        label : "Niños",
		        backgroundColor : "rgba(75, 192, 192, 0.6)",
		        data : arregloNin
		      },
		    ]
		  };

		  var canvas = document.getElementById('graf01').getContext('2d');
		  window.bar = new Chart(canvas, {
		    type : "bar",
		    data : datos,
		    options : {
		      scales: {
		        xAxes: [{
		          gridLines: {
		            display: true,
		            drawBorder: true,
		            drawOnChartArea: false,
		            },
		          scaleBeginAtZero: false,
		          barPercentage: 1,
		          categoryPercentage: 0.6,
		          }],
		        yAxes: [{
		          gridLines: {
		            display: true,
		            drawBorder: true,
		            drawOnChartArea: false,
		            },
		          scaleBeginAtZero: true,
		          barPercentage: 1,
		          categoryPercentage: 0.6,
		          }]
		      },
		      elements : {
		        rectangle : {
		          borderWidth : 1,
		          borderSkipped : 'bottom'
		        }
		      },
		      responsive : true,
		      title : {
		        display : true
		        //text : "Prueba de grafico de barras"
		      }
		    }
		  });
		});

}

// Funcon que genera el grafico de lineas
function generaGrafLineas(fechaIni,iddisa,idred,idmred,idestab)
{
	var annioGrafLin=$('#fechaIni').val();
	$('#annioGrafLin').html(annioGrafLin.substr(0,4));

	$.post("../ajax/escritorio.php?op=generaGrafLineas",{fechaIni:fechaIni, iddisa:iddisa, idred:idred, idmred:idmred, idestab:idestab},function(rsptaData,result){
		rspta = JSON.parse(rsptaData);
		var arregloMes=new Array();
		var arregloGest=new Array();
		var arregloNin=new Array();

		mesAnt=rspta[0].mes;
		arregloMes.push(mesAnt)
		for (var i =0; i < rspta.length ; i++)
		{
			if(mesAnt!=rspta[i].mes)
			{
				mesAnt=rspta[i].mes;
				arregloMes.push(rspta[i].mes);
			}

			if(rspta[i].tipoPersona=='G')
			{
				arregloGest.push(rspta[i].numBenef);
			}else{
				arregloNin.push(rspta[i].numBenef);
			}
		}

	  var datos = {
	    labels : arregloMes,
	    datasets : [
	      {
	        label : "Gestantes",
	        backgroundColor: "rgba(255, 99, 132, 1)",
					borderColor: "rgba(255, 99, 132, 1)",
	        data : arregloGest,
	        fill: false,
	      },
	      {
	        label : "Niños",
	        backgroundColor: "rgba(75, 192, 192, 0.6)",
					borderColor: "rgba(75, 192, 192, 1)",
	        data : arregloNin,
	        fill: false
	      },
	    ]
	  };

	  var canvas = document.getElementById('graf02').getContext('2d');
	  window.bar = new Chart(canvas, {
	    type : "line",
	    data : datos,
	    options : {
	      scales: {
	        xAxes: [{
	          gridLines: {
	            display: true,
	            drawBorder: true,
	            drawOnChartArea: false,
	            },
	          scaleBeginAtZero: false,
	          }],
	        yAxes: [{
	          gridLines: {
	            display: true,
	            drawBorder: true,
	            drawOnChartArea: false,
	            },
	          scaleBeginAtZero: true,
	          }]
	      },
	      elements : {
	        rectangle : {
	          borderWidth : 1,
	          borderSkipped : 'bottom'
	        }
	      },
	      responsive : true,
	      title : {
	        display : true
	        //text : "Prueba de grafico de barras"
	      }
	    }
	  });
	});

}


init();