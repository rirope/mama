<?php
session_start();
require_once "../modelos/Reporte06.php";

$rep06=new Reporte06();

switch ($_GET["op"])
{
	case 'listar':
		$fechaIni=$_POST['fechaIni'];
		$fechaFin=$_POST['fechaFin'];
		$iddisa=$_POST["iddisa"];
		$idred=$_POST["idred"];
		$idmred=$_POST["idmred"];
		$idestab=$_POST["idestab"];

		$rspta=$rep06->listar($fechaIni,$fechaFin,$iddisa,$idred,$idmred,$idestab);
		//Declaramos un array para mostrar todos los registros que se estan obteniendo
		$data=Array();
		while($reg=$rspta->fetch_object()){
			$data[]=Array(
				"0"=>$reg->codEstab,
				"1"=>$reg->descEstab,
				"2"=>$reg->nroGest,
				"3"=>$reg->nroNinos
			);
		}
		$results=Array(
				"sEcho"=>1, //Informacion para el datatables
				"iTotalRecords"=>count($data), //Enviamos el total de registros al datatables
				"iTotalDisplayRecords"=>count($data), //Enviamos el total de registros a visualizar
				"aaData"=>$data);
		echo json_encode($results);
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