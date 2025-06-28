<?php
session_start();
require_once "../modelos/RegistroGestantes.php";

$regGest=new RegistroGestantes();

$idGest=isset($_POST["idGest"]) ? limpiarCadena($_POST["idGest"]):"";
$tipoDocIdent=isset($_POST["tipoDocIdent"]) ? limpiarCadena($_POST["tipoDocIdent"]):"";
$nroDocIdent=isset($_POST["nroDocIdent"]) ? limpiarCadena($_POST["nroDocIdent"]):"";
$apePat=isset($_POST["apePat"]) ? limpiarCadena($_POST["apePat"]):"";
$apeMat=isset($_POST["apeMat"]) ? limpiarCadena($_POST["apeMat"]):"";
$nombres=isset($_POST["nombres"]) ? limpiarCadena($_POST["nombres"]):"";
$fechaNacGest=isset($_POST["fechaNacGest"]) ? limpiarCadena($_POST["fechaNacGest"]):"";
$idGrInst=isset($_POST["idGrInst"]) ? limpiarCadena($_POST["idGrInst"]):"";
$fecProbParto=isset($_POST["fecProbParto"]) ? limpiarCadena($_POST["fecProbParto"]):"";
$fechaAtc=isset($_POST["fechaAtc"]) ? limpiarCadena($_POST["fechaAtc"]):"";
$hClFam=isset($_POST["hClFam"]) ? limpiarCadena($_POST["hClFam"]):"";
$hClGest=isset($_POST["hClGest"]) ? limpiarCadena($_POST["hClGest"]):"";
$celularMadre=isset($_POST["celularMadre"]) ? limpiarCadena($_POST["celularMadre"]):"";
$celularAcomp=isset($_POST["celularAcomp"]) ? limpiarCadena($_POST["celularAcomp"]):"";
$progSoc=isset($_POST["progSoc"]) ? limpiarCadena($_POST["progSoc"]):"";
$msgVoz=isset($_POST["msgVoz"]) ? limpiarCadena($_POST["msgVoz"]):"";
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

		if (empty($idGest)){
		  $rspta=$regGest->insertar($tipoDocIdent,$nroDocIdent,$apePat,$apeMat,$nombres,$fechaNacGest,$idGrInst,$fecProbParto,$fechaAtc,$hClFam,$hClGest,$celularMadre,$celularAcomp,$progSoc,$msgVoz,$idEess,$idUsrApp);
		  //echo $rspta ? "La Nueva Usuaria ha sido Registrada Correctamente." : "No se pudo registrar a la Nueva Usuaria.";
		  echo $rspta;
		} else {
			// Se esta actualizando los datos de una gestante
			$rspta=$regGest->editar($idGest,$apePat,$apeMat,$nombres,$fechaNacGest,$idGrInst,$fecProbParto,$fechaAtc,$hClFam,$hClGest,$celularMadre,$celularAcomp,$progSoc,$msgVoz);
			echo $rspta ? "Los Datos de la Gestante fueron actualizados." : "Los Datos de la Gestante no se pudieron actualizar."; 
		}
		break;

	case 'desactivar':
		$rspta=$regGest->desactivar($idGest);
 		echo $rspta ? "Registro de la Gestante Desactivado" : "Registro de la Gestante no se puede Desactivar";
		break;

	case 'activar':
		$rspta=$regGest->activar($idGest);
 		echo $rspta ? "Registro de la Gestante Activado" : "Registro de la Gestante no se puede Activar";
		break;

	case 'mostrar':
		$rspta=$regGest->mostrar($idGest);
		//Codificar el resultado utilizando json, porque en este caso se recibe un array de registro
		echo json_encode($rspta);
		break;

	case 'buscarGestanteExiste':
		$rspta=$regGest->buscarGestanteExiste($idGest);
		//Codificar el resultado utilizando json, porque en este caso se recibe un array de registro
		echo json_encode($rspta);
		break;

	case 'verGestacionActiva':
	  //Buscamos si hay una gestacion activa y devolvemos 1-Alguna gestacion activa, 0-No hay gestacion activa
		$rspta=$regGest->verGestacionActiva($nroDocIdent);
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

		$rspta=$regGest->listar($fechaIni,$fechaFin,$iddisa,$idred,$idmred,$idestab,$estado);
		//Declaramos un array para mostrar todos los registros que se estan obteniendo
		$data=Array();
		while($reg=$rspta->fetch_object()){
			$url='../reportes/formatogestante.php?idGest='.$reg->id.'&region='.$region;
			$data[]=Array(
				"0"=>($reg->estado=='1')?'<button class="btn btn-xs btn-primary" onclick="mostrar('.$reg->id.')"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-xs btn-warning" onclick="desactivar('.$reg->id.')"><i class="fa fa-close"></i></button>'.' <button class="btn btn-xs btn-danger" onclick="eliminar('.$reg->id.')"><i class="fa fa-trash"></i></button>'.'<a target="_blank" href="'.$url.'"> <button class="btn btn-xs btn-info"><i class="fa fa-print"></i> Imprimir</button></a>':' <button class="btn btn-xs btn-primary" onclick="mostrar('.$reg->id.')"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-xs btn-success" onclick="activar('.$reg->id.')"><i class="fa fa-check"></i></button>'.' <button class="btn btn-xs btn-danger" onclick="eliminar('.$reg->id.')"><i class="fa fa-trash"></i></button>'.'<a target="_blank" href="'.$url.'"> <button class="btn btn-xs btn-info"><i class="fa fa-print"></i> Imprimir</button></a>',
				"1"=>$reg->nomApeGest,
				"2"=>$reg->tipDi.' '.$reg->nroDiGest,
				"3"=>$reg->celularGest,
				"4"=>$reg->edadGest,
				"5"=>$reg->fpp,
				"6"=>$reg->fechaAct,
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
		$rspta=$regGest->eliminar($idGest);
		echo $rspta ? "Registro de la Gestante eliminado." : "Registro de la Gestante no se pudo eliminar."; 
		break;

	case 'listaGestBusq':
	  $tipoDoc=$_POST["tipoDoc"];
		$nroDoc=$_POST["nroDoc"];

		$rspta=$regGest->listaGestBusq($tipoDoc,$nroDoc);
		//Declaramos un array para mostrar todos los registros que se estan obteniendo
		$data=Array();
		while($reg=$rspta->fetch_object()){
			$data[]=Array(
				//"0"=>($reg->estado=='0')?'<button class="btn btn-xs btn-primary" onclick="mostrar('.$reg->id.')"><i class="fa fa-check"></i></button>':'<span class="label bg-green">Activo</span>',
				"0"=>$reg->id,
				"1"=>$reg->estado,
				"2"=>$reg->nomApeGest,
				"3"=>$reg->tipoDi.' '.$reg->nroDi,
				"4"=>$reg->celular,
				"5"=>$reg->fpp,
				"6"=>$reg->descEstab,
				"7"=>$reg->tipoPersona,
				"8"=>$reg->fecha_nacimiento,
				"9"=>$reg->disa
			);
		}
		echo json_encode($data);
		break;

	case 'eliminar':
		$rspta=$regGest->eliminar($idGest);
		echo $rspta ? "Registro de la Gestante eliminado." : "Registro de la Gestante no se pudo eliminar."; 
		break;

	case 'verificarDatosGestante':
		$nroDoc=$_POST['nroDoc'];
		$tipoDoc=$_POST['tipoDoc'];
		$rspta=$regGest->verificarDatosGestante($tipoDoc,$nroDoc);
		echo $rspta;
		break;

	case 'listaImportarGestante':
		$nroDoc=$_POST["nroDoc"];
		$tipoDoc=$_POST['tipoDoc'];
		$rspta=$regGest->listaImportarGestante($tipoDoc,$nroDoc);
		//Declaramos un array para mostrar todos los registros que se estan obteniendo
		$data=Array();
		while($reg=$rspta->fetch_object()){
			$data[]=Array(
				"0"=>$reg->id,
				"1"=>$reg->nomApeGest,
				"2"=>$reg->tipoDiGest.' '.$reg->nroDiGest,
				"3"=>$reg->celular,
				"4"=>$reg->fpp,
				"5"=>$reg->descEstab.'-'.$reg->disa,
				"6"=>$reg->fechaReg,
				"7"=>$reg->estado
			);
		}
		echo json_encode($data);
		break;

	case 'importarGestante':
		$idRegGest=$_POST["idRegGest"];
		$idEeDest=$_POST["idEstablDestino"];
		$rspta=$regGest->importarGestante($idRegGest,$idEeDest,$idUsrApp);
		echo $rspta;
		break;

	case 'existeNroCelular':
		$celularGest=$_POST['celularGest'];
		$rspta=$regGest->existeNroCelular($celularGest);
		echo $rspta;
		break;

}
