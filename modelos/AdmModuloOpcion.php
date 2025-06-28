<?php
// Se incluye la conexion a la BD
require "../config/Conexion.php";

class AdmModuloOpcion
{
	//Implementamos el constructor
	public function __construct()
	{

	}

	//Metodo para insertar un registro
	public function insertar($nomModulo,$descModulo,$clase,$estado)
	{
		$sql="INSERT INTO modulo (nombre, descripcion, vigencia, class) 
		    VALUES('$nomModulo', '$descModulo', '$estado', '$clase')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para actualizar los datos de un registro que se ha editado
	public function editar($idModulo,$nomModulo,$descModulo,$clase,$estado)
	{
		$sql="UPDATE modulo SET nombre='$nomModulo', descripcion='$descModulo', vigencia='$estado',
			class='$clase'  
		   WHERE idModulo='$idModulo'";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para actualizar los datos de un registro que se ha editado
	public function editarO($idOpcion,$nomOpcion,$rutaOpcion)
	{
		$sql="UPDATE opcion SET nombre='$nomOpcion', url='$rutaOpcion' 
		   WHERE idOpcion='$idOpcion'";
		return ejecutarConsulta($sql);
	}

	//Metodo para insertar un registro
	public function insertarO($idModulo,$nomOpcion,$rutaOpcion)
	{
		$sql="INSERT INTO opcion (nombre, url, vigencia, id_modulo) 
		    VALUES('$nomOpcion', '$rutaOpcion', 'S', '$idModulo')";
		return ejecutarConsulta($sql);
	}


	//Implementamos un método para desactivar registros
	public function desactivar($idModulo)
	{
		$sql="UPDATE modulo SET vigencia='N' WHERE idModulo='$idModulo'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar registros
	public function desactivarO($idOpcion)
	{
		$sql="UPDATE opcion SET vigencia='N' WHERE idOpcion='$idOpcion'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	public function activar($idModulo)
	{
		$sql="UPDATE modulo SET vigencia='S' WHERE idModulo='$idModulo'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	public function activarO($idOpcion)
	{
		$sql="UPDATE opcion SET vigencia='S' WHERE idOpcion='$idOpcion'";
		return ejecutarConsulta($sql);
	}


	//Implementamos un metodo para mostrar los datos de un registro que se quiere editar o modificar
	public function mostrar($idModulo)
	{
		$sql="SELECT * FROM modulo
			WHERE idModulo = '$idModulo'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementamos un metodo para mostrar los datos de un registro que se quiere editar o modificar
	public function mostrarOpcion($idOpcion)
	{
		$sql="SELECT * FROM opcion
			WHERE idOpcion = '$idOpcion'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implemantamos el metodo para listar los registros
	public function listar($estado)
	{
		$sql="SELECT * FROM modulo 
		". ($estado != "" ? " where vigencia = '$estado' " : "") ." ORDER BY nombre";
		return ejecutarConsulta($sql);
	}

	//Implemantamos el metodo para listar los registros
	public function listarOpcion($idModulo)
	{
		$sql="SELECT * FROM opcion WHERE id_modulo = '$idModulo'
		ORDER BY nombre";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo eliminar un registro
	public function eliminar($idModulo)
	{
		$sql="DELETE FROM modulo WHERE idModulo='$idModulo'";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo eliminar un registro
	public function eliminarO($idOpcion)
	{
		$sql="DELETE FROM opcion WHERE idOpcion='$idOpcion'";
		return ejecutarConsulta($sql);
	}

}

?>