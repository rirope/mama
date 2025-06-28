<?php
// Se incluye la conexion a la BD
require "../config/Conexion.php";

class AdmOrgGeo
{
	//Implementamos el constructor
	public function __construct()
	{

	}

	//Implemantamos el metodo para listar todas las disas
	public function listarDptos()
	{
		$sql="SELECT idDpto, descDpto FROM dpto";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para listar los registros de todas las disas y mostrar en el select
	public function listarProv($idDpto)
	{
		$sql="SELECT idProv, descProv FROM prov WHERE dpto_idDpto='$idDpto'";
		return ejecutarConsulta($sql);
	}
	
	//Implementamos el metodo para listar las redes de la disa seleccionada
	public function listarDist($idProv)
	{
		$sql="SELECT idDist, descDist FROM dist WHERE prov_idprov='$idProv'";
		return ejecutarConsulta($sql);
	}


}

?>