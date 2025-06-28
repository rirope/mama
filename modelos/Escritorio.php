<?php
// Se incluye la conexion a la BD
require "../config/Conexion.php";

class Escritorio
{
	//Implementamos el constructor
	public function __construct()
	{

	}

	//Implementamos el metodo para obtener el total de usuarios mama registrados en el rango de fechas
	public function obtenerTotalUsuariosMama($fechaIni,$fechaFin,$iddisa,$idred,$idmred,$idestab)
	{
		$sql="CALL totalUsuariosMama('$fechaIni','$fechaFin','$iddisa','$idred','$idmred','$idestab','TU')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para obtener el total de niños registrados activos en el rango de fechas
	public function obtenerTotalNinos($fechaIni,$fechaFin,$iddisa,$idred,$idmred,$idestab)
	{
		$sql="CALL totalUsuariosMama('$fechaIni','$fechaFin','$iddisa','$idred','$idmred','$idestab','TN')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para obtener el total de gestantes registradas activas en el rango de fechas
	public function obtenerTotalGest($fechaIni,$fechaFin,$iddisa,$idred,$idmred,$idestab)
	{
		$sql="CALL totalUsuariosMama('$fechaIni','$fechaFin','$iddisa','$idred','$idmred','$idestab','TG')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para obtener el numero de niños con menos de 30 dias de nacido a la fecha actual
	public function obtenerNinosMen30Dias($iddisa,$idred,$idmred,$idestab)
	{
		$sql="CALL reporte01('$iddisa','$idred','$idmred','$idestab','DA')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para obtener el numero de gestantes con FPP en los siguientes 30 dias a la fecha actual
	public function obtenerGestFpp30Dias($iddisa,$idred,$idmred,$idestab)
	{
		$sql="CALL reporte02('1000-01-01','1000-01-01','$iddisa','$idred','$idmred','$idestab','DA')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para obtener el numero de niños que van a cumplir 2 meses con rango de una semana antes y despues.
	public function obtenerNinos2mVRNP($iddisa,$idred,$idmred,$idestab)
	{
		$sql="CALL reporte03('1000-01-01','1000-01-01','$iddisa','$idred','$idmred','$idestab','DA')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para obtener el numero de niños que van a cumplir entre 31 y 60 dias.
	public function obtenerNinos31y60($iddisa,$idred,$idmred,$idestab)
	{
		$sql="CALL reporte04('1000-01-01','1000-01-01','$iddisa','$idred','$idmred','$idestab','DA')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para obtener el numero de niños que van a cumplir entre 61 y 90 dias.
	public function obtenerNinos61y90($iddisa,$idred,$idmred,$idestab)
	{
		$sql="CALL reporte05('1000-01-01','1000-01-01','$iddisa','$idred','$idmred','$idestab','DA')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para obtener el numero de niños que tienen entre 110 y 130 dias.
	public function obtenerNinos110y130($iddisa,$idred,$idmred,$idestab)
	{
		$sql="CALL reporte07('1000-01-01','1000-01-01','$iddisa','$idred','$idmred','$idestab','DA')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para obtener los datos para el gráfico de barras con todos los
	//datos de la BD
	public function generaGrafBarras($iddisa,$idred,$idmred,$idestab)
	{
		$sql="CALL graficos('1000-01-01','$iddisa','$idred','$idmred','$idestab','BA')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para obtener los datos para el gráfico de barras con todos los
	//datos de la BD
	public function generaGrafLineas($fechaIni,$iddisa,$idred,$idmred,$idestab)
	{
		$sql="CALL graficos('$fechaIni','$iddisa','$idred','$idmred','$idestab','LI')";
		return ejecutarConsulta($sql);
	}

}
?>
