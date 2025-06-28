<?php

session_start();
if (!isset($_SESSION["rolUsuario"]))
{
  header("Location: login.html");
}else{
  // Aqui capturamos todas las variables globales de sesion para llevar el control de las mismas
  $_SESSION['idEstabl']=$_GET['idEstabl'];
  $_SESSION['idNiv']=$_GET['idNiv'];
  $_SESSION['origenUsuario']=$_GET['origenUsuario'];
  $_SESSION['idRol']=$_GET['idRol'];
  $_SESSION['idDisa']=$_GET['idDisa'];
  $_SESSION['idRed']=$_GET['idRed'];
  $_SESSION['idMred']=$_GET['idMred'];
  $_SESSION['nivelUsuario']=$_GET['nivelUsuario'];
  $_SESSION['rolUsuario']=$_GET['rolUsuario'];

  require 'header.php';
  require_once "../config/funciones.php";
  $rolUsuario=$_SESSION['rolUsuario'];
  //Para controlar los filtros de acuerdo al nivel de usuario
  $idDisa=$_SESSION['idDisa'];
  $idRed=$_SESSION['idRed'];
  $idMred=$_SESSION['idMred'];
  $idEstabl=$_SESSION['idEstabl'];
  $idNiv=$_SESSION['idNiv'];
  //Establecemos zona horaria por defecto
  date_default_timezone_set('America/Lima');
}
?>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Titulo General de la Página -->
      <section class="content-header">
        <h1>
          Dashboard - 
          <small>Panel de Control</small></h1>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-info">
              <div class="box-header">

                <!-- Este div es para el listado de registros -->
                <div class="panel-body">
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Disa/Geresa</label>
                    <input type="hidden" name="rolUsuario" id="rolUsuario" value="<?php echo $rolUsuario; ?>">
                    <input type="hidden" name="idDisaSel" id="idDisaSel" value="<?php echo $idDisa; ?>">
                    <input type="hidden" name="idRedSel" id="idRedSel" value="<?php echo $idRed; ?>">
                    <input type="hidden" name="idMredSel" id="idMredSel" value="<?php echo $idMred; ?>">
                    <input type="hidden" name="idEstablSel" id="idEstablSel" value="<?php echo $idEstabl; ?>">
                    <input type="hidden" name="idNiv" id="idNiv" value="<?php echo $idNiv; ?>">
                    <select name="iddisa" id="iddisa" class="form-control selectpicker" data-live-search="true" onchange='cargarRedes(this.value);'>
                      <option value="00">Todas las Geresas</option>
                    </select>
                  </div>

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Red</label></br>
                    <select name="idred" id="idred" class="form-control selectpicker" data-live-search="true" onchange='cargarMicroRedes(this.value);'>
                      <option value="0000">Todas las Redes</option>
                    </select>
                  </div>

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>MicroRed</label>
                    <select name="idmred" id="idmred" class="form-control selectpicker" data-live-search="true" onchange='cargarEstabl(this.value);'>
                      <option value="000000">Todas las MicroRedes</option>
                    </select>
                  </div>

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Establecimiento</label>
                    <select name="idestab" id="idestab" class="form-control selectpicker" data-live-search="true">
                      <option value="0">Todos los Establecimientos</option>
                    </select>
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                   <label>Desde</label>
                   <input type="date" class="form-control" name="fechaIni" id="fechaIni" value="<?php echo PrimerDiaMes(); ?>">
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label>Hasta</label>
                    <input type="date" class="form-control" name="fechaFin" id="fechaFin" value="<?php echo date("Y-m-d"); ?>">
                  </div>

                  <div class="form-group col-lg-1 col-md-1 col-sm-1 col-xs-12">
                    <label>&nbsp</label></br>
                    <button class="btn btn-warning" id="btnBuscar" name="btnBuscar" onclick="generaDashboard()"><i class="fa fa-search"></i> Mostrar</button>
                  </div>
                </div>
                
                <div class="panel-body">
                  <!-- Aqui empiezan los cuadros para mostrar los datos del dashboard -->
                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="small-box bg-purple">
                      <div class="inner">
                        <h4>
                          <strong><p id="totUsu" style="font-size:2.5rem;"></p></strong>
                        </h4>
                        <p>Total Beneficiarios Registrados</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-android-people"></i>
                      </div>
                      <a href="#" class="small-box-footer">Total Beneficiarios Registrados <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                  </div>

                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="small-box bg-purple">
                      <div class="inner">
                        <h4>
                          <strong><p id="totNinos" style="font-size:2.5rem;"></p></strong>
                        </h4>
                        <p>Total Niños Registrados</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-android-contacts"></i>
                      </div>
                      <a href="registroninos.php" class="small-box-footer">Total Niños Registrados <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                  </div>

                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="small-box bg-purple">
                      <div class="inner">
                        <h4>
                          <strong><p id="totGest" style="font-size:2.5rem;"></p></strong>
                        </h4>
                        <p>Total Gestantes Registradas</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-woman"></i>
                      </div>
                      <a href="registrogestantes.php" class="small-box-footer">Total Gestantes Registradas <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                  </div>

                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="small-box bg-yellow">
                      <div class="inner">
                        <h4>
                          <strong><p id="gestFpp" style="font-size:2.5rem;"></p></strong>
                        </h4>
                        <p>Gestantes Fpp sgtes 30 días</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-female"></i>
                      </div>
                      <a href="reporte02.php" class="small-box-footer">Gestantes Fpp sgtes 30 días <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                  </div>

                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="small-box bg-aqua">
                      <div class="inner">
                        <h4>
                          <strong><p id="ninosMen30" style="font-size:2.5rem;"></p></strong>
                        </h4>
                        <p>Niños < 30 días de Nacido</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-ios-copy"></i>
                      </div>
                      <a href="reporte01.php" class="small-box-footer">Niños < 30 días de Nacido <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                  </div>

                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="small-box bg-aqua">
                      <div class="inner">
                        <h4>
                          <strong><p id="ninos2m" style="font-size:2.5rem;"></p></strong>
                        </h4>
                        <p>Niños 2 meses V/R.N.P.</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-ios-copy"></i>
                      </div>
                      <a href="reporte03.php" class="small-box-footer">Niños 2 meses V/R.N.P. <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                  </div>

                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="small-box bg-aqua">
                      <div class="inner">
                        <h4>
                          <strong><p id="ninos3160" style="font-size:2.5rem;"></p></strong>
                        </h4>
                        <p>Niños entre 31 y 60 dias</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-ios-copy"></i>
                      </div>
                      <a href="reporte04.php" class="small-box-footer">Niños entre 31 y 60 dias <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                  </div>

                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="small-box bg-aqua">
                      <div class="inner">
                        <h4>
                          <strong><p id="ninos6190" style="font-size:2.5rem;"></p></strong>
                        </h4>
                        <p>Niños entre 61 y 90 dias</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-ios-copy"></i>
                      </div>
                      <a href="reporte05.php" class="small-box-footer">Niños entre 61 y 90 dias <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                  </div>

                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="small-box bg-aqua">
                      <div class="inner">
                        <h4>
                          <strong><p id="ninos110130" style="font-size:2.5rem;"></p></strong>
                        </h4>
                        <p>Niños entre 110 y 130 dias</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-ios-copy"></i>
                      </div>
                      <a href="reporte08.php" class="small-box-footer">Niños a suplementar <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                </div>

                <div class="panel-body">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="box box-primary">
                      <div class="box-header with-border">
                        Avance de Captacion de Beneficiarios Mama Según Nivel Seleccionado - Año <span id="annioGrafLin"><?php echo date('Y');?></span>
                      </div>
                      <div class="box-body">
                        <canvas id="graf02" width="400" height="150"></canvas>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="panel-body">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="box box-primary">
                      <div class="box-header with-border">
                        Total de Beneficiarios Gestantes y Niños desde el Año 2015 - Programa MAMA
                      </div>
                      <div class="box-body">
                        <canvas id="graf01" width="400" height="150"></canvas>
                      </div>
                    </div>
                  </div>
                </div>

                <!--Fin centro -->
              </div>
            </div><!-- /.box -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->

<?php
require 'footer.php';
?>

<script type="text/javascript" src="scripts/escritorio.js"></script>
<script src="../public/js/Chart.min.js"></script>
<script src="../public/js/Chart.bundle.min.js"></script>