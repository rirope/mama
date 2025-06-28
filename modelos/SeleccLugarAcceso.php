<?php
// Se incluye la conexion a la BD
require "../config/Conexion.php";

class SeleccLugarAcceso
{
	//Implementamos el constructor
	public function __construct()
	{

	}

	//Implemantamos el metodo para listar los registros
	public function listar($idUsr)
	{
		$sql="CALL admUsuarios('$idUsr','','','','','','','',0,'',0,'','','DU')";
		return ejecutarConsulta($sql);
	}
}

?>