<?php
// Se incluye la conexion a la BD
require "../config/Conexion.php";

class RolUsuario
{
	//Implementamos el constructor
	public function __construct()
	{

	}

	//Implemantamos el metodo para listar los registros
	public function listar()
	{
		//$sql="CALL mantWsDat01('06','','','','L',@lcId)";
		$sql="SELECT CONCAT(nroOrden,' ',descripcion) AS descripcion FROM nivelUsuario ORDER BY nroOrden";
		return ejecutarConsulta($sql);
	}

}

?>