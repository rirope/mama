<?php
// Se incluye la conexion a la BD
require "../config/Conexion.php";

class AdmEstablec
{
	//Implementamos el constructor
	public function __construct()
	{

	}

	//Metodo para insertar un registro
	public function insertar($idDisa,$descRed)
	{
/*		$sql="CALL admRedes('','','$descRed','$idDisa','N',@lcId)";
		return ejecutarConsulta($sql);*/
	}

	//Implementamos el metodo para actualizar los datos de un registro que se ha editado
	public function editar($idRed,$descRed)
	{
/*		$sql="CALL admRedes('$idRed','','$descRed','','U',@lcId)";
		return ejecutarConsulta($sql);*/
	}

	//Implementamos un metodo para mostrar los datos de un registro que se quiere editar o modificar
	public function mostrar($idRed)
	{
/*		//$sql="CALL admRedes('$idRed','','','','V',@lcId)";
		$sql="SELECT * FROM red WHERE idRed='$idRed'";
		return ejecutarConsultaSimpleFila($sql);*/
	}

	//Implemantamos el metodo para listar los registros
	public function listar($idDisa)
	{
/*		//$sql="CALL admRedes('','','','$idDisa','L')";
		$sql="SELECT * FROM red WHERE disa_idDisa='$idDisa'";
		return ejecutarConsulta($sql);*/
	}

	//Implementamos el metodo para listar los registros y mostrar en el select
	public function listaEstabxMicRed($idMRed)
	{
		$sql="SELECT idEstabl,descEstab FROM establec WHERE microred_idMred='$idMRed' ORDER BY descEstab";
		return ejecutarConsulta($sql);
	}
}

?>