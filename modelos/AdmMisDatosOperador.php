<?php
// Se incluye la conexion a la BD
require "../config/Conexion.php";

class AdmMisDatosOperador
{
	//Implementamos el constructor
	public function __construct()
	{

	}

	//Metodo para validar si dni que se está ingresando ya existe en la BD: 1-Existe, 0-No existe
	public function validarDni($nroDni)
	{
		$sql="CALL admUsuOperadorCU(0,'','','','$nroDni','','','','','VA',@lnRspta)";
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

	//Metodo para validar si dni que se está ingresando ya existe en la BD: 1-Existe, 0-No existe
	public function buscarDniOperador($nroDni)
	{
		$sql="CALL admUsuAdminCU(0,'','','','$nroDni','','','','','BD',@lnRspta)";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementamos el metodo para actualizar los datos de un registro que se ha editado
	public function editar($idUsu,$apePat,$apeMat,$nombres,$idProf,$nroDni,$correoElec,$nomUsu,$passUsu)
	{
		$sql="CALL admUsuAdminCU('$idUsu','$apePat','$apeMat','$nombres','$nroDni','$correoElec','$idProf','$nomUsu','$passUsu','U',@lnId)";
		return ejecutarConsulta($sql);
	}

	//Implementamos un metodo para mostrar los datos de un registro que se quiere editar o modificar
	public function mostrar($id)
	{
		$sql="CALL admUsuOperadorRD('$id','','','','','','','','M')";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementamos el metodo para listar las profesiones para con combo
	public function listaProf()
	{
		$sql="CALL mantWsdat01('04','','','','LO',@lcId)";
		return ejecutarConsulta($sql);
	}

}
?>
