<?php
session_start();
if (!isset($_SESSION["rolUsuario"]))
{
  header("Location: login.html");
}else{
  require 'header.php';
}
?>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Titulo General de la Página -->
        <section class="content-header">
          <h1>
           Administracion de Accesos
           </h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <!-- Este div es para el Titulo del formulario -->
              <div class="box box-info">
                <div class="box-header">
 
                  <!-- Este div es para el listado de registros -->
                  <div class="panel-body table-responsive" id="listadoregistros">
<!--
                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <h1 class="box-title"> Listado <button class="btn btn-success" id="btnAgregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar Nivel de Usuario</button>
                    </h1>
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    </div>
-->
                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label>Estado</label>
                      <select name="estadof" id="estadof" class="form-control selectpicker">
                        <option value="-Todos-">-Todos-</option>
                        <option value="Activos">Activos</option>
                        <option value="Inactivos">Inactivos</option>
                      </select>
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label> Hacer Click para ...</label>
                      <button class="btn btn-warning" onclick="listar()">
                        <i class="fa fa-refresh"></i> Obtener Datos</button>
                    </div>

                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <hr/>
                    </div>

                    <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                      <thead>
                            <tr>
                                <th>Acciones</th>
                                <th>Descripcion</th>
                                <th>Activo</th>
                                <th>OPCIONES</th>
                            </tr>
                        </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>

                   <!-- Este div es para el formulario de ingreso de datos -->
                  <div class="panel-body" style="height: 450px;" id="formularioregistros">

                    <h1 class="box-title"> Registro de Datos</h1>
                    <hr/>

                    <div class="panel-body">
                      <form name="formulario" id="formulario" method="POST">
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Nombre de Nivel Usuario</label>
                          <input type="hidden" name="idNivelUsuario" id="idNivelUsuario">
                          <input type="text" style="text-transform:uppercase;" class="form-control" name="nomNivelUsuario" id="nomNivelUsuario" maxlength="60" placeholder="Nombre de Nivel Usuario" autofocus required>
                        </div>
 
                        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                          <label>Estado</label>
                          <select size="6" name="estado" id="estado" class="form-control selectpicker" data-live-search="true">
                            <option value="S"> Activo </option>
                            <option value="N"> Inactivo </option>
                          </select>
                        </div>

                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>Guardar</button>
                          <!--Este boton va a llamar a la funcion cancelarform() de javascript -->
                          <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i>Cancelar</button>
                        </div>
                      </form>
                    </div>
                  </div>

                   <!-- Este div es para el formulario de opciones -->
                  <div class="panel-body" style="height: 450px;" id="formulario_opciones">

                    <h1 class="box-title"> Opciones </h1>
                    <hr/>

                    <div class="panel-body">
                      <form name="formularioo" id="formularioo" method="POST">
                        <input type="hidden" name="nivel_usuario" id="nivel_usuario" value="">
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" id="lista_opciones" name="lista_opciones">
                           
                        </div>

                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <button class="btn btn-primary" type="submit" id="btnGuardarOpciones"><i class="fa fa-save"></i>Guardar</button>
                          <!--Este boton va a llamar a la funcion cancelarform() de javascript -->
                          <button class="btn btn-danger" onclick="cancelarform1()" type="button"><i class="fa fa-arrow-circle-left"></i>Cancelar</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
<?php
require 'footer.php';
?>

<script type="text/javascript" src="scripts/admnivelusuario.js?v=1.0.5"></script>
