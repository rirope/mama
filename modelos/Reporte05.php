<?php
// Se incluye la conexion a la BD
require "../config/Conexion.php";

class Reporte05
{
	//Implementamos el constructor
	public function __construct()
	{

	}

	//Implementamos el metodo para listar los registros
	public function listar($iddisa,$idred,$idmred,$idestab)
	{
		$sql="CALL reporte05('1000-01-01','1000-01-01','$iddisa','$idred','$idmred','$idestab','L')";
		return ejecutarConsulta($sql);
	}

}
?>
