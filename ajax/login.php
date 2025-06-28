<?php
// Liinea para usar con git
session_start(); 
require_once "../modelos/AdmLogin.php";

$AdmLogin=new AdmLogin();

$usuario = isset($_POST["usuarioa"]) ? limpiarCadena($_POST["usuarioa"]):"";
$clave = isset($_POST["clavea"]) ? limpiarCadena($_POST["clavea"]):"";

switch ($_GET["op"])
{
	case 'cerrar_sesion':
		$AdmLogin->cerrar_sesion(); 
		break;

	case 'iniciarSesion':
		$rspta =  $AdmLogin->inciar_sesion_usuario($usuario, $clave); 
		echo $rspta;
		break;

	//Se verifica si el dni del operador que esta registrando sus datos y existen en la bd
	case 'validarDni':
		$nroDni=$_POST['nroDni'];
		$rspta=$AdmLogin->validarDni($nroDni);
 		echo $rspta;
		break;

	//Muestra los datos del usuario operador basado en su dni
	case 'mostrar':
		$nroDni=$_POST['nroDni'];
		$rspta=$AdmLogin->mostrar($nroDni);
		//Codificar el resultado utilizando json, porque en este caso se recibe un array de registro
		echo json_encode($rspta);
		break;

	//Grabamos los datos del nuevo usuario
	case 'grabarNvoUsuario':
		$apePat=limpiarCadena($_POST["apePat"]);
		$apeMat=limpiarCadena($_POST["apeMat"]);
		$nombres=limpiarCadena($_POST["nombres"]);
		$idProf=limpiarCadena($_POST["idProf"]);
		$nroDni=limpiarCadena($_POST["nroDni"]);
		$correoElec=limpiarCadena($_POST["email"]);
		$idDisa=limpiarCadena($_POST["idDisa"]);
		$idRed=limpiarCadena($_POST["idRed"]);
		$idMred=limpiarCadena($_POST["idMred"]);
		$idEstab=limpiarCadena($_POST["idEstab"]);
		$rspta=$AdmLogin->grabarNvoUsuario($apePat,$apeMat,$nombres,$idProf,$nroDni,$correoElec,$idDisa,$idRed,$idMred,$idEstab);
		echo $rspta;
		break;

	//Grabamos los datos del lugar de acceso ya que el usuario ya existe en la BD y se esta asignando otro lugar de acceso
	case 'grabarLugarAcceso':
		$idPers=limpiarCadena($_POST["idPers"]);
		$idProf=limpiarCadena($_POST["idProf"]);
		$idDisa=limpiarCadena($_POST["idDisa"]);
		$idRed=limpiarCadena($_POST["idRed"]);
		$idMred=limpiarCadena($_POST["idMred"]);
		$idEstab=limpiarCadena($_POST["idEstab"]);
		$rspta=$AdmLogin->grabarLugarAcceso($idPers,$idProf,$idDisa,$idRed,$idMred,$idEstab);
		echo $rspta;
		break;

	//Grabamos los datos del lugar de acceso ya que el usuario ya existe en la BD y se esta asignando otro lugar de acceso
	case 'verificarLugarAcceso':
		$idPers=limpiarCadena($_POST["idPers"]);
		$idDisa=limpiarCadena($_POST["idDisa"]);
		$idRed=limpiarCadena($_POST["idRed"]);
		$idMred=limpiarCadena($_POST["idMred"]);
		$idEstab=limpiarCadena($_POST["idEstab"]);
		$rspta=$AdmLogin->verificarLugarAcceso($idPers,$idDisa,$idRed,$idMred,$idEstab);
		echo $rspta;
		break;

	//Grabamos los datos del lugar de acceso ya que el usuario ya existe en la BD y se esta asignando otro lugar de acceso
	case 'verificarEmail':
		$correoElecRec=limpiarCadena($_POST["correoElecRec"]);
		$rspta=$AdmLogin->verificarEmail($correoElecRec);
		if($rspta)
		{
			//Agregar el codigo que envia el Correo Electronico
			while ($reg=$rspta->fetch_object())
			{
				$nombreUsuario=$reg->nombreUsuario;
				$clave=$reg->clave;
			}
			$para = $correoElecRec;
			$asunto = "Envia solicitud de usuario y contraseña MAMA";
			$mensaje = "En respuesta a su solicitud se le envia sus datos de:</br>";
			$mensaje .= "Usuario: ".$nombreUsuario."</br>";
			$mensaje .= "Clave  : ".$clave."</br>";
			$mensaje .= "Felicitaciones, ya puede ingresar al aplicativo MAMA.";
			$cabeceras ="From: soportemama@outlook.com";
			mail($para, $asunto, $mensaje, $cabeceras);
			echo 'S';
		}else{
			echo 'N';
		}
		break;

	// Esta opcion es para cargar el control select con la lista de grados de instrucción
	case 'listaProf':
		$rspta = $AdmLogin->listaProf();
		echo '<option value="0000">-Seleccionar Profesión-</option>';
		while ($reg = $rspta->fetch_object())
		{
			echo '<option value='. "'$reg->idWsDat01'" . '>' . $reg->descripcion . '</option>';
		}
		break;

	// Esta opcion es para cargar el control select con la lista de disas/geresas
	case 'listaDisas':
		require_once "../modelos/AdmDisaGeresa.php";
		$admDg = new AdmDisaGeresa();
		$rspta = $admDg->listadisas();
		echo '<option value="00">Todas las Geresas</option>';
		while ($reg = $rspta->fetch_object())
		{
			echo '<option value=' . "'$reg->idDisa'" . '>' . $reg->descDisa . '</option>';
		}
		break;

	// Esta opcion es para cargar el control select con la lista de redes que perteneces a una disa
	case 'listaRedes':
		require_once "../modelos/AdmRedes.php";
	  $iddisa=$_POST["iddisa"];
		$admRed = new AdmRedes();
		$rspta = $admRed->listaRedesxDisa($iddisa);
		echo '<option value="0000">Todas las Redes</option>';
		while ($reg = $rspta->fetch_object())
		{
			echo '<option value='. "'$reg->idRed'" . '>' . $reg->descRed . '</option>';
		}
		break;

	// Esta opcion es para cargar el control select con la lista de microredes que pertenece a una red
	case 'listaMicRedes':
		require_once "../modelos/AdmMicRedes.php";
	  $idred=$_POST["idred"];
		$admMicRed = new AdmMicRedes();
		$rspta = $admMicRed->listaMicRedesxRed($idred);
		echo '<option value="000000">Todas las MicroRedes</option>';
		while ($reg = $rspta->fetch_object())
		{
			echo '<option value='. "'$reg->idMred'" . '>' . $reg->descMicRed . '</option>';
		}
		break;

	// Esta opcion es para cargar el control select con la lista de redes que perteneces a una disa
	case 'listaEstablec':
		require_once "../modelos/AdmEstablec.php";
	  $idmred=$_POST["idmred"];
		$admEstab = new AdmEstablec();
		$rspta = $admEstab->listaEstabxMicRed($idmred);
		echo '<option value="0">Todos los Establecimientos</option>';
		while ($reg = $rspta->fetch_object())
		{
			echo '<option value='. $reg->idEstabl . '>' . $reg->descEstab . '</option>';
		}
		break;


}
?>