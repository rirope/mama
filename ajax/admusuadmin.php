<?php
session_start();
require_once "../modelos/AdmUsuAdmin.php";

$regAdm=new AdmUsuAdmin();
//Datos del usuario
$idUsu=isset($_POST["idUsu"]) ? limpiarCadena($_POST["idUsu"]):"";
$apePat=isset($_POST["apePat"]) ? limpiarCadena($_POST["apePat"]):"";
$apeMat=isset($_POST["apeMat"]) ? limpiarCadena($_POST["apeMat"]):"";
$nombres=isset($_POST["nombres"]) ? limpiarCadena($_POST["nombres"]):"";
$idProf=isset($_POST["idProf"]) ? limpiarCadena($_POST["idProf"]):"";
$nroDni=isset($_POST["nroDni"]) ? limpiarCadena($_POST["nroDni"]):"";
$correoElec=isset($_POST["correoElec"]) ? limpiarCadena($_POST["correoElec"]):"";
$nomUsu=isset($_POST["nomUsu"]) ? limpiarCadena($_POST["nomUsu"]):"";
$passUsu=isset($_POST["passUsu"]) ? limpiarCadena($_POST["passUsu"]):"";

switch ($_GET["op"])
{
	case 'guardaryeditar':
		if (empty($idUsu)){
		  $rspta=$regAdm->insertar($apePat,$apeMat,$nombres,$idProf,$nroDni,$correoElec,$nomUsu,$passUsu);
		  echo $rspta;
		} else {
			// Se esta actualizando los datos del usuario
			$rspta=$regAdm->editar($idUsu,$apePat,$apeMat,$nombres,$idProf,$nroDni,$correoElec,$nomUsu,$passUsu);
			echo $rspta ? "Los Datos del Usuario fueron actualizados." : "Los Datos del Usuario no se pudieron actualizar."; 
		}
		break;

	case 'validarDni':
		$rspta=$regAdm->validarDni($nroDni);
 		echo $rspta;
		break;

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

	case 'mostrar':
		$rspta=$regAdm->mostrar($idUsu);
		//Codificar el resultado utilizando json, porque en este caso se recibe un array de registro
		echo json_encode($rspta);
		break;

	case 'eliminarUsuAdmin':
		$rspta=$regAdm->eliminarUsuAdmin($idUsu);
		//Codificar el resultado utilizando json, porque en este caso se recibe un array de registro
		echo json_encode($rspta);
		break;

	case 'mostrarDetLugar':
	  $idDet=$_POST['idDet'];
		$rspta=$regAdm->mostrarDetLugar($idDet);
		//Codificar el resultado utilizando json, porque en este caso se recibe un array de registro
		echo json_encode($rspta);
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
				"0"=>'<button class="btn btn-xs btn-primary" onclick="mostrar('.$reg->idPers.')"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-xs btn-danger" onclick="eliminarUsuAdmin('.$reg->idPers.')"><i class="fa fa-trash"></i></button>',
				"1"=>$reg->apeNom,
				"2"=>$reg->profesion,
				"3"=>$reg->nroDni,
				"4"=>$reg->email,
				"5"=>$reg->origenUsuario,
				"6"=>'<span class="label bg-blue">'.$reg->nroAccesos.'</span>',
				"7"=>'<span class="label bg-green">'.$reg->accesosActivos.'</span>'
			);
		}
		$results=Array(
				"sEcho"=>1, //Informacion para el datatables
				"iTotalRecords"=>count($data), //Enviamos el total de registros al datatables
				"iTotalDisplayRecords"=>count($data), //Enviamos el total de registros a visualizar
				"aaData"=>$data);
		echo json_encode($results);
		break;

	case 'listaDetUsu':
		$rspta=$regAdm->listaDetUsu($idUsu);
		//Declaramos un array para mostrar todos los registros que se estan obteniendo
		$data=Array();
		while($reg=$rspta->fetch_object()){
			$data[]=Array(
				"0"=>($reg->estado=='1')?'<button type="button" class="btn btn-xs btn-primary" onclick="mostrarDetLugar('.$reg->idDet.','."'E'".')"><i class="fa fa-pencil"></i></button>'.' <button type="button" class="btn btn-xs btn-warning" onclick="desactivarDet('.$reg->idDet.')"><i class="fa fa-close"></i></button>'.' <button type="button" class="btn btn-xs btn-danger" onclick="eliminarDet('.$reg->idDet.')"><i class="fa fa-trash"></i></button>':' <button type="button" class="btn btn-xs btn-success" onclick="activarDet('.$reg->idDet.')"><i class="fa fa-check"></i></button>',
				"1"=>$reg->origenUsuario,
				"2"=>$reg->rol,
				"3"=>$reg->fechaIni,
				"4"=>$reg->updated_at,
				"5"=>($reg->estado=='1')?'<span class="label bg-green">Activo</span>':'<span class="label bg-red">Inactivo</span>'
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
	case 'listaProf':
		$rspta = $regAdm->listaProf();
		while ($reg = $rspta->fetch_object())
		{
			echo '<option value='. "'$reg->idWsDat01'" . '>' . $reg->descripcion . '</option>';
		}
		break;

	// Esta opcion es para cargar el control select con la lista de niveles de acceso
	case 'listaNivel':
		$idNiv=$_POST['idNiv'];
		$rspta = $regAdm->listaNivel($idNiv);
		echo '<option value="">-Seleccionar Nivel-</option>';
		while ($reg = $rspta->fetch_object())
		{
			echo '<option value='. "'$reg->idWsDat01'" . '>' . $reg->descripcion . '</option>';
		}
		break;

	case 'eliminar':
		$idDet=$_POST['idDet'];
		$rspta=$regAdm->eliminar($idDet);
		echo $rspta ? "Registro del Usuario eliminado." : "Registro del Usuario no se pudo eliminar."; 
		break;

	case 'nvoLugarAcceso':
		$idNivel=$_POST['idNivel'];
		$iddisaUsu=$_POST['iddisaUsu'];
		$idredUsu=$_POST['idredUsu'];
		$idmredUsu=$_POST['idmredUsu'];

		$rspta=$regAdm->nvoLugarAcceso($idNivel,$iddisaUsu,$idredUsu,$idmredUsu,$idUsu);
		echo $rspta ? "El Nuevo Acceso fue Creado." : "El Nuevo Acceso No se pudo Crear."; 
		break;

}
?>