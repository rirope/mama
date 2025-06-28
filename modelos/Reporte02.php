<?php
// Se incluye la conexion a la BD
require "../config/Conexion.php";

class Reporte02
{
	//Implementamos el constructor
	public function __construct()
	{

	}

	//Implementamos el metodo para listar los registros
	public function listar($fechaIni,$fechaFin,$iddisa,$idred,$idmred,$idestab)
	{
		$sql="CALL reporte02('$fechaIni','$fechaFin','$iddisa','$idred','$idmred','$idestab','L')";
		return ejecutarConsulta($sql);
	}

}
?>
