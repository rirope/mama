<?php
// Se incluye la conexion a la BD
/* Linea agregada para usar con git */
require "../config/Conexion.php";
/* Linea agregada desde la web */
class AdmLogin
{
	//Implementamos el constructor
	public function __construct()
	{

	}

	// Funcion que inicia la sesion del usuaqrio logeado
	public function inciar_sesion_usuario($usr, $pass)
	{
		// Verificamos que el usuario existe y esta activo en por lo menos algún lugar
		$sql="CALL admUsuarios(0,'','','','','','','',0,'',0,'$usr','$pass','UL')";
		$rspta=ejecutarConsultaSimpleFila($sql);
		$valor = "n";
		if ($rspta) {
			$valor = "s";
		}
		//El inicio de sesión se hizo en: ../ajax/login.php
		$_SESSION['idUsr']=$rspta['idPers'];
		$_SESSION['idUsrApp']=$rspta['idUsrApp'];
		$_SESSION['nomUsr']=$rspta['nombres'];
		$_SESSION['apePatUsr']=$rspta['apePat'];
		$_SESSION['apeMatUsr']=$rspta['apeMat'];
		$_SESSION['profUsr']=$rspta['profesion'];
		$_SESSION['idEstabl']=0;
		$_SESSION['idNiv']='';
		$_SESSION['idRol']='';
		$_SESSION['idDisa']='';
		$_SESSION['idRed']='';
		$_SESSION['idMred']='';
		$_SESSION['nivelUsuario']='';
		$_SESSION['origenUsuario']='';
		$_SESSION['rolUsuario']='';
		return $valor;
	}

	//CERRAR SESION
	public function cerrar_sesion()
	{
		session_start();
		session_destroy();
		header("Location: ../vistas/login.html");
	}

	//Metodo para mostrar el menu
	public function listarMenu()
	{
		//TRAEMOS EL DATO IDNIV DE SESSION 

		$idNiv = $_SESSION['idNiv'];
		$sql= "SELECT idModulo, nombre, class 
			from rolpermiso r 
			inner join modulo m on r.modulo_IdModulo = m.idModulo
			where m.vigencia='S' and r.vigencia='S' and nivelusuario_idNiv = '$idNiv'
			group by idModulo
			order by idModulo asc";
		$rspta=ejecutarConsulta($sql);
		while($row = $rspta->fetch_row()) {
		  $rows_modulo[]=$row;
		}

		$sql="SELECT 1 relacionado, o.idOpcion, o.nombre opcion, o.url, o.id_modulo, fu_bmodulo(o.id_modulo) modulo, 
			(Select id_mod_categoria from modulo where idModulo=o.id_modulo) id_mod_categoria 
			from rolpermiso r 
			inner join opcion o on r.opcion_IdOpcion=o.idOpcion
			where o.vigencia='S' and fu_bvigenciamodulo(o.id_modulo)='S' and nivelusuario_idNiv = '$idNiv' 
			order by id_modulo asc,opcion";
		$rspta=ejecutarConsulta($sql);
		while($row = $rspta->fetch_row()) {
		  $rows_opcion[]=$row;
		}
		$menu = array();
		$menu[1] = "";
		foreach($rows_modulo as $row)
		{
			$menu[1] .= '<li class="treeview">
              <a href="#">
                <i class="'.$row[2].'"></i> <span>'.$row[1].'</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>';
              $count = 0;
              $menu[2] = '';
              foreach ($rows_opcion as $opcion) {
              	if ($row[0] == $opcion[4]) {
              		$count = 1;
              		$menu[2] .= '<li><a href="'.$opcion[3].'"><i class="fa fa-circle-o"></i> '.$opcion[2].'</a></li>';
              	}
              }
              if ($count == 1) {
              	$menu[1] .= '<ul class="treeview-menu">' .$menu[2] . '</ul>'; 
              }
            $menu[1] .='</li>  ';
		
		}
		echo $menu[1];
	}

	//Metodo para validar si dni que se está ingresando ya existe en la BD: 1-Existe, 0-No existe
	public function validarDni($nroDni)
	{
		$sql="CALL admUsuAdminCU(0,'','','','$nroDni','','','','','VA',@lnRspta)";
		$rspta=ejecutarConsulta($sql);
		if($rspta)
		{
			//Obtenemos la respuesta
			$existeDni=ejecutarConsulta("SELECT @lnRspta AS lnRspta");
			$fila = $existeDni->fetch_assoc();
			$lnRspta = $fila['lnRspta'];
			return $lnRspta;
		}else{
			return $rspta;
		}
	}

	//Implementamos un metodo para mostrar los datos de un operador basado en su dni
	public function mostrar($nroDni)
	{
		$sql="CALL admUsuOperadorRD(0,'','','','$nroDni','','','','M1')";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Grabamos los datos del nuevo usuario
	public function grabarNvoUsuario($apePat,$apeMat,$nombres,$idProf,$nroDni,$correoElec,$idDisa,$idRed,$idMred,$idEstab)
	{
		$sql="CALL registroNvoOperadorLogin(0,'$apePat', '$apeMat', '$nombres', '$nroDni', '$correoElec', '$idProf', '$idDisa', '$idRed', '$idMred', '$idEstab','N',@cResp)";
		$rspta=ejecutarConsulta($sql);
		if($rspta)
		{
			$seGrabo=ejecutarConsulta("SELECT @cResp AS cRsptaGrabo");
			$fila=$seGrabo->fetch_assoc();
			$lcSeGrabo=$fila['cRsptaGrabo'];
			return $lcSeGrabo;
		}else{
			return $rspta;
		}
	}

	//Grabamos los datos del nuevo lugar de acceso que se asigna el operador desde la pantalla del login
	public function grabarLugarAcceso($idPers,$idProf,$idDisa,$idRed,$idMred,$idEstab)
	{
		$sql="CALL registroNvoOperadorLogin('$idPers','', '', '', '', '', '$idProf', '$idDisa', '$idRed', '$idMred', '$idEstab','E',@cResp)";
		$rspta=ejecutarConsulta($sql);
		if($rspta)
		{
			$seGrabo=ejecutarConsulta("SELECT @cResp AS cRsptaGrabo");
			$fila=$seGrabo->fetch_assoc();
			$lcSeGrabo=$fila['cRsptaGrabo'];
			return $lcSeGrabo;
		}else{
			return $rspta;
		}
	}

	//Se verifica que el lugar de acceso que se esta asignando el usuario no exista en la bd
	public function verificarLugarAcceso($idPers,$idDisa,$idRed,$idMred,$idEstab)
	{
		$sql="CALL registroNvoOperadorLogin('$idPers','', '', '', '', '', '', '$idDisa', '$idRed', '$idMred', '$idEstab','V',@cResp)";
		$rspta=ejecutarConsulta($sql);
		if($rspta)
		{
			$existe=ejecutarConsulta("SELECT @cResp AS cExiste");
			$fila=$existe->fetch_assoc();
			$lcExiste=$fila['cExiste'];
			return $lcExiste;
		}else{
			return $rspta;
		}
	}

	//Se verifica que el email ingresado por el usuario existe en la BD
	public function verificarEmail($correoElecRec)
	{
		$sql="CALL registroNvoOperadorLogin(0,'', '', '', '', '$correoElecRec', '', '', '', '', '','VE',@cResp)";
		$rspta=ejecutarConsulta($sql);
		return $rspta;
	}

	//Implementamos el metodo para listar las profesiones para con combo
	public function listaProf()
	{
		$sql="CALL mantWsdat01('04','','','','LO',@lcId)";
		return ejecutarConsulta($sql);
	}


}

?>
