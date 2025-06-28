<?php
// Se incluye la conexion a la BD
require "../config/Conexion.php";

class RegistroGestantes
{
	//Implementamos el constructor
	public function __construct()
	{

	}

	//Metodo para insertar un registro
	public function insertar($tipoDocIdent,$nroDocIdent,$apePat,$apeMat,$nombres,$fechaNacGest,$idGrInst,$fecProbParto,$fechaAtc,$hClFam,$hClGest,$celularMadre,$celularAcomp,$progSoc,$msgVoz,$idEess,$idUsrApp)
	{
		$sql="CALL admGestantesCU(0,'$nombres','$apePat','$apeMat','$fechaNacGest','$idGrInst','$celularMadre','$fecProbParto','$fechaAtc','','$tipoDocIdent','$nroDocIdent','$hClGest','$celularAcomp','','$hClFam','$idUsrApp','$idEess','$msgVoz','$progSoc',0,0,'N',@lnId)";
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
	public function editar($idGest,$apePat,$apeMat,$nombres,$fechaNacGest,$idGrInst,$fecProbParto,$fechaAtc,$hClFam,$hClGest,$celularMadre,$celularAcomp,$progSoc,$msgVoz)
	{
		$sql="CALL admGestantesCU('$idGest','$nombres','$apePat','$apeMat','$fechaNacGest','$idGrInst','$celularMadre','$fecProbParto','$fechaAtc','','','','$hClGest','$celularAcomp','','$hClFam',0,0,'$msgVoz','$progSoc',0,0,'U',@lnId)";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar registros
	public function desactivar($id)
	{
		//$sql="UPDATE mamaspiloto SET estado='0' WHERE id='$id'";
		$sql="CALL admGestantesRD('$id','1000-01-01','1000-01-01','','','','','0','AD')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	public function activar($id)
	{
		$sql="CALL admGestantesRD('$id','1000-01-01','1000-01-01','','','','','1','AD')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un metodo para mostrar los datos de un registro que se quiere editar o modificar
	public function mostrar($id)
	{
		$sql="CALL admGestantesRD('$id','1000-01-01','1000-01-01','','','','','','M')";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementamos un metodo para buscar a usuaria con nueva gestación
	public function buscarGestanteExiste($id)
	{
		$sql="CALL admGestantesRD('$id','1000-01-01','1000-01-01','','','','','','M')";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementamos el metodo para listar los registros
	public function listar($fechaIni,$fechaFin,$iddisa,$idred,$idmred,$idestab,$estado)
	{
		$sql="CALL admGestantesRD(0,'$fechaIni','$fechaFin','$iddisa','$idred','$idmred','$idestab','$estado','L')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo eliminar un registro
	public function eliminar($id)
	{
		$sql="CALL admGestantesRD('$id','1000-01-01','1000-01-01','','','','','','E')";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para listar la gestantes que son buscadas en el modal
	public function listaGestBusq($tipoDoc,$nroDoc)
	{
		$sql="CALL admGestantesCU(0,'','','','1000-01-01','',0,'1000-01-01','1000-01-01','','$tipoDoc','$nroDoc','','','','',0,0,'','',0,0,'BG',@lnId)";
		return ejecutarConsulta($sql);
	}

	//Implementamos el metodo para listar la gestantes que son buscadas en el modal
	public function verGestacionActiva($nroDocIdent)
	{
		$sql="CALL admGestantesCU(0,'','','','1000-01-01','',0,'1000-01-01','1000-01-01','','','$nroDocIdent','','','','',0,0,'','',0,0,'AC',@lnId)";
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

	public function obtDatosGest($id)
	{
		$sql="CALL admGestantesRD('$id','1000-01-01','1000-01-01','','','','','','FG')";
		return ejecutarConsultaSimpleFila($sql);
	}

	// Verificamos si los datos de la gestante existen en la BD con el criterio: el nro de dni de la gestante, tipoPersona='G', necesariamente tiene que ser nro del dni de la mama
	public function verificarDatosGestante($tipoDoc, $nroDniGest)
	{
		$sql="CALL admGestantesCU(0,'','','','1000-01-01','',0,'1000-01-01','1000-01-01','','$tipoDoc','$nroDniGest','','','','',0,0,'','',0,0,'VE',@lnExiste)";
		$ejecutaSql=ejecutarConsulta($sql);
		if($ejecutaSql)
		{
			$rspta=ejecutarConsulta("SELECT @lnExiste AS nExiste");
			$fila=$rspta->fetch_assoc();
			$existe=$fila['nExiste'];
			return $existe;
		}else{
			return $ejecutaSql;
		}
	}

	// Listamos los datos de la gestante tipoPersona='G'
	public function listaImportarGestante($tipoDoc,$nroDoc)
	{
		$sql="CALL admGestantesCU(0,'','','','1000-01-01','',0,'1000-01-01','1000-01-01','','$tipoDoc','$nroDoc','','','','',0,0,'','',0,0,'LI',@lnExiste)";
		return ejecutarConsulta($sql);
	}

	// Realizamos la iportacion de los datos del niño al eess destino
	public function importarGestante($idGest,$idEeDest,$idUsrApp)
	{
		$sql="CALL admGestantesCU('$idGest','','','','1000-01-01','',0,'1000-01-01','1000-01-01','','','','','','','','$idUsrApp','$idEeDest','','',0,0,'IM',@lnExiste)";
		return ejecutarConsulta($sql);
	}

	// Se verifica si existe el numero de celular de la gestante en todos los beneficiarios tipo G o N que esten activos
	public function existeNroCelular($celularGest)
	{
		$sql="CALL admGestantesCU(0,'','','','1000-01-01','','$celularGest','1000-01-01','1000-01-01','','','','','','','',0,0,'','',0,0,'EC',@lnExiste)";
		$ejecutaSql=ejecutarConsulta($sql);
		if($ejecutaSql)
		{
			$rspta=ejecutarConsulta("SELECT @lnExiste AS nExiste");
			$fila=$rspta->fetch_assoc();
			$existe=$fila['nExiste'];
			return $existe;
		}else{
			return $ejecutaSql;
		}
	}

}
?>
