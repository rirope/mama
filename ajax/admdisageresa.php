<?php
require_once "../modelos/AdmDisaGeresa.php";

$admDg=new AdmDisaGeresa();

switch ($_GET["op"])
{
	case 'listar':
		$rspta=$admDg->listar();
		//Declaramos un array para mostrar todos los registros que se estan obteniendo
		$data=Array();
		while($reg=$rspta->fetch_object()){
			//$url='../vistas/admredes.php?nomDisa=';
			$data[]=Array(
				"0"=>'<button class="btn btn-xs btn-primary" onclick="listarRedes('."'$reg->idDisa'".','."'$reg->descDisa'".')"><i class="fa fa-eye"></i> Redes</button>',
				"1"=>$reg->descDisa
				);
		}
		$results=Array(
				"sEcho"=>1, //Informacion para el datatables
				"iTotalRecords"=>count($data), //Enviamos el total de registros al datatables
				"iTotalDisplayRecords"=>count($data), //Enviamos el total de registros a visualizar
				"aaData"=>$data);
		echo json_encode($results);
		break;

	case 'listaRedes':
		$idDisa=$_POST['idDisa'];
		$rspta=$admDg->listaRedes($idDisa);
		//Declaramos un array para mostrar todos los registros que se estan obteniendo
		$data=Array();
		while($reg=$rspta->fetch_object())
		{
			$data[]=Array(
				"0"=>'<button class="btn btn-xs btn-primary" onclick="listarMicroRedes('."'$reg->idRed'".','."'$reg->descRed'".')"><i class="fa fa-eye"></i> MicroRedes</button>',
				"1"=>$reg->descRed
				);
		}
		$results=Array(
				"sEcho"=>1, //Informacion para el datatables
				"iTotalRecords"=>count($data), //Enviamos el total de registros al datatables
				"iTotalDisplayRecords"=>count($data), //Enviamos el total de registros a visualizar
				"aaData"=>$data);
		echo json_encode($results);
		break;

	case 'listaMicroRedes':
		$idRed=$_POST['idRed'];
		$rspta=$admDg->listaMicroRedes($idRed);
		//Declaramos un array para mostrar todos los registros que se estan obteniendo
		$data=Array();
		while($reg=$rspta->fetch_object())
		{
			$data[]=Array(
				"0"=>'<button class="btn btn-xs btn-primary"><i class="fa fa-home"></i> EeSs</button>',
				"1"=>$reg->descMicRed
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