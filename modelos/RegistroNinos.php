<?php
// Se incluye la conexion a la BD
require "../config/Conexion.php";

class RegistroNinos
{
	//Implementamos el constructor
	public function __construct()
	{

	}

	//Metodo para insertar un registro
	public function insertar($tipoDocIdent,$nroDocIdent,$apePat,$apeMat,$nombres,$fechaNacMama,$idGrInst,$celularMadre,$celularAcomp,$progSoc,$msgVoz,$tipoDiNino,$nroDiNino,$hClNino,$hClFam,$fechaNacNino,$fechaAtcNino,$progSocNino,$idEess,$idUsrApp)
	{
		$sql="CALL admNinosCU(0,'$tipoDocIdent','$nroDocIdent','$apePat','$apeMat','$nombres','$fechaNacMama','$idGrInst','$celularMadre','$celularAcomp','$progSoc','$msgVoz','$tipoDiNino','$nroDiNino','$hClNino','$hClFam','$fechaNacNino','$fechaAtcNino','$progSocNino','','$idUsrApp','$idEess',0,0,'N',@lnId)";
		$ejecutaSql=ejecutarConsulta($sql);
		if($ejecutaSql)
		{
			$rspta = ejecutarConsulta("SELECT @lnId AS id");
			$fila = $rspta->fetch_assoc();
			$nvoId = $fila['id'];
			return $nvoId;
		}else{
			return $ejecutaSql;
		}
	}

	//Implementamos el metodo para actualizar los datos de un registro que se ha editado
	public function editar($idNino,$idGrInst,$celularMadre,$celularAcomp,$progSoc,$msgVoz,$tipoDiNino,$nroDiNino,$hClNino,$hClFam,$fechaNacNino,$fechaAtcNino,$progSocNino)
	{
		$sql="CALL admNinosCU('$idNino','','','','','','1000-01-01','$idGrInst','$celularMadre','$celularAcomp','$progSoc','$msgVoz','$tipoDiNino','$nroDiNino','$hClNino','$hClFam','$fechaNacNino','$fechaAtcNino','$progSocNino','',0,0,0,0,'U',@lnId)";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar registros
	public function desactivar($id)
	{
		$sql="CALL admNinosRD('$id','1000-01-01','1000-01-01','','','','','0','AD')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	public function activar($id)
	{
		$sql="CALL admNinosRD('$id','1000-01-01','1000-01-01','','','','','1','AD')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un metodo para mostrar los datos de un registro que se quiere editar o modificar
	public function mostrar($id)
	{
		$sql="CALL admNinosRD('$id','1000-01-01','1000-01-01','','','','','','M')";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementamos un metodo para buscar a la mamá que ya existe en la BD del niño a registrar
	public function buscarMamaExiste($nroDocIdent)
	{
		$sql="CALL admNinosCU(0,'','$nroDocIdent','','','','1000-01-01','',0,'','','','','','','','1000-01-01','1000-01-01','','',0,0,0,0,'AC',@lnId)";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementamos el metodo para listar los registros
	public function listar($fechaIni,$fechaFin,$iddisa,$idred,$idmred,$idestab,$estado)
	{
		$sql="CALL admNinosRD(0,'$fechaIni','$fechaFin','$iddisa','$idred','$idmred','$idestab','$estado','L')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo eliminar un registro
	public function eliminar($id)
	{
		$sql="CALL admNinosRD('$id','1000-01-01','1000-01-01','','','','','','E')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para listar la gestantes que son buscadas en el modal
	public function listaMamaBusq($tipoDoc,$nroDoc)
	{
		$sql="CALL admNinosCU(0,'$tipoDoc','$nroDoc','','','','1000-01-01','',0,'','','','','','','','1000-01-01','1000-01-01','','',0,0,0,0,'BG',@lnId)";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para verificar si el dni del niño ya existe
	public function existedninino($nroDiNino)
	{
		$sql="CALL admNinosCU(0,'','','','','','1000-01-01','',0,'','','','','$nroDiNino', '', '', '1000-01-01', '1000-01-01', '', '', 0, 0, 0, 0, 'EN', @lnId)";
		$ejecutaSql=ejecutarConsulta($sql);
		if($ejecutaSql)
		{
			$rspta = ejecutarConsulta("SELECT @lnId AS lnExiste");
			$fila = $rspta->fetch_assoc();
			$existe = $fila['lnExiste'];
			return $existe;
		}else{
			return $ejecutaSql;
		}

	}

	// Verificamos si los datos de la mama del niño existen en la BD con el criterio: el nro de dni de la mama existe, tipoPersona='N' y estado='1', necesariamente tiene que ser nro del dni de la mama
	public function verificarDatosMamaImportarNino($nroDniMama)
	{
		$sql="CALL admNinosCU(0,'','$nroDniMama','','','','1000-01-01','',0,'','','','','','','','1000-01-01','1000-01-01','','',0,0,0,0,'VE',@lnExiste);";
		$ejecutaSql=ejecutarConsulta($sql);
		if($ejecutaSql)
		{
			$rspta=ejecutarConsulta("SELECT @lnExiste AS lnExiste");
			$fila=$rspta->fetch_assoc();
			$existe=$fila['lnExiste'];
			return $existe;
		}else{
			return $ejecutaSql;
		}
	}

	// Listamos los datos del niño cuya madre sea tipoPersona='N' y estado='1', necesariamente tiene que ser nro del dni de la mama
	public function listaImportarNino($nroDniMama)
	{
		$sql="CALL admNinosCU(0,'','$nroDniMama','','','','1000-01-01','',0,'','','','','','','','1000-01-01','1000-01-01','','',0,0,0,0,'LI',@lnExiste);";
		return ejecutarConsulta($sql);
	}

	// Realizamos la iportacion de los datos del niño al eess destino
	public function importarNino($idMa,$idEeDest,$idUsrApp)
	{
		$sql="CALL admNinosCU('$idMa','','','','','','1000-01-01','',0,'','','','','','','','1000-01-01','1000-01-01','','','$idUsrApp','$idEeDest',0,0,'IM',@lnExiste);";
		return ejecutarConsulta($sql);
	}

	// Obtenemos los datos para imprimir el formato de registro del niño
	public function obtDatosNinos($id)
	{
		$sql="CALL admNinosRD('$id','1000-01-01','1000-01-01','','','','','','FN')";
		return ejecutarConsultaSimpleFila($sql);
	}

}
?>
