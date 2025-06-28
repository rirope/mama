<?php
session_start();
require_once "../modelos/AdmPeticionAcceso.php";

$regAdm=new AdmUsuOperador();

switch ($_GET["op"])
{
	case 'desactivar':
		$idDet=$_POST['idDet'];
		$rspta=$regAdm->desactivarDet($idDet);
 		echo $rspta ? "Registro del Usuario Desactivado" : "Registro del Usuario no se puede Desactivar";
		break;

	case 'activar':
		$idDet=$_POST['idDet'];
		$rspta=$regAdm->activar($idDet);
 		echo $rspta ? "Registro del Usuario Activado" : "Registro del Usuario no se puede Activar";
		break;

	case 'listar':
		$iddisa=$_POST["iddisa"];
		$idred=$_POST["idred"];
		$idmred=$_POST["idmred"];
		$idestab=$_POST["idestab"];

		$rspta=$regAdm->listar($iddisa,$idred,$idmred,$idestab);
		//Declaramos un array para mostrar todos los registros que se estan obteniendo
		$data=Array();
		while($reg=$rspta->fetch_object()){
			$data[]=Array(
				"0"=>'<button class="btn btn-xs btn-primary" onclick="activar('.$reg->idPers.')"><i class="fa fa-check"></i></button>',
				"1"=>$reg->fechaSolicitud,
				"2"=>$reg->apeNom,
				"3"=>$reg->profesion,
				"4"=>$reg->nroDni,
				"5"=>$reg->email,
				"6"=>$reg->eessSolicitado
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