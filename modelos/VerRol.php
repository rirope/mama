<?php
// Se incluye la conexion a la BD
require "../config/Conexion.php";

class NivelUsuario
{
	//Implementamos el constructor
	public function __construct()
	{

	}

	//Implemantamos el metodo para listar los registros
	public function listar()
	{
		$sql="CALL mantWsDat01('07','','','','L',@lcId)";
		return ejecutarConsulta($sql);
	}

}

?>