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
         Listado de Usuarios Administradores
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
                    <h1 class="box-title">Listado
                      <button class="btn btn-success" id="btnAgregar" onclick="mostrarform(true)">
                        <i class="fa fa-plus-circle"></i> Agregar Usuario</button>
                    </h1>
                    <div class="box-tools pull-right">
                    </div>
                  </div>

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
                      <th>Apellidos y Nombres</th>
                      <th>Profesión</th>
                      <th>DNI</th>
                      <th>Email</th>
                      <th>Lugar</th>
                      <th>Accesos</th>
                      <th>Activos</th>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
                
                <!-- Este div es para el formulario de ingreso de datos -->    
                <div class="panel-body" id="formularioregistros">
                  <form name="formulario" id="formulario" method="POST">
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <h4 class="box-title">Datos del Usuario</h4>
                      <hr/ style="margin-top:0px; margin-bottom: 0px;">
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 has-error">
                      <label>Apellido Paterno</label>
                      <input type="hidden" name="idUsu" id="idUsu" value="">
                      <input type="hidden" name="rolUsuario" id="rolUsuario" value="<?php echo $rolUsuario; ?>">
                      <input type="hidden" name="idDisaSel" id="idDisaSel" value="<?php echo $idDisa; ?>">
                      <input type="hidden" name="idRedSel" id="idRedSel" value="<?php echo $idRed; ?>">
                      <input type="hidden" name="idMredSel" id="idMredSel" value="<?php echo $idMred; ?>">
                      <input type="hidden" name="idEstablSel" id="idEstablSel" value="<?php echo $idEstabl; ?>">
                      <input type="hidden" name="idNiv" id="idNiv" value="<?php echo $idNiv; ?>">
                      <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" name="apePat" id="apePat" maxlength="100" placeholder="Apellido Paterno" required>
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 has-error">
                      <label>Apellido Materno</label>
                      <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" name="apeMat" id="apeMat" maxlength="100" placeholder="Apellido Materno" required>
                    </div>

                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 has-error">
                      <label>Nombres</label>
                      <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" name="nombres" id="nombres" maxlength="150" placeholder="Nombres" required>
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label>Profesión</label>
                      <select name="idProf" id="idProf" class="form-control selectpicker" data-live-search="true">                           
                      </select>
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 has-error">
                      <label>Nro DNI</label>
                      <input type="text" class="form-control" name="nroDni" id="nroDni" placeholder="Número de DNI" onkeypress="return soloNumeros(event,8,'nroDni');" required>
                      <!-- El siguiente control se utilizará para validar si hubo cambios en el dni cuando se hace edicion de datos -->
                      <input type="hidden" name="nroDniAnt" id="nroDniAnt">
                    </div>

                    <div id="fecNacGest" class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 has-error">
                      <label>Correo Electrónico</label>
                      <input type="email" class="form-control" name="correoElec" id="correoElec" placeholder="Correo Electrónico" required>
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 has-error">
                      <label>Usuario</label>
                      <input type="text" class="form-control" name="nomUsu" id="nomUsu" placeholder="Nombre de Usuario" required>
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 has-error">
                      <label>Password</label>
                      <input type="text" class="form-control" name="passUsu" id="passUsu" placeholder="Password" required>
                    </div>

                    &nbsp
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <h4 class="box-title">Lugares de Trabajo</h4>
                      <hr/ style="margin-top:0px; margin-bottom: 0px;">
                      <button class="btn btn-success" type="button" name="btnAsignar" id="btnAsignar" onclick="mostrarDetLugar(0,'N')"><i class="fa fa-plus-circle"></i> Asignar</button>

                    </div>

                    <!-- MOSTRAMOS LA TABLA DE DETALLES-->
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <div class="table-responsive">
                        <table id="tblListaDet" class="table table-striped table-bordered table-condensed table-hover" style="width:100%">
                          <thead>
                              <th>Opción</th>
                              <th>Lugar de Acceso</th>
                              <th>Rol</th>
                              <th>Fecha Inicio</th>
                              <th>Ultima Actualización</th>
                              <th>Estado</th>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>

                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <button class="btn btn-primary" type="submit" id="btnGuardar" name="btnGuardar"><i class="fa fa-save"></i>Guardar</button>
                      <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i>Cancelar</button>
                    </div>

                  </form>

                </div>
                <!--Fin centro -->
              </div>
            </div><!-- /.box -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->

  <!-- Modal Registro de Usuario Admin -->
  <div id="nuevoUsuarioAdmin" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

          <!--=====================================
          CABEZA DEL MODAL
          ======================================-->
          <div class="modal-header" style="background:#3c8dbc; color:white">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Registrar el Lugar de Acceso</h4>
          </div>
          <!--=====================================
          CUERPO DEL MODAL
          ======================================-->
          <div class="modal-body">
            <div class="box-body">

              <!-- DATOS DEL USUARIO-->
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Nivel de Acceso</label>
                <input type="hidden" name="idDet" id="idDet" value="">
                <input type="hidden" name="tcAccion" id="tcAccion" value="">
                <select name="idNivel" id="idNivel" class="form-control selectpicker">
                  <option value="">-Seleccionar Nivel-</option>
                </select>
              </div>

              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Disa/Geresa</label>
                <select name="iddisaUsu" id="iddisaUsu" class="form-control selectpicker" data-live-search="true" onchange='cargarRedesUsu(this.value);'>
                  <option value="00">Todas las Geresas</option>
                </select>
              </div>

              <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label>Red</label></br>
                <select name="idredUsu" id="idredUsu" class="form-control selectpicker" data-live-search="true" onchange='cargarMicroRedesUsu(this.value);'>
                  <option value="0000">Todas las Redes</option>
                </select>
              </div>

              <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label>MicroRed</label>
                <select name="idmredUsu" id="idmredUsu" class="form-control selectpicker" data-live-search="true" onchange='return (this.value);'>
                  <option value="000000">Todas las MicroRedes</option>
                </select>
              </div>

              <!-- FECHA DE REGISTRO -->
              <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label>Fecha de Registro</label>
                <input type="date" name="fechaReg" id="fechaReg" class="form-control" value="<?php echo date("Y-m-d"); ?>" disabled>
              </div>


            </div>
          </div>

          <!--=====================================
          PIE DEL MODAL
          ======================================-->
          <div class="modal-footer">
            <button type="button" type="submit" id="btnGrabaUsuario" name="btnGrabaUsuario" onclick="guardarLugarAcceso()" class="btn btn-primary">Guardar</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>
          </div>

      </div>
    </div>
  </div>
  <!-- Fin modal -->



<?php
require 'footer.php';
?>

<script type="text/javascript" src="scripts/admusuadmin.js"></script>