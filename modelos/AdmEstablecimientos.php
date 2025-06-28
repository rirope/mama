<?php
// Se incluye la conexion a la BD
require "../config/Conexion.php";

class AdmEstablecimientos
{
	//Implementamos el constructor
	public function __construct()
	{

	}

	//Metodo para insertar un registro
	public function insertar($codEstab,$descEstab,$idTipEstab,$idCateg,$iddisaUsu,$idredUsu,$idmredUsu,$iddpto,$idprov,$iddist)
	{
		$sql="CALL admEstablecimientosCU(0,'$codEstab','$descEstab','$idTipEstab','$idCateg','$iddpto','$idprov','$iddist','$iddisaUsu','$idredUsu','$idmredUsu','N',@lnId)";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para actualizar los datos de un registro que se esta editando
	public function editar($idEstabl,$codEstab,$descEstab,$idTipEstab,$idCateg,$iddisaUsu,$idredUsu,$idmredUsu,$iddpto,$idprov,$iddist)
	{
		$sql="CALL admEstablecimientosCU('$idEstabl','$codEstab','$descEstab','$idTipEstab','$idCateg','$iddpto','$idprov','$iddist','$iddisaUsu','$idredUsu','$idmredUsu','U',@lnId)";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar registros
	public function desactivar($idEstabl)
	{
		$sql="CALL admEstablecimientosRD('$idEstabl','','','','N','AD')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	public function activar($idEstabl)
	{
		$sql="CALL admEstablecimientosRD('$idEstabl','','','','S','AD')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo eliminar un registro
	public function eliminar($idEstabl)
	{
		$sql="CALL admEstablecimientosRD('$idEstabl','','','','','E')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un metodo para mostrar los datos de un registro que se quiere editar o modificar
	public function mostrar($idEstabl)
	{
		$sql="CALL admEstablecimientosRD('$idEstabl','','','','','M')";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementamos un metodo para eliminar los registros del usuario
	public function eliminarUsuAdmin($id)
	{
		$sql="CALL admUsuAdminRD('$id','','','','','','','EU')";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementamos un metodo para mostrar los datos del item del detalle seleccionado
	public function mostrarDetLugar($idDet)
	{
		$sql="CALL admUsuAdminRD('$idDet','','','','','','','DL')";
		return ejecutarConsultaSimpleFila($sql);
	}


	//Implementamos el metodo para listar los registros
	public function listar($iddisa,$idred,$idmred,$activo)
	{
		$sql="CALL admEstablecimientosRD(0,'$iddisa','$idred','$idmred','$activo','L')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para listar las profesiones 
	public function listaTipoEstab()
	{
		$sql="CALL mantWsdat01('01','','','','L',@lcId)";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para listar los niveles de usuario
	public function listaCategEstab()
	{
		$sql="CALL mantWsdat01('02','','','','L',@lcId)";
		return ejecutarConsulta($sql);
	}

}
?>
