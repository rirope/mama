<?php
// Se incluye la conexion a la BD
require "../config/Conexion.php";

class AdmUsuOperador
{
	//Implementamos el constructor
	public function __construct()
	{

	}

	//Implementamos un método para desactivar registros
	public function desactivar($idDet)
	{
		$sql="CALL admUsuOperadorRD('$idDet','','','','','0','','','AD')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	public function activar($idDet)
	{
		$sql="CALL admUsuOperadorRD('$idDet','','','','','1','','','AD')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para listar los registros
	public function listar($iddisa,$idred,$idmred,$idestab)
	{
		$sql="CALL admUsuOperadorRD(0,'$iddisa','$idred','$idmred','$idestab','','','','L1')";
		return ejecutarConsulta($sql);
	}
}
?>
