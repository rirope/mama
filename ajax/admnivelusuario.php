<?php
require_once "../modelos/AdmNivelUsuario.php";

$admNivelUsuario=new AdmNivelUsuario();

$idNivelUsuario=isset($_POST["idNivelUsuario"]) ? limpiarCadena($_POST["idNivelUsuario"]):"";
$nomNivelUsuario=isset($_POST["nomNivelUsuario"]) ? strtoupper(limpiarCadena($_POST["nomNivelUsuario"])):"";
$estado=isset($_POST["estado"]) ? limpiarCadena($_POST["estado"]):"";
$data=isset($_POST["data"]) ? limpiarCadena($_POST["data"]):"";

switch ($_GET["op"])
{
	case 'guardaryeditar':
		if (empty($idNivelUsuario)){
			// Se esta ingresando un nuevo registro
			$rspta=$admNivelUsuario->insertar($nomNivelUsuario,$estado);
			echo $rspta ? "Los Datos del Nivel de Usuario fueron Registrados." : "Los Datos del Nivel de Usuario No se pudieron Registrar.";
		} else {
			// Se esta actualizando los datos de un establecimiento
			$rspta=$admNivelUsuario->editar($idNivelUsuario,$nomNivelUsuario,$estado);
			echo $rspta ? "Los Datos del Nivel de Usuario fueron Actualizados." : "Los Datos del Nivel de Usuario No se pudieron Actualizar."; 
		}
		break;

	case 'desactivar':
		$rspta=$admNivelUsuario->desactivar($idNivelUsuario);
 		echo $rspta ? "El Nivel de usuario fue Desactivado" : "El Nivel de Usuario NO se puede Desactivar";
		break;

	case 'activar':
		$rspta=$admNivelUsuario->activar($idNivelUsuario);
 		echo $rspta ? "El Nivel de Usuario fue Activado" : "El Nivel de Usuario NO se puede Activar";
		break;
	
	case 'mostrar':
		$rspta=$admNivelUsuario->mostrar($idNivelUsuario);
		//Codificar el resultado utilizando json, porque en este caso se recibe un array de registro
		echo json_encode($rspta);
		break;

	case 'listar':
	    $estado=$_REQUEST["estado"];
		$rspta=$admNivelUsuario->listar($estado);
		//Declaramos un array para mostrar todos los registros que se estan obteniendo
		$data=Array();
		while($reg=$rspta->fetch_object()){
			$data[]=Array(
					"0"=>($reg->activo == "S")?'<button class="btn btn-xs btn-primary" onclick="mostrar('."'$reg->idNiv'".')"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-xs btn-warning" onclick="desactivar('."'$reg->idNiv'".')"><i class="fa fa-close"></i></button>'.' <button class="btn btn-xs btn-danger" onclick="eliminar('."'$reg->idNiv'".')"><i class="fa fa-trash"></i></button>':' <button class="btn btn-xs btn-success" onclick="activar('."'$reg->idNiv'".')"><i class="fa fa-check"></i></button>',
					"1"=>$reg->descripcion,
					"2"=>($reg->activo == "S")?'<span class="label bg-green">Activo</span>':'<span class="label bg-red">Inactivo</span>',
					"3"=>' <button class="btn btn-xs btn-warning" onclick="opciones('."'$reg->idNiv'".')"><i class="fa fa-cog"></i></button>',
				);
		}
		$results=Array(
				"sEcho"=>1, //Informacion para el datatables
				"iTotalRecords"=>count($data), //Enviamos el total de registros al datatables
				"iTotalDisplayRecords"=>count($data), //Enviamos el total de registros a visualizar
				"aaData"=>$data);
		echo json_encode($results);
		break;

	case 'opciones': 
		$rspta=$admNivelUsuario->listar_opciones($idNivelUsuario);
		$tabla = array();
		$tabla[1]= '';
		if ($rspta != null)
			for ($i = 0; $i < count($rspta); $i++) {
				//$aaaa = $rspta[$i];
			$rspta[$i][0]==1?$r="checked":$r="";
			if ($i == 0) {
				$tabla[1] .= '<li class="list-group-item node-treeview-checkable" data-nodeid="1" style="color:undefined;background-color:undefined;">'; 
				$tabla[1] .= '<input type="checkbox" '.$r.' name="chkMod'.$rspta[$i][4].'" id="chkMod'.$rspta[$i][4].'" onchange="seleccionarDetalle('.$rspta[$i][4].')" value='.$rspta[$i][4].' >';
				$tabla[1] .= '<strong>' . $rspta[$i][5] . ' </strong></li>';
				$tabla[1] .= '<div id="detalle'. $rspta[$i][4] .'">';
			}
			if ($i > 0 && $rspta[$i][5] != $rspta[$i - 1][5]) {
				$tabla[1] .= '</div>';
				$tabla[1] .= '<li class="list-group-item node-treeview-checkable" data-nodeid="1" style="color:undefined;background-color:undefined;">'; 
				$tabla[1] .= '<input type="checkbox" '.$r.' name="chkMod'.$rspta[$i][4].'" id="chkMod'.$rspta[$i][4].'" onchange="seleccionarDetalle('.$rspta[$i][4].')" value='.$rspta[$i][4].' >';
				$tabla[1] .= '<strong>'. $rspta[$i][5] . ' </strong></li>';
				$tabla[1] .= '<div id="detalle'.$rspta[$i][4] .'">';
			}

			$tabla[1] .= '<li class="list-group-item node-treeview-checkable" data-nodeid="1" style="color:undefined;background-color:undefined;padding: 0px 60px;">'; 
			$tabla[1] .= '<input  type="checkbox" '.$r.' name="chkMod" id="chkOp'.$rspta[$i][4].'-'.$i.'" value='.$rspta[$i][1]."/".$rspta[$i][4].' >';
			$tabla[1] .= $rspta[$i][2] . '</li>';


			}
 
		echo ($tabla[1]);
		break;

	case 'grabaropciones':
		$insertar = "";
		if ($idNivelUsuario == '') {
			$rspta = "No se ha seleccionado un nivel de usuario. Por favor comunicarse con el oficina de soporte";
			echo $rspta;
		} else {
			$datosOpcion= explode(",", $data);
			$rspta=$admNivelUsuario->eliminarOpciones($idNivelUsuario);
			for($i=0;$i<count($datosOpcion)-1;$i++){
				$datosEnviar= explode("/", $datosOpcion[$i]);
				//var_dump("i=".$i."--Datos a enviar".$datosEnviar[0]."--$datosEnviar[1]");
				$insertar = $admNivelUsuario->insertaropcion($datosEnviar[0], $datosEnviar[1], $idNivelUsuario);
				$insertar=true;
			}
		}
		echo $insertar != "" ? "Los permisos fueron modificados" : "No se guardaron los permisos."; 
		break;

	case 'eliminar':
		$rspta=$admNivelUsuario->eliminar($idNivelUsuario);
		echo $rspta ? "El Nivel de Usuario fue Eliminado." : "El Nivel de Usuario no se pudo Eliminar porque YA TIENE Movimiento."; 
		break;
 
}
?>