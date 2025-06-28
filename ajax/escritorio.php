<?php
session_start();
require_once "../modelos/Escritorio.php";

$regEsc=new Escritorio();
$iddisa=$_POST["iddisa"];
$idred=$_POST["idred"];
$idmred=$_POST["idmred"];
$idestab=$_POST["idestab"];

switch ($_GET["op"])
{
	case 'obtenerTotalUsuariosMama':
		$fechaIni=$_POST["fechaIni"];
		$fechaFin=$_POST["fechaFin"];

		$rspta=$regEsc->obtenerTotalUsuariosMama($fechaIni,$fechaFin,$iddisa,$idred,$idmred,$idestab);
		$objeto=$rspta->fetch_object();
		$valor=$objeto->totRegUsuMama;
		echo $valor;
		break;

	case 'obtenerTotalNinos':
		$fechaIni=$_POST["fechaIni"];
		$fechaFin=$_POST["fechaFin"];

		$rspta=$regEsc->obtenerTotalNinos($fechaIni,$fechaFin,$iddisa,$idred,$idmred,$idestab);
		$objeto=$rspta->fetch_object();
		$valor=$objeto->totNinos;
		echo $valor;
		break;

	case 'obtenerTotalGest':
		$fechaIni=$_POST["fechaIni"];
		$fechaFin=$_POST["fechaFin"];

		$rspta=$regEsc->obtenerTotalGest($fechaIni,$fechaFin,$iddisa,$idred,$idmred,$idestab);
		$objeto=$rspta->fetch_object();
		$valor=$objeto->totGest;
		echo $valor;
		break;

	case 'obtenerNinosMen30Dias':
		$rspta=$regEsc->obtenerNinosMen30Dias($iddisa,$idred,$idmred,$idestab);
		$objeto=$rspta->fetch_object();
		$valor=$objeto->numNinos;
		echo $valor;
		break;

	case 'obtenerGestFpp30Dias':
		$rspta=$regEsc->obtenerGestFpp30Dias($iddisa,$idred,$idmred,$idestab);
		$objeto=$rspta->fetch_object();
		$valor=$objeto->numGestFpp;
		echo $valor;
		break;

	case 'obtenerNinos2mVRNP':
		$rspta=$regEsc->obtenerNinos2mVRNP($iddisa,$idred,$idmred,$idestab);
		$objeto=$rspta->fetch_object();
		$valor=$objeto->ninos2m;
		echo $valor;
		break;

	case 'obtenerNinos31y60':
		$rspta=$regEsc->obtenerNinos31y60($iddisa,$idred,$idmred,$idestab);
		$objeto=$rspta->fetch_object();
		$valor=$objeto->ninos31y60;
		echo $valor;
		break;

	case 'obtenerNinos61y90':
		$rspta=$regEsc->obtenerNinos61y90($iddisa,$idred,$idmred,$idestab);
		$objeto=$rspta->fetch_object();
		$valor=$objeto->ninos61y90;
		echo $valor;
		break;

	case 'obtenerNinos110y130':
		$rspta=$regEsc->obtenerNinos110y130($iddisa,$idred,$idmred,$idestab);
		$objeto=$rspta->fetch_object();
		$valor=$objeto->ninos110y130;
		echo $valor;
		break;

	case 'generaGrafBarras':
		$rspta=$regEsc->generaGrafBarras($iddisa,$idred,$idmred,$idestab);
		$data=Array();
		while($reg=$rspta->fetch_object())
		{
			$data[]=Array(
				"annio"=>$reg->annio,
				"benefN"=>$reg->benefN,
				"benefG"=>$reg->benefG
			);
		}
		echo json_encode($data);
		break;

	case 'generaGrafLineas':
		$fechaIni=$_POST["fechaIni"];
		$rspta=$regEsc->generaGrafLineas($fechaIni,$iddisa,$idred,$idmred,$idestab);
		$data=Array();
		while($reg=$rspta->fetch_object())
		{
			$data[]=Array(
				"mes"=>$reg->mes,
				"tipoPersona"=>$reg->tipoPersona,
				"numBenef"=>$reg->numBenef
			);
		}
		echo json_encode($data);
		break;

}
?>