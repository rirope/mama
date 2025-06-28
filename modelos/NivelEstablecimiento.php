<?php
// Se incluye la conexion a la BD
require "../config/Conexion.php";

class NivelEstablecimiento
{
	//Implementamos el constructor
	public function __construct()
	{

	}

	//Metodo para insertar un registro
	public function insertar($descripcion)
	{
		$sql="CALL mantWsDat01('02','','','$descripcion','N',@lcId)";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para actualizar los datos de un registro que se ha editado
	public function editar($idwsdat01,$descripcion)
	{
		$sql="CALL mantWsDat01('','','$idwsdat01','$descripcion','U',@lcId)";
		return ejecutarConsulta($sql);
	}

	//Implementamos un metodo para mostrar los datos de un registro que se quiere editar o modificar
	public function mostrar($idwsdat01)
	{
		$sql="CALL mantWsDat01('','','$idwsdat01','','V',@lcId)";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implemantamos el metodo para listar los registros
	public function listar()
	{
		$sql="CALL mantWsDat01('02','','','','L',@lcId)";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo eliminar un registro
	public function eliminar($idwsdat01)
	{
		$sql="CALL mantWsDat01('','','$idwsdat01','','E',@lcId)";
		return ejecutarConsulta($sql);
	}

	//Implemantamos el metodo para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM categoria WHERE condicion=1";
		return ejecutarConsulta($sql);
	}
}

?>