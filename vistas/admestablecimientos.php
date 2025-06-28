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
         Listado de Establecimientos de Salud
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
                        <i class="fa fa-plus-circle"></i> Agregar Ee.Ss.</button>
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

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>MicroRed</label>
                    <select name="idmred" id="idmred" class="form-control selectpicker" data-live-search="true" onchange='cargarEstabl(this.value);'>
                      <option value="000000">Todos los Establecimientos</option>
                    </select>
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label>Estado</label>
                    <select name="activo" id="activo" class="form-control selectpicker" required="">
                      <option value="">-Todos-</option>
                      <option value="S">Activos</option>
                      <option value="N">Inactivos</option>
                    </select>
                  </div>

                  <div class="form-group col-lg-1 col-md-1 col-sm-1 col-xs-12">
                    <label>&nbsp</label></br>
                    <button class="btn btn-warning" id="btnBuscar" name="btnBuscar" onclick="listar()"><i class="fa fa-search"></i> Mostrar</button>
                  </div>

                  <table id="tbllistado" class="table table-sm table-striped table-bordered table-condensed table-hover" style="width:100%">
                    <thead>
                      <th>Opciones</th>
                      <th>Renipres</th>
                      <th>Nombre del EeSs</th>
                      <th>Tipo</th>
                      <th>Categoria</th>
                      <th>Ultima Actualización</th>
                      <th>Activo</th>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
                
                <!-- Este div es para el formulario de ingreso de datos -->    
                <div class="panel-body" id="formularioregistros">
                  <form name="formulario" id="formulario" method="POST">
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <h4 class="box-title">Datos del Establecimiento</h4>
                      <hr/ style="margin-top:0px; margin-bottom: 0px;">
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 has-error">
                      <label>Codigo Renipress</label>
                      <input type="hidden" name="idEstabl" id="idEstabl" value="">
                      <input type="hidden" name="rolUsuario" id="rolUsuario" value="<?php echo $rolUsuario; ?>">
                      <input type="hidden" name="idDisaSel" id="idDisaSel" value="<?php echo $idDisa; ?>">
                      <input type="hidden" name="idRedSel" id="idRedSel" value="<?php echo $idRed; ?>">
                      <input type="hidden" name="idMredSel" id="idMredSel" value="<?php echo $idMred; ?>">
                      <input type="hidden" name="idEstablSel" id="idEstablSel" value="<?php echo $idEstabl; ?>">
                      <input type="hidden" name="idNiv" id="idNiv" value="<?php echo $idNiv; ?>">
                      <input type="number" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" name="codEstab" id="codEstab" placeholder="Renipress" required>
                    </div>

                    <div class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-12 has-error">
                      <label>Nombre del EeSs</label>
                      <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" name="descEstab" id="descEstab" maxlength="45" placeholder="Nombre del EeSs" required>
                    </div>

                    <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                      <label>Tipo</label>
                      <select name="idTipEstab" id="idTipEstab" class="form-control selectpicker" data-live-search="true"> 
                      </select>
                    </div>

                    <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                      <label>Categoria</label>
                      <select name="idCateg" id="idCateg" class="form-control selectpicker" data-live-search="true"> 
                      </select>
                    </div>

                    &nbsp
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <h4 class="box-title">Ubicacion Administrativa</h4>
                      <hr/ style="margin-top:0px; margin-bottom: 0px;">
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                      <label>Disa/Geresa</label>
                      <select name="iddisaUsu" id="iddisaUsu" class="form-control selectpicker" data-live-search="true" onchange='cargarRedesUsu(this.value);'>
                        <option value="00">Seleccionar Geresa</option>
                      </select>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                      <label>Red</label></br>
                      <select name="idredUsu" id="idredUsu" class="form-control selectpicker" data-live-search="true" onchange='cargarMicroRedesUsu(this.value);'>
                        <option value="0000">Seleccionar Red</option>
                      </select>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                      <label>MicroRed</label>
                      <select name="idmredUsu" id="idmredUsu" class="form-control selectpicker" data-live-search="true">
                        <option value="000000">Seleccionar MicroRed</option>
                      </select>
                    </div>

                    &nbsp
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <h4 class="box-title">Ubicacion Geográfica</h4>
                      <hr/ style="margin-top:0px; margin-bottom: 0px;">
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                      <label>Departamento</label>
                      <select name="iddpto" id="iddpto" class="form-control selectpicker" data-live-search="true" onchange='cargarProv(this.value);'>
                        <option value="00">-Seleccionar Departamento-</option>
                      </select>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                      <label>Provincia</label></br>
                      <select name="idprov" id="idprov" class="form-control selectpicker" data-live-search="true" onchange='cargarDistr(this.value);'>
                        <option value="0000">-Seleccionar Provincia-</option>
                      </select>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                      <label>Distrito</label>
                      <select name="iddist" id="iddist" class="form-control selectpicker" data-live-search="true">
                        <option value="000000">-Seleccionar Distrito-</option>
                      </select>
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

<?php
require 'footer.php';
?>

<script type="text/javascript" src="scripts/admestablecimientos.js"></script>