<?php
session_start();
require_once "../modelos/AdmMisDatosOperador.php";

$regAdm=new AdmMisDatosOperador();
//Datos del usuario
$idUsu=isset($_POST["idUsu"]) ? limpiarCadena($_POST["idUsu"]):"";
$apePat=isset($_POST["apePat"]) ? limpiarCadena($_POST["apePat"]):"";
$apeMat=isset($_POST["apeMat"]) ? limpiarCadena($_POST["apeMat"]):"";
$nombres=isset($_POST["nombres"]) ? limpiarCadena($_POST["nombres"]):"";
$idProf=isset($_POST["idProf"]) ? limpiarCadena($_POST["idProf"]):"";
$nroDni=isset($_POST["nroDni"]) ? limpiarCadena($_POST["nroDni"]):"";
$correoElec=isset($_POST["correoElec"]) ? limpiarCadena($_POST["correoElec"]):"";
$nomUsu=isset($_POST["nomUsu"]) ? limpiarCadena($_POST["nomUsu"]):"";
$passUsu=isset($_POST["passUsu"]) ? limpiarCadena($_POST["passUsu"]):"";

switch ($_GET["op"])
{
	case 'guardaryeditar':
		// Se esta actualizando los datos del usuario
		$rspta=$regAdm->editar($idUsu,$apePat,$apeMat,$nombres,$idProf,$nroDni,$correoElec,$nomUsu,$passUsu);
		echo $rspta ? "Los Datos del Usuario fueron actualizados." : "Los Datos del Usuario no se pudieron actualizar."; 
		break;

	case 'validarDni':
		$rspta=$regAdm->validarDni($nroDni);
 		echo $rspta;
		break;

	case 'buscarDniOperador':
		$rspta=$regAdm->buscarDniOperador($nroDni);
 		echo json_encode($rspta);
		break;

	case 'mostrar':
		$rspta=$regAdm->mostrar($idUsu);
		//Codificar el resultado utilizando json, porque en este caso se recibe un array de registro
		echo json_encode($rspta);
		break;

	// Esta opcion es para cargar el control select con la lista de grados de instrucción
	case 'listaProf':
		$rspta = $regAdm->listaProf();
		while ($reg = $rspta->fetch_object())
		{
			echo '<option value='.$reg->idWsDat01. '>' . $reg->descripcion . '</option>';
		}
		break;

}
?>