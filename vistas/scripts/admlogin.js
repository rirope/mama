//funcion que se ejecuta al incio
/* Otra linea insertada para trabajar con git*/
/* Otra mas para trabajar con git */
function init()
{

  $("#frmAcceso").on("submit", function(e)
  {
    iniciarSesion(e);
  });

  $("#btnGrabaUsuario").prop("disabled",true);
  // Cargamos los items al select lista de profesiones
  $.post("../ajax/login.php?op=listaProf", function(r){
    $("#idProf").html(r);
    $('#idProf').selectpicker('refresh');
  });

}

// Funcion para iniciar la sesión
function iniciarSesion(e)
{
  e.preventDefault(); //No se activará la acción predeterminada del evento
  var usuarioa=$('#usuarioa').val();
  var clavea=$('#clavea').val();
  $.post("../ajax/login.php?op=iniciarSesion",{usuarioa:usuarioa, clavea:clavea},function(data, status)
  {
    //data = JSON.parse(data);
    //console.log(data);
    if (data == "s"){
      location.href= 'selecclugaracceso.php';
      //$(location).attr("href","selecclugaracceso.php");
    }else{
      bootbox.alert("Usuario o Contraseña Incorrecta.");
      $("#ingresar").prop("disabled",false);
    }

  });
  limpiar();
}

//Funcion limpiar
function limpiar()
{
  $("#usuarioa").val(""); 
  $("#clavea").val("");
}

function limpiarModalNvo()
{
  // Limpiamos controles
  $("#nroDi").prop("disabled",false);
  $("#nroDi").val('');
  $("#nroDi").val('');
  $("#apePat").val('');
  $("#apeMat").val('');
  $("#nombres").val('');
  $("#idProf").val('-Seleccionar Profesión-');
  $("#idProf").selectpicker('refresh');
  $("#correoElec").val('');
  $("#correoElec").val('');

  $("#iddisaUsu").val('Todas las Geresas');
  $("#iddisaUsu").selectpicker('refresh');

  $("#idredUsu").val('Todas las Redes');
  $("#idredUsu").selectpicker('refresh');

  $("#idmredUsu").val('Todas las MicroRedes');
  $("#idmredUsu").selectpicker('refresh');

  $("#idestabUsu").val('Todos los Establecimientos');
  $("#idestabUsu").selectpicker('refresh');
  $("#fechaReg").val('');
}

function modalNvoUsuario()
{
  limpiarModalNvo()
  $('#usuOperadorNuevo').modal('show');
  var fechaHoy=obtenerFechaHoy();

  $("#btnGrabaUsuario").prop("disabled",true);
  document.getElementById("fechaReg").innerHTML=fechaHoy;
  //Cargamos los items al select disa/geresa del modal
  $.post("../ajax/login.php?op=listaDisas",function(r){
    $("#iddisaUsu").html(r);
    $("#iddisaUsu").selectpicker('refresh');
  });

  //Cargamos los items al select redes del modal
  $.post("../ajax/login.php?op=listaRedes",function(r){
    $("#idredUsu").html(r);
    $("#idredUsu").selectpicker('refresh');
  });

  //Cargamos los items al select microredes del modal
  $.post("../ajax/login.php?op=listaMicRedes",function(r){
    $("#idmredUsu").html(r);
    $("#idmredUsu").selectpicker('refresh');
  });

  //Cargamos los items al select establecimientos del modal
  $.post("../ajax/login.php?op=listaEstablec",function(r){
    $("#idestabUsu").html(r);
    $("#idestabUsu").selectpicker('refresh');
  });

}

//Buscamo si el dni existe en la BD
function buscarDatosOperador()
{
  var nroDni=$('#nroDi').val();

  if (nroDni.length==8)
  {
    $("#nroDi").prop("disabled",true);

    // Se lanza proceso para verificar si el nro de dni ya existe en la BD
    $.post('../ajax/login.php?op=validarDni',{nroDni:nroDni}, function(data,status)
    {
      var rspta=data;
      $("#btnGrabaUsuario").prop("disabled",false);
      if(rspta=='1')
      {
        $('#tcAccion').val('E'); 
        // El nro de dni ya existe, se deben mostrar sus datos
        $.post('../ajax/login.php?op=mostrar',{nroDni:nroDni},function(data,status){
          data = JSON.parse(data);
          
          $("#idPers").val(data.idPers);
          //Datos del usuario
          $("#apePat").val(data.apePat);
          $("#apeMat").val(data.apeMat);
          $("#nombres").val(data.nombres);
          $("#correoElec").val(data.email);
          $("#idProf").val(data.idProf);
          $("#idProf").selectpicker('refresh');

          // Se deben activar los controles de seleccion de lugar de acceso

        })
        //$("#btnGuardar").prop("disabled",false);
        //$("#btnAsignar").prop("disabled",true);
        //bootbox.alert('El Numero de DNI ya existe, verifique por favor.');
      }else{
        bootbox.alert('El DNI no existe ingrese sus datos por favor.');
        $('#tcAccion').val('N'); 
      }
    })
  }else{
    bootbox.alert('El DNI debe tener 8 caracteres, verifique por favor.');
  }
}

