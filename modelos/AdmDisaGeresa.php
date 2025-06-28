<?php
// Se incluye la conexion a la BD
require "../config/Conexion.php";

class AdmDisaGeresa
{
	//Implementamos el constructor
	public function __construct()
	{

	}

	//Implemantamos el metodo para listar todas las disas
	public function listar()
	{
		$sql="CALL admDisaGeresa('','','L')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para listar los registros de todas las disas y mostrar en el select
	public function listadisas()
	{
		$sql="SELECT * FROM disa";
		return ejecutarConsulta($sql);
	}
	
	//Implementamos el metodo para listar las redes de la disa seleccionada
	public function listaRedes($idDisa)
	{
		$sql="CALL admRedes('','','','$idDisa','L',@lcId)";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para listar las microredes de la red seleccionada
	public function listaMicroRedes($idRed)
	{
		$sql="CALL admMicroRedes('','','','$idRed','L',@lcId)";
		return ejecutarConsulta($sql);
	}
}

?>