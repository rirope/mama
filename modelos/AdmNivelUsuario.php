<?php
// Se incluye la conexion a la BD
require "../config/Conexion.php";

class AdmNivelUsuario
{
	//Implementamos el constructor
	public function __construct()
	{

	}

	//Metodo para insertar un registro
	public function insertar($descripcion,$estado)
	{
		$sql="INSERT INTO nivelusuario (descripcion, activo) 
		    VALUES('$descripcion', '$estado')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para actualizar los datos de un registro que se ha editado
	public function editar($idNivelUsuario,$descripcion,$estado)
	{
		$sql="UPDATE nivelusuario SET descripcion='$descripcion', activo='$estado' 
		   WHERE idNiv='$idNivelUsuario'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar registros
	public function desactivar($idNivelUsuario)
	{
		$sql="UPDATE nivelusuario SET activo='N' WHERE idNiv='$idNivelUsuario'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	public function activar($idNivelUsuario)
	{
		$sql="UPDATE nivelusuario SET activo='S' WHERE idNiv='$idNivelUsuario'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un metodo para mostrar los datos de un registro que se quiere editar o modificar
	public function mostrar($idNivelUsuario)
	{
		$sql="SELECT * FROM nivelusuario
			WHERE idNiv = '$idNivelUsuario'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implemantamos el metodo para listar los registros
	public function listar($estado)
	{
		$sql="SELECT * 
			FROM nivelusuario
			". ($estado != "" ? " where activo = '$estado' " : "") ." ORDER BY descripcion";
		return ejecutarConsulta($sql);
	}

	//Implemantamos el metodo para listar opciones
	public function listar_opciones($idNivelUsuario)
	{
		$sql="SELECT 1 relacion, o.idOpcion, o.nombre opcion, o.url, o.id_modulo, fu_bmodulo(o.id_modulo) modulo
FROM rolpermiso r
inner join opcion o on r.opcion_idOpcion = o.idOpcion
WHERE o.vigencia = 'S' and fu_bvigenciamodulo(o.id_modulo)='S' and r.nivelusuario_idNiv = '$idNivelUsuario'
UNION 
SELECT 0 relacionado, o1.idOpcion, o1.nombre opcion, o1.url, o1.id_modulo, fu_bmodulo(o1.id_modulo) modulo 
FROM opcion o1
WHERE o1.vigencia = 'S' and fu_bvigenciamodulo(o1.id_modulo)='S' and o1.idOpcion NOT IN 
(
SELECT opcion_idOpcion
FROM rolpermiso r1
WHERE nivelusuario_idNiv = '$idNivelUsuario'
)
ORDER BY id_modulo";
		$rspta = ejecutarConsulta($sql);
		while($row = $rspta->fetch_row()) {
		  $rows_respuesta[]=$row;
		}
		return $rows_respuesta;

	}

	public function insertaropcion($opcion, $mod, $idnivel) {
		
		//$cod=buscarPermiso($idnivel,$opcion);
		$cod = null;
		$datos1=null;
		if($cod==null){
			$sql="INSERT INTO rolpermiso(vigencia, opcion_idOpcion, modulo_idModulo, nivelusuario_idNiv)" 
			. "values('S', $opcion, $mod, '$idnivel')";
			$datos1 =  ejecutarConsulta($sql);
		}
		return $datos1;
	}

	public function eliminarOpciones($idNivelUsuario){
		$sql="DELETE FROM rolpermiso where nivelusuario_idNiv='$idNivelUsuario'";
		return ejecutarConsulta($sql);
    } 


    public function buscarPermiso($idNivelUsuario, $idOpcion){
    	$sql="SELECT * from rolpermiso where nivelusuario_idNiv='$idNivelUsuario' and opcion_idOpcion=$idOpcion and vigencia='S'";
		$datos = ejecutarConsulta($sql);
		return $datos;
    } 


	//Implementamos el metodo eliminar un registro
	public function eliminar($idNivelUsuario)
	{
		$sql="DELETE FROM nivelusuario WHERE idNiv='$idNivelUsuario'";
		return ejecutarConsulta($sql);
	}

}

?>