//Funcion que graba los datos del usuario.
function grabarDatosUsuario()
{
  var tcAccion=$('#tcAccion').val();
  if(validaDatosRegistro())
  {
    if(tcAccion=='N')
    {
      //Nuevo usuario, se graban todos los datos registrados
      var nroDni=$('#nroDi').val();
      var apePat=$("#apePat").val();
      var apePat=$("#apePat").val();
      var apeMat=$("#apeMat").val();
      var nombres=$("#nombres").val();
      var email=$("#correoElec").val();
      var idProf=$("#idProf").val(); 
      var idDisa=$("#iddisaUsu").val(); 
      var idRed=$("#idredUsu").val(); 
      var idMred=$("#idmredUsu").val(); 
      var idEstab=$("#idestabUsu").val(); 
      // Grabamos los datos del nuevo usuario
      $.post('../ajax/login.php?op=grabarNvoUsuario',{nroDni:nroDni, apePat:apePat, apeMat:apeMat, nombres:nombres, email:email, idProf:idProf,
        idDisa:idDisa, idRed:idRed, idMred:idMred, idEstab:idEstab},function(data, status){
        if(data=='1')
        {
          bootbox.alert('Sus datos se han registrado correctamente. </br> Comuníquese con el administrador para que empieze a registrar a los usuarios MAMA.');
        }else{
          bootbox.alert('Hubo algún problema en el registro de datos. </br> Intente de nuevo, si el problema persiste comuníquese con el admisnitrador.');        
        }
        $('#usuOperadorNuevo').modal('hide');
      })
    }else{
      //El usuario ya existe, se graba solo el lugar que se esta asignando
      var idPers=$("#idPers").val();
      var idProf=$("#idProf").val(); 
      var idDisa=$("#iddisaUsu").val(); 
      var idRed=$("#idredUsu").val(); 
      var idMred=$("#idmredUsu").val(); 
      var idEstab=$("#idestabUsu").val(); 
      //Primero se hace una verificacion para que no se duplique el nuevo lugar de asignacion
      $.post('../ajax/login.php?op=verificarLugarAcceso',{idPers:idPers, idDisa:idDisa, idRed:idRed, idMred:idMred, idEstab:idEstab},function(data,status){
        if(data == '')
        {
          // Se graban solo los datos del nuevo lugar de acceso, solo si no existe el nuevo lugar de asignación
          $.post('../ajax/login.php?op=grabarLugarAcceso',{idPers:idPers, idProf:idProf, idDisa:idDisa, idRed:idRed, idMred:idMred, idEstab:idEstab},function(data, status){
            if(data=='1')
            {
              bootbox.alert('Sus datos se han registrado correctamente. </br> Comuníquese con el administrador para que active su nuevo lugar de acceso.');
            }else{
              bootbox.alert('Hubo algún problema en el registro de datos. </br> Intente de nuevo, si el problema persiste comuníquese con el administrador.');        
            }
            $('#usuOperadorNuevo').modal('hide');
          })
        }else{
          if(data=='1')
          {
            //El lugar existe y está activo para el usuario
            lcMsg='El lugar que esta asignandose YA EXISTE y está ACTIVO, no se puede asignar de nuevo.';
          }else{
            //El lugar existe pero está inactivo para el usuario
            lcMsg='El lugar que esta asignandose YA EXISTE pero está INACTIVO, comuniquese con el administrador para activarlo.';
          }
          bootbox.alert(lcMsg);
          $('#usuOperadorNuevo').modal('hide');
        }
      })
    }
  }
}

//Valida que se hayan ingresado todos los datos para el registro de los datos del usuario que se esta ingresando
function validaDatosRegistro()
{
  var nroDni=$('#nroDi').val();
  var apePat=$('#apePat').val();
  var apeMat=$('#apeMat').val();
  var nombres=$('#nombres').val();
  var correoElec=$('#correoElec').val();
  var iddisaUsu=$('#iddisaUsu').val();
  var idredUsu=$('#idredUsu').val();
  var idmredUsu=$('#idmredUsu').val();
  var idestabUsu=$('#idestabUsu').val();

  if(nroDni.length==0)
  {
    bootbox.alert('Debe ingresar el Numero de DNI');
    return false;
  }

  if(apePat.length==0)
  {
    bootbox.alert('Debe ingresar el Apellido Paterno');
    return false;
  }

  if(apeMat.length==0)
  {
    bootbox.alert('Debe ingresar el Apellido Materno');
    return false;
  }

  if(nombres.length==0)
  {
    bootbox.alert('Debe ingresar su(s) Nombre(s)');
    return false;
  }

  if(correoElec.length==0)
  {
    bootbox.alert('Debe ingresar su Correo Electronico.');
    return false;
  }

  if(iddisaUsu=='00')
  {
    bootbox.alert('Debe seleccionar la DISA/GERESA.');
    return false;
  }

  if(idredUsu=='0000')
  {
    bootbox.alert('Debe seleccionar la RED.');
    return false;
  }

  if(idmredUsu=='000000')
  {
    bootbox.alert('Debe seleccionar la MICRORED.');
    return false;
  }

  if(idestabUsu=='0')
  {
    bootbox.alert('Debe seleccionar el ESTABLECIMIENTO DE SALUD.');
    return false;
  }

  return true;
}

//Limpiar el campo que recibe el email del usuario
function limpiarModalRecuperarEmail()
{
  $('#correoElecRec').val('');
}

//Abrir la venta model para que el usuario ingrese su email y recupere su usuario y clave.
function modalRecuperaEMail()
{
  limpiarModalRecuperarEmail()
  $('#recuperarPass').modal('show');
}

//Funcion que verifica si el email ingresado existe en la BD, si es asi recoje los datos(usuario y clave) de ese usuario y lo envia al
//correo electrónico que se esta validando, sino se muestra un mensaje de alerta "Emkail no existe en la BD".
function validarEmail()
{
  var correoElecRec=$('#correoElecRec').val();

  if(verificaEmail(correoElecRec))
  {
    $.post('../ajax/login.php?op=verificarEmail',{correoElecRec:correoElecRec},function(data,status){
      if(data=='S')
      {
        //Significa que el email existe en la BD y se hizo el envio del correo electronico con sus datos al usuario
        lcMsg="Un correo con los datos de su Usuario y Clave actual ha sido enviado a su correo.</br>";
        lcMsg=lcMsg + "En su bandeja revise tambien en Correo No Deseado.";
        bootbox.alert(lcMsg);
      }else{
        bootbox.alert('El Email ingresado No Existe en la Base de Datos, verifique por favor.');
      }

    })  
  }
}

//Funcion que valida si se ha ingresado un email correcto
function verificaEmail(correoElecRec)
{
  var patron=/^(?:[^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*|"[^\n"]+")@(?:[^<>()[\].,;:\s@"]+\.)+[^<>()[\]\.,;:\s@"]{2,63}$/i;
  //var patron=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;

  if(correoElecRec.length==0)
  {
    bootbox.alert('No ingresó su Correo Electrónico. Intente de Nuevo por favor.');
    return false;
  }

  if(correoElecRec.search(patron)==0)
  {
    //Mail correcto
    return true;
  }else{
    bootbox.alert('El Correo ingresado no es correcto');
    return false;
  }

  return true;
}

//Funcion que carga las redes de acuerdo a la geresa seleccionada.
function cargarRedesUsu(iddisa)
{
    document.getElementById("idredUsu").disabled=false; 
    //Cargamos los items al select red
    $.post("../ajax/login.php?op=listaRedes", {iddisa : iddisa}, function(r){
        $("#idredUsu").html(r);
        $('#idredUsu').selectpicker('refresh');
      });
    cargarMicroRedesUsu('0000');
    cargarEstablUsu('0');
}

//Funcion que carga las redes de acuerdo a la geresa seleccionada.
function cargarMicroRedesUsu(idred)
{
  document.getElementById("idmredUsu").disabled=false; 
    //Cargamos los items al select microred
  $.post("../ajax/login.php?op=listaMicRedes", {idred : idred}, function(r){
      $("#idmredUsu").html(r);
      $('#idmredUsu').selectpicker('refresh');
  }); 
  cargarEstablUsu('0');
}

//Funcion que carga las redes de acuerdo a la geresa seleccionada.
function cargarEstablUsu(idmred)
{
  document.getElementById("idestabUsu").disabled=false; 
    //Cargamos los items al select establec
  $.post("../ajax/login.php?op=listaEstablec", {idmred : idmred}, function(r){
      $("#idestabUsu").html(r);
      $('#idestabUsu').selectpicker('refresh');
  });
}

//funcion que obtiene la fecha de hoy
function obtenerFechaHoy()
{
  var hoy = new Date();
  var dd = hoy.getDate();
  var mm = hoy.getMonth()+1;
  var yyyy = hoy.getFullYear();
  
  // Agregar cero a la izquierda si el dia tiene 1 digito
  if(dd<10) {
      dd='0'+dd;
  } 
  
  // Agregar cero a la izquierda si el mes tiene 1 digito
  if(mm<10) {
      mm='0'+mm;
  } 

  return dd+'/'+mm+'/'+yyyy;
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