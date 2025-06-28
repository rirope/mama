<?php
// Se incluye la conexion a la BD
require "../config/Conexion.php";

class AdmUsuAdmin
{
	//Implementamos el constructor
	public function __construct()
	{

	}

	//Metodo para insertar un registro
	public function insertar($apePat,$apeMat,$nombres,$idProf,$nroDni,$correoElec,$nomUsu,$passUsu)
	{
		$sql="CALL admUsuAdminCU(0,'$apePat','$apeMat','$nombres','$nroDni','$correoElec','$idProf','$nomUsu','$passUsu','N',@lnId)";
		$rspta=ejecutarConsulta($sql);
		if($rspta)
		{
			//Obtenemos el nuvo ID autogenerado
			$obtCod=ejecutarConsulta("SELECT @lnId AS lnNewId");
			$fila = $obtCod->fetch_assoc();
			$lnNewId = $fila['lnNewId'];
			return $lnNewId;
		}else{
			return $rspta;
		}
	}

	//Metodo para validar si dni que se está ingresando ya existe en la BD: 1-Existe, 0-No existe
	public function validarDni($nroDni)
	{
		$sql="CALL admUsuAdminCU(0,'','','','$nroDni','','','','','VA',@lnRspta)";
		$rspta=ejecutarConsulta($sql);
		if($rspta)
		{
			//Obtenemos la respuesta
			$existeDni=ejecutarConsulta("SELECT @lnRspta AS lnRspta");
			$fila = $existeDni->fetch_assoc();
			$lnRspta = $fila['lnRspta'];
			return $lnRspta;
		}else{
			return $rspta;
		}
	}

	//Implementamos el metodo para actualizar los datos de un registro que se ha editado
	public function editar($idUsu,$apePat,$apeMat,$nombres,$idProf,$nroDni,$correoElec,$nomUsu,$passUsu)
	{
		$sql="CALL admUsuAdminCU('$idUsu','$apePat','$apeMat','$nombres','$nroDni','$correoElec','$idProf','$nomUsu','$passUsu','U',@lnId)";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar registros
	public function desactivarDet($idDet)
	{
		$sql="CALL admUsuAdminRD('$idDet','','','','','0','','AD')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	public function activar($idDet)
	{
		$sql="CALL admUsuAdminRD('$idDet','','','','','1','','AD')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un metodo para mostrar los datos de un registro que se quiere editar o modificar
	public function mostrar($id)
	{
		$sql="CALL admUsuAdminRD('$id','','','','','','','M')";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementamos un metodo para eliminar los registros del usuario
	public function eliminarUsuAdmin($id)
	{
		$sql="CALL admUsuAdminRD('$id','','','','','','','EU')";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementamos un metodo para mostrar los datos del item del detalle seleccionado
	public function mostrarDetLugar($idDet)
	{
		$sql="CALL admUsuAdminRD('$idDet','','','','','','','DL')";
		return ejecutarConsultaSimpleFila($sql);
	}


	//Implementamos el metodo para listar los detalles del usuario
	public function listaDetUsu($id)
	{
		$sql="CALL admUsuAdminRD('$id','','','','','','','MD')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para listar los registros
	public function listar($iddisa,$idred,$idmred,$idestab)
	{
		$sql="CALL admUsuAdminRD(0,'$iddisa','$idred','$idmred','$idestab','','','L')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo eliminar un registro
	public function eliminar($idDet)
	{
		$sql="CALL admUsuAdminRD('$idDet','','','','','','','E')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para listar las profesiones 
	public function listaProf()
	{
		$sql="CALL mantWsdat01('04','','','','L',@lcId)";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para listar los niveles de usuario
	public function listaNivel($idNiv)
	{
		$sql="CALL mantWsdat01('06','','$idNiv','','LA',@lcId)";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para registra el nuevo lugar de acceso
	public function nvoLugarAcceso($idNivel,$iddisaUsu,$idredUsu,$idmredUsu,$idUsu)
	{
		$sql="CALL admUsuAdminRD('$idUsu','$iddisaUsu','$idredUsu','$idmredUsu','','','$idNivel','ND')";
		return ejecutarConsulta($sql);
	}

}
?>
