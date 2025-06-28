<?php
require_once "../modelos/AdmModuloOpcion.php";

$admModuloOpcion=new AdmModuloOpcion();

$idModulo=isset($_POST["idModulo"]) ? limpiarCadena($_POST["idModulo"]):"";
$idModuloo=isset($_POST["idModuloo"]) ? limpiarCadena($_POST["idModuloo"]):"";
$idOpcion=isset($_POST["idOpcion"]) ? limpiarCadena($_POST["idOpcion"]):"";
$nomOpcion=isset($_POST["nomOpcion"]) ? limpiarCadena($_POST["nomOpcion"]):"";
$rutaOpcion=isset($_POST["rutaOpcion"]) ? limpiarCadena($_POST["rutaOpcion"]):"";
$nomModulo=isset($_POST["nomModulo"]) ? strtoupper(limpiarCadena($_POST["nomModulo"])):"";
$descModulo=isset($_POST["descModulo"]) ? strtoupper(limpiarCadena($_POST["descModulo"])):"";
$clase=isset($_POST["clase"]) ? limpiarCadena($_POST["clase"]):"";
$estado=isset($_POST["estado"]) ? limpiarCadena($_POST["estado"]):"";

switch ($_GET["op"])
{
	case 'guardaryeditar':
		if (empty($idModulo)){
			// Se esta ingresando un nuevo registro
			$rspta=$admModuloOpcion->insertar($nomModulo,$descModulo,$clase,$estado);
			echo $rspta ? "Los Datos del Modulo fueron Registrados." : "Los Datos del Modulo No se pudieron Registrar.";
		} else {
			// Se esta actualizando los datos de un establecimiento
			$rspta=$admModuloOpcion->editar($idModulo,$nomModulo,$descModulo,$clase,$estado);
			echo $rspta ? "Los Datos del Modulo fueron Actualizados." : "Los Datos del Modulo No se pudieron Actualizar."; 
		}
		break;

	case 'guardaryeditarO':
		if (empty($idOpcion)){
			// Se esta ingresando un nuevo registro
			$rspta=$admModuloOpcion->insertarO($idModuloo,$nomOpcion,$rutaOpcion);
			echo $rspta ? "Los Datos de la Opcion fueron Registrados." : "Los Datos de la Opcion No se pudieron Registrar.";
		} else {
			// Se esta actualizando los datos de un establecimiento
			$rspta=$admModuloOpcion->editarO($idOpcion,$nomOpcion,$rutaOpcion);
			echo $rspta ? "Los Datos de la Opcion fueron Actualizados." : "Los Datos de la Opcion No se pudieron Actualizar."; 
		}
		break;

	case 'desactivar':
		$rspta=$admModuloOpcion->desactivar($idModulo);
 		echo $rspta ? "El Modulo fue Desactivado" : "El Modulo NO se puede Desactivar";
		break;

	case 'desactivarO':
		$rspta=$admModuloOpcion->desactivarO($idOpcion);
 		echo $rspta ? "La Opcion fue Desactivada" : "La Opcion NO se puede Desactivar";
		break;

	case 'activar':
		$rspta=$admModuloOpcion->activar($idModulo);
 		echo $rspta ? "El Modulo fue Activado" : "El Modulo NO se puede Activar";
		break;

	case 'activarO':
		$rspta=$admModuloOpcion->activarO($idOpcion);
 		echo $rspta ? "La Opcion fue Activada" : "La Opcion NO se puede Activar";
		break;
	
	case 'mostrar':
		$rspta=$admModuloOpcion->mostrar($idModulo);
		//Codificar el resultado utilizando json, porque en este caso se recibe un array de registro
		echo json_encode($rspta);
		break;

	case 'mostrarOpcion':
		$rspta=$admModuloOpcion->mostrarOpcion($idOpcion);
		//Codificar el resultado utilizando json, porque en este caso se recibe un array de registro
		echo json_encode($rspta);
		break;

	case 'listar':
	    $estado=$_REQUEST["estado"];
		$rspta=$admModuloOpcion->listar($estado);
		//Declaramos un array para mostrar todos los registros que se estan obteniendo
		$data=Array();
		while($reg=$rspta->fetch_object()){
			$data[]=Array(
					"0"=>($reg->vigencia == "S")?'<button class="btn btn-xs btn-primary" onclick="mostrar('."'$reg->idModulo'".')"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-xs btn-warning" onclick="desactivar('."'$reg->idModulo'".')"><i class="fa fa-close"></i></button>'.' <button class="btn btn-xs btn-danger" onclick="eliminar('."'$reg->idModulo'".')"><i class="fa fa-trash"></i></button>':' <button class="btn btn-xs btn-success" onclick="activar('."'$reg->idModulo'".')"><i class="fa fa-check"></i></button>',
					"1"=>$reg->nombre,
					"2"=>$reg->descripcion,
					"3"=>$reg->class,
					"4"=>($reg->vigencia == "S")?'<span class="label bg-green">Activo</span>':'<span class="label bg-red">Inactivo</span>',
					"5"=>' <button class="btn btn-xs btn-warning" onclick="opciones('."'$reg->idModulo'".')"><i class="fa fa-cog"></i></button>',
				);
		}
		$results=Array(
				"sEcho"=>1, //Informacion para el datatables
				"iTotalRecords"=>count($data), //Enviamos el total de registros al datatables
				"iTotalDisplayRecords"=>count($data), //Enviamos el total de registros a visualizar
				"aaData"=>$data);
		echo json_encode($results);
		break;

	case 'listarOpciones':
	    $idModulo=$_REQUEST["idModulo"];
		$rspta=$admModuloOpcion->listarOpcion($idModulo);
		//Declaramos un array para mostrar todos los registros que se estan obteniendo
		$data=Array();
		while($reg=$rspta->fetch_object()){
			$data[]=Array(
					"0"=>($reg->vigencia == "S")?'<button class="btn btn-xs btn-primary" onclick="mostrarOpcion('."'$reg->idOpcion'".')"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-xs btn-warning" onclick="desactivarO('."'$reg->idOpcion'".')"><i class="fa fa-close"></i></button>'.' <button class="btn btn-xs btn-danger" onclick="eliminarO('."'$reg->idOpcion'".')"><i class="fa fa-trash"></i></button>':' <button class="btn btn-xs btn-success" onclick="activarO('."'$reg->idOpcion'".')"><i class="fa fa-check"></i></button>',
					"1"=>$reg->nombre,
					"2"=>$reg->url,
					"3"=>($reg->vigencia == "S")?'<span class="label bg-green">Activo</span>':'<span class="label bg-red">Inactivo</span>',
				);
		}
		$results=Array(
				"sEcho"=>1, //Informacion para el datatables
				"iTotalRecords"=>count($data), //Enviamos el total de registros al datatables
				"iTotalDisplayRecords"=>count($data), //Enviamos el total de registros a visualizar
				"aaData"=>$data);
		echo json_encode($results);
		break;

	case 'eliminar':
		$rspta=$admModuloOpcion->eliminar($idModulo);
		echo $rspta ? "El Modulo fue Eliminado." : "El Modulo no se pudo Eliminar porque YA TIENE Movimiento."; 
		break;

	case 'eliminarO':
		$rspta=$admModuloOpcion->eliminarO($idOpcion);
		echo $rspta ? "La Opcion fue Eliminada." : "La Opcion no se pudo Eliminar porque YA TIENE Movimiento."; 
		break;
}
?>