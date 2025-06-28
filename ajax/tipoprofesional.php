<?php
require_once "../modelos/TipoProfesional.php";

$tipoProf=new TipoProfesional();

$idwsdat01=isset($_POST["idwsdat01"]) ? limpiarCadena($_POST["idwsdat01"]):"";
$descripcion=isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]):"";

switch ($_GET["op"])
{
	case 'guardaryeditar':
		if (empty($idwsdat01)){
			// Se esta ingresando un nuevo registro
			$rspta=$tipoProf->insertar($descripcion);
			echo $rspta ? "Tipo de Profesional registrado." : "Tipo de Profesional no se pudo registrar.";
		} else {
			// Se esta actualizando los datos de un establecimiento
			$rspta=$tipoProf->editar($idwsdat01,$descripcion);
			echo $rspta ? "Tipo de Profesional actualizado." : "Tipo de Profesional no se pudo actualizar."; 
		}
		break;
	
	case 'mostrar':
		$rspta=$tipoProf->mostrar($idwsdat01);
		//Codificar el resultado utilizando json, porque en este caso se recibe un array de registro
		echo json_encode($rspta);
		break;

	case 'listar':
		$rspta=$tipoProf->listar();
		//Declaramos un array para mostrar todos los registros que se estan obteniendo
		$data=Array();
		while($reg=$rspta->fetch_object()){
			$data[]=Array(
					"0"=>'<button class="btn btn-xs btn-primary" onclick="mostrar('."'$reg->idWsDat01'".')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-xs btn-danger" onclick="eliminar('."'$reg->idWsDat01'".')"><i class="fa fa-close"></i></button>',
					"1"=>$reg->descripcion
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
		$rspta=$tipoProf->eliminar($idwsdat01);
		echo $rspta ? "Tipo de Profesional eliminado." : "Tipo de Profesional no se pudo eliminar."; 
		break;

}
?>