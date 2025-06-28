<?php
 
require_once "../modelos/SeleccLugarAcceso.php";

$admLa=new SeleccLugarAcceso();

$idUsr=isset($_POST["idUsr"]) ? limpiarCadena($_POST["idUsr"]):"";

switch ($_GET["op"])
{
	case 'listarLugares':
		$rspta=$admLa->listar($idUsr);
		//Declaramos un array para mostrar todos los registros que se estan obteniendo
		$data=Array();
		while($reg=$rspta->fetch_object()){
			$url='../vistas/principal.php?idEstabl=';
			$data[]=Array(
				"0"=>'<a target="" href="'.$url.$reg->idEstabl.'&idNiv='.$reg->idNivel.'&origenUsuario='.$reg->origenUsuario.'&idRol='.$reg->idRol.'&idDisa='.$reg->idDisa.'&idRed='.$reg->idRed.'&idMred='.$reg->idMred.'&nivelUsuario='.$reg->nivelUsuario.'&rolUsuario='.$reg->rolUsuario.'"> <button class="btn btn-xs btn-success"><i class="fa fa-check"></i> Ingresar</button></a>',
				"1"=>$reg->origenUsuario,
				"2"=>$reg->rolUsuario
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