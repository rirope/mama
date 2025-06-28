<?php
session_start();
if (!isset($_SESSION["rolUsuario"]))
{
  header("Location: login.html");
}else{
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
         Listado de Usuarios Operadores con Peticion de Acceso Requerido o Inactivos
         </h1>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-info">
              <div class="box-header">

                <!-- Este div es para el listado de registros -->
                <div class="panel-body table-responsive" id="listadoregistros">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h1 class="box-title">Listado</h1>
                    <div class="box-tools pull-right">
                    </div>
                  </div>
                  <!-- Campos ocultos para mostrar niveles de acceso -->
                  <input type="hidden" name="idDisaSel" id="idDisaSel" value="<?php echo $idDisa; ?>">
                  <input type="hidden" name="idRedSel" id="idRedSel" value="<?php echo $idRed; ?>">
                  <input type="hidden" name="idMredSel" id="idMredSel" value="<?php echo $idMred; ?>">
                  <input type="hidden" name="idEstablSel" id="idEstablSel" value="<?php echo $idEstabl; ?>">
                  <input type="hidden" name="idNiv" id="idNiv" value="<?php echo $idNiv; ?>">

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Disa/Geresa</label>
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

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
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

                  <div class="form-group col-lg-1 col-md-1 col-sm-1 col-xs-12">
                    <label>&nbsp</label></br>
                    <button class="btn btn-warning" id="btnBuscar" name="btnBuscar" onclick="listar()"><i class="fa fa-search"></i> Mostrar</button>
                  </div>

                  <table id="tbllistado" class="table table-sm table-striped table-bordered table-condensed table-hover" style="width:100%">
                    <thead>
                      <th>Opciones</th>
                      <th>Fecha Peticion</th>
                      <th>Apellidos y Nombres</th>
                      <th>Profesión</th>
                      <th>DNI</th>
                      <th>Email</th>
                      <th>Establecimiento</th>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
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

<script type="text/javascript" src="scripts/admpeticionacceso.js"></script>