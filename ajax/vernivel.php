<?php
require_once "../modelos/VerNivel.php";

$rolUsu=new RolUsuario();

$descripcion=isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]):"";

switch ($_GET["op"])
{
	case 'listar':
		$rspta=$rolUsu->listar();
		//Declaramos un array para mostrar todos los registros que se estan obteniendo
		$data=Array();
		while($reg=$rspta->fetch_object()){
			$data[]=Array(
					"0"=>$reg->descripcion
				);
		}
		$results=Array(
				"sEcho"=>1, //Informacion para el datatables
				"iTotalRecords"=>count($data), //Enviamos el total de registros al datatables
				"iTotalDisplayRecords"=>count($data), //Enviamos el total de registros a visualizar
				"aaData"=>$data);
		echo json_encode($results);
		break;

}
?>