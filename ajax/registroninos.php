<?php
session_start();
require_once "../modelos/RegistroNinos.php";

$regNinos=new RegistroNinos();
//Datos de la mamá del niño
$idNino=isset($_POST["idNino"]) ? limpiarCadena($_POST["idNino"]):"";
$tipoDocIdent=isset($_POST["tipoDocIdent"]) ? limpiarCadena($_POST["tipoDocIdent"]):"";
$nroDocIdent=isset($_POST["nroDocIdent"]) ? limpiarCadena($_POST["nroDocIdent"]):"";
$apePat=isset($_POST["apePat"]) ? limpiarCadena($_POST["apePat"]):"";
$apeMat=isset($_POST["apeMat"]) ? limpiarCadena($_POST["apeMat"]):"";
$nombres=isset($_POST["nombres"]) ? limpiarCadena($_POST["nombres"]):"";
$fechaNacMama=isset($_POST["fechaNacMama"]) ? limpiarCadena($_POST["fechaNacMama"]):"";
$idGrInst=isset($_POST["idGrInst"]) ? limpiarCadena($_POST["idGrInst"]):"";
$celularMadre=isset($_POST["celularMadre"]) ? limpiarCadena($_POST["celularMadre"]):"";
$celularAcomp=isset($_POST["celularAcomp"]) ? limpiarCadena($_POST["celularAcomp"]):"";
$progSoc=isset($_POST["progSoc"]) ? limpiarCadena($_POST["progSoc"]):"";
$msgVoz=isset($_POST["msgVoz"]) ? limpiarCadena($_POST["msgVoz"]):"";
//Datos del niño
$tipoDiNino=isset($_POST["tipoDiNino"]) ? limpiarCadena($_POST["tipoDiNino"]):"";
$nroDiNino=isset($_POST["nroDiNino"]) ? limpiarCadena($_POST["nroDiNino"]):"";
$hClFam=isset($_POST["hClFam"]) ? limpiarCadena($_POST["hClFam"]):"";
$hClNino=isset($_POST["hClNino"]) ? limpiarCadena($_POST["hClNino"]):"";
$fechaNacNino=isset($_POST["fechaNacNino"]) ? limpiarCadena($_POST["fechaNacNino"]):"";
$fechaAtcNino=isset($_POST["fechaAtcNino"]) ? limpiarCadena($_POST["fechaAtcNino"]):"";
$progSocNino=isset($_POST["progSocNino"]) ? limpiarCadena($_POST["progSocNino"]):"";

$idUsrApp=$_SESSION['idUsrApp'];
$idEstabl=$_SESSION['idEstabl'];

switch ($_GET["op"])
{
	case 'guardaryeditar':
		// Capturamos el id del eess
		$idestab=$_POST['idestab'];
		if($idEstabl==0)
		{
			$idEess=$idestab;
		}else{
			$idEess=$idEstabl;
		}

		if (empty($idNino)){
		  $rspta=$regNinos->insertar($tipoDocIdent,$nroDocIdent,$apePat,$apeMat,$nombres,$fechaNacMama,$idGrInst,$celularMadre,$celularAcomp,$progSoc,$msgVoz,$tipoDiNino,$nroDiNino,$hClNino,$hClFam,$fechaNacNino,$fechaAtcNino,$progSocNino,$idEess,$idUsrApp);
		  //echo $rspta ? "Los Datos del Niño han sido Registrados Correctamente." : "No se pudo registrar los Datos del Niño.";
		  echo $rspta;
		} else {
			// Se esta actualizando los datos del registro del niño
			$rspta=$regNinos->editar($idNino,$idGrInst,$celularMadre,$celularAcomp,$progSoc,$msgVoz,$tipoDiNino,$nroDiNino,$hClNino,$hClFam,$fechaNacNino,$fechaAtcNino,$progSocNino);
			echo $rspta ? "Los Datos del Niño fueron actualizados." : "Los Datos del Niño no se pudieron actualizar."; 
		}
		break;

	case 'desactivar':
		$rspta=$regNinos->desactivar($idNino);
 		echo $rspta ? "Registro de la Gestante Desactivado" : "Registro de la Gestante no se puede Desactivar";
		break;

	case 'activar':
		$rspta=$regNinos->activar($idNino);
 		echo $rspta ? "Registro de la Gestante Activado" : "Registro de la Gestante no se puede Activar";
		break;

	case 'mostrar':
		$rspta=$regNinos->mostrar($idNino);
		//Codificar el resultado utilizando json, porque en este caso se recibe un array de registro
		echo json_encode($rspta);
		break;

	case 'buscarMamaExiste':
		$rspta=$regNinos->buscarMamaExiste($nroDocIdent);
		//Codificar el resultado utilizando json, porque en este caso se recibe un array de registro
		echo json_encode($rspta);
		break;

	case 'listar':
	  $fechaIni=$_POST["fechaIni"];
		$fechaFin=$_POST["fechaFin"];
		$iddisa=$_POST["iddisa"];
		$idred=$_POST["idred"];
		$idmred=$_POST["idmred"];
		$idestab=$_POST["idestab"];
		$estado=$_POST["estado"];
		$region=$_POST["region"];

		$rspta=$regNinos->listar($fechaIni,$fechaFin,$iddisa,$idred,$idmred,$idestab,$estado);
		//Declaramos un array para mostrar todos los registros que se estan obteniendo
		$data=Array();
		while($reg=$rspta->fetch_object()){
			$url='../reportes/formatonino.php?idNino='.$reg->id.'&region='.$region;
			$data[]=Array(
				"0"=>($reg->estado=='1')?'<button class="btn btn-xs btn-primary" onclick="mostrar('.$reg->id.')"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-xs btn-warning" onclick="desactivar('.$reg->id.')"><i class="fa fa-close"></i></button>'.' <button class="btn btn-xs btn-danger" onclick="eliminar('.$reg->id.')"><i class="fa fa-trash"></i></button>'.'<a target="_blank" href="'.$url.'"> <button class="btn btn-xs btn-info"><i class="fa fa-print"></i> Imprimir</button></a>':' <button class="btn btn-xs btn-primary" onclick="mostrar('.$reg->id.')"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-xs btn-success" onclick="activar('.$reg->id.')"><i class="fa fa-check"></i></button>'.' <button class="btn btn-xs btn-danger" onclick="eliminar('.$reg->id.')"><i class="fa fa-trash"></i></button>',
				"1"=>$reg->nomApeMama,
				"2"=>$reg->tipDiMama.' '.$reg->nroDiMama,
				"3"=>$reg->celularMama,
				"4"=>$reg->tipoDiNino.' '.$reg->nroDiNino,
				"5"=>$reg->fecNacNino,
				"6"=>$reg->fechaReg,
				"7"=>$reg->geresa,
				"8"=>$reg->descEstab,
				"9"=>$reg->codEstab,
				"10"=>$reg->usuReg,
				"11"=>($reg->estado=='1')?'<span class="label bg-green">Activo</span>':'<span class="label bg-red">Inactivo</span>'
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

	// Esta opcion es para cargar el control select con la lista de grados de instrucción
	case 'listaGrInst':
		require_once "../modelos/GradoInstruccion.php";
		$admGi = new GradoInstruccion();
		$rspta = $admGi->listaGrInst();
		while ($reg = $rspta->fetch_object())
		{
			echo '<option value='. "'$reg->idWsDat01'" . '>' . $reg->descripcion . '</option>';
		}
		break;

	// Esta opcion es para cargar el control select con la lista de Tipos de Documentos de Identidad
	case 'listaDocIdent':
		require_once "../modelos/TipoDocIdent.php";
		$admTdi = new TipoDocIdent();
		$rspta = $admTdi->listar();
		while ($reg = $rspta->fetch_object())
		{
			echo '<option value='. "'$reg->idWsDat01'" . '>' . $reg->descripcion . '</option>';
		}
		echo '<option value="00000">-No Tiene-</option>';
		break;

	// Esta opcion es para cargar el control select con la lista de Programas sociales
	case 'listaProgSoc':
		require_once "../modelos/ProgramaSociales.php";
		$admPs = new ProgramaSociales();
		$rspta = $admPs->listar();
		echo '<option value="00000">-No Tiene-</option>';
		while ($reg = $rspta->fetch_object())
		{
			echo '<option value='. "'$reg->idWsDat01'" . '>' . $reg->descripcion . '</option>';
		}
		break;

	// Esta opcion es para cargar el control select con la lista de idiomas para el mensaje de voz
	case 'listaIdiomaMv':
		require_once "../modelos/Idiomas.php";
		$admId = new Idiomas();
		$rspta = $admId->listar();
		echo '<option value="00000">-No Recibe-</option>';
		while ($reg = $rspta->fetch_object())
		{
			echo '<option value='. "'$reg->idWsDat01'" . '>' . $reg->descripcion . '</option>';
		}
		break;

	case 'eliminar':
		$rspta=$regNinos->eliminar($idNino);
		echo $rspta ? "Registro del Niño eliminado." : "Registro del Niño no se pudo eliminar."; 
		break;

	case 'listaMamaBusq':
	  $tipoDoc=$_POST["tipoDoc"];
		$nroDoc=$_POST["nroDoc"];

		$rspta=$regNinos->listaMamaBusq($tipoDoc,$nroDoc);
		//Declaramos un array para mostrar todos los registros que se estan obteniendo
		$data=Array();
		while($reg=$rspta->fetch_object()){
			$data[]=Array(
				"0"=>$reg->id,
				"1"=>$reg->estado,
				"2"=>$reg->nomApeMama,
				"3"=>$reg->tipoDiMa.' '.$reg->nroDiMa,
				"4"=>$reg->celular,
				"5"=>$reg->fecNacMa,
				"6"=>$reg->descEstab
			);
		}
		echo json_encode($data);
		break;

	case 'verificarDatosMamaImportarNino':
		$nroDoc=$_POST['nroDoc'];
		$rspta=$regNinos->verificarDatosMamaImportarNino($nroDoc);
		echo $rspta;
		break;

	case 'listaImportarNino':
		$nroDoc=$_POST["nroDoc"];

		$rspta=$regNinos->listaImportarNino($nroDoc);
		//Declaramos un array para mostrar todos los registros que se estan obteniendo
		$data=Array();
		while($reg=$rspta->fetch_object()){
			$data[]=Array(
				"0"=>$reg->id,
				"1"=>$reg->nomApeMama,
				"2"=>$reg->tipoDiMa.' '.$reg->nroDiMa,
				"3"=>$reg->celular,
				"4"=>$reg->fecNacNi,
				"5"=>$reg->nroDiNino,
				"6"=>$reg->fechaReg,
				"7"=>$reg->nroDiasReg,
				"8"=>$reg->descEstab.'-'.$reg->disa
			);
		}
		echo json_encode($data);
		break;

	case 'importarNino':
		$idRegMa=$_POST["idRegMa"];
		$idEeDest=$_POST["idEstablDestino"];
		$rspta=$regNinos->importarNino($idRegMa,$idEeDest,$idUsrApp);
		echo $rspta;
		break;

	case 'existedninino':
		$rspta=$regNinos->existedninino($nroDiNino);
		echo ($rspta);
		break;

}
?>