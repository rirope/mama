<?php
session_start();
require_once "../modelos/AdmEstablecimientos.php";

$regEstab=new AdmEstablecimientos();
//Datos del usuario
$idEstabl=isset($_POST["idEstabl"]) ? limpiarCadena($_POST["idEstabl"]):"";
$codEstab=isset($_POST["codEstab"]) ? limpiarCadena($_POST["codEstab"]):"";
$descEstab=isset($_POST["descEstab"]) ? limpiarCadena($_POST["descEstab"]):"";
$idTipEstab=isset($_POST["idTipEstab"]) ? limpiarCadena($_POST["idTipEstab"]):"";
$idCateg=isset($_POST["idCateg"]) ? limpiarCadena($_POST["idCateg"]):"";
$iddisaUsu=isset($_POST["iddisaUsu"]) ? limpiarCadena($_POST["iddisaUsu"]):"";
$idredUsu=isset($_POST["idredUsu"]) ? limpiarCadena($_POST["idredUsu"]):"";
$idmredUsu=isset($_POST["idmredUsu"]) ? limpiarCadena($_POST["idmredUsu"]):"";
$iddpto=isset($_POST["iddpto"]) ? limpiarCadena($_POST["iddpto"]):"";
$idprov=isset($_POST["idprov"]) ? limpiarCadena($_POST["idprov"]):"";
$iddist=isset($_POST["iddist"]) ? limpiarCadena($_POST["iddist"]):"";

switch ($_GET["op"])
{
	case 'guardaryeditar':
		if (empty($idEstabl)){
			//Se esta registrando un nuevo establecimiento
		  $rspta=$regEstab->insertar($codEstab,$descEstab,$idTipEstab,$idCateg,$iddisaUsu,$idredUsu,$idmredUsu,$iddpto,$idprov,$iddist);
			echo $rspta ? "Los Datos del Nuevo Establecimiento fueron Registrados." : "Los Datos del Nuevo Establecimiento No se pudieron Registrar.";
		} else {
			// Se esta actualizando los datos del establecimiento
			$rspta=$regEstab->editar($idEstabl,$codEstab,$descEstab,$idTipEstab,$idCateg,$iddisaUsu,$idredUsu,$idmredUsu,$iddpto,$idprov,$iddist);
			echo $rspta ? "Los Datos del Establecimiento fueron Actualizados." : "Los Datos del Establecimiento No se pudieron Actualizar.";
		}
		break;

	case 'desactivar':
		$rspta=$regEstab->desactivar($idEstabl);
 		echo $rspta ? "Registro del Establecimiento Desactivado" : "Registro del Establecimiento no se puede Desactivar";
		break;

	case 'activar':
		$rspta=$regEstab->activar($idEstabl);
 		echo $rspta ? "Registro del Establecimiento Activado" : "Registro del Establecimiento no se puede Activar";
		break;

	case 'eliminar':
		$rspta=$regEstab->eliminar($idEstabl);
		echo $rspta ? "Registro del Establecimiento eliminado." : "Registro del Establecimiento no se pudo eliminar."; 
		break;

	case 'mostrar':
		$rspta=$regEstab->mostrar($idEstabl);
		//Codificar el resultado utilizando json, porque en este caso se recibe un array de registro
		echo json_encode($rspta);
		break;

	case 'listar':
		$iddisa=$_POST["iddisa"];
		$idred=$_POST["idred"];
		$idmred=$_POST["idmred"];
		$activo=$_POST['activo'];

		$rspta=$regEstab->listar($iddisa,$idred,$idmred,$activo);
		//Declaramos un array para mostrar todos los registros que se estan obteniendo
		$data=Array();
		while($reg=$rspta->fetch_object()){
			$data[]=Array(
				"0"=>($reg->activo=='S')?'<button type="button" class="btn btn-xs btn-primary" onclick="mostrar('."'$reg->idEstabl'".')"><i class="fa fa-pencil"></i></button>'.' <button type="button" class="btn btn-xs btn-warning" onclick="desactivar('."'$reg->idEstabl'".')"><i class="fa fa-close"></i></button>'.' <button type="button" class="btn btn-xs btn-danger" onclick="eliminar('."'$reg->idEstabl'".')"><i class="fa fa-trash"></i></button>':' <button type="button" class="btn btn-xs btn-success" onclick="activar('."'$reg->idEstabl'".')"><i class="fa fa-check"></i></button>',
				"1"=>$reg->codEstab,
				"2"=>$reg->descEstab,
				"3"=>$reg->tipoEstab,
				"4"=>$reg->categEstab,
				"5"=>$reg->fechaAct,
				"6"=>($reg->activo=='S')?'<span class="label bg-green">Activo</span>':'<span class="label bg-red">Inactivo</span>'
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

	// Esta opcion es para cargar el control select con la lista de tipos de establecimiento
	case 'listaTipoEstab':
		$rspta = $regEstab->listaTipoEstab();
		echo '<option value="">-Seleccione Tipo-</option>';
		while ($reg = $rspta->fetch_object())
		{
			echo '<option value='. "'$reg->idWsDat01'" . '>' . $reg->descripcion . '</option>';
		}
		break;

	// Esta opcion es para cargar el control select con la lista de categorias de establecimiento
	case 'listaCategEstab':
		$rspta = $regEstab->listaCategEstab();
		echo '<option value="">-Seleccione Categoria-</option>';
		while ($reg = $rspta->fetch_object())
		{
			echo '<option value='. "'$reg->idWsDat01'" . '>' . $reg->descripcion . '</option>';
		}
		break;

	// Esta opcion es para cargar el control select con la lista de departamentos
	case 'listaDptos':
		require_once "../modelos/AdmOrgGeo.php";
		$admGeo = new AdmOrgGeo();
		$rspta = $admGeo->listarDptos();
		echo '<option value="00">-Seleccionar Departamento-</option>';
		while ($reg = $rspta->fetch_object())
		{
			echo '<option value=' . "'$reg->idDpto'" . '>' . $reg->descDpto . '</option>';
		}
		break;

	// Esta opcion es para cargar el control select con la lista de provincias
	case 'listaProv':
		require_once "../modelos/AdmOrgGeo.php";
		$admGeo = new AdmOrgGeo();
		$idDpto=$_POST['idDpto'];
		$rspta = $admGeo->listarProv($idDpto);
		echo '<option value="0000">-Seleccionar Provincia-</option>';
		while ($reg = $rspta->fetch_object())
		{
			echo '<option value=' . "'$reg->idProv'" . '>' . $reg->descProv . '</option>';
		}
		break;

	// Esta opcion es para cargar el control select con la lista de distritos
	case 'listaDist':
		require_once "../modelos/AdmOrgGeo.php";
		$admGeo = new AdmOrgGeo();
		$idProv=$_POST['idProv'];
		$rspta = $admGeo->listarDist($idProv);
		echo '<option value="000000">-Seleccionar Distrito-</option>';
		while ($reg = $rspta->fetch_object())
		{
			echo '<option value=' . "'$reg->idDist'" . '>' . $reg->descDist . '</option>';
		}
		break;

}
?>