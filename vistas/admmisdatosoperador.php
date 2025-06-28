<?php
session_start();
if (!isset($_SESSION["rolUsuario"]))
{
  header("Location: login.html");
}else{
  require 'header.php';
  require_once "../config/funciones.php";
  $idUsu=$_SESSION['idUsr'];

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
         Administrar mis Datos
         </h1>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-info">
              <div class="box-header">

                <!-- Este div es para el formulario de ingreso de datos -->    
                <div class="panel-body" id="formularioregistros">
                  <form name="formulario" id="formulario" method="POST">
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <h4 class="box-title">Detalles</h4>
                      <hr/ style="margin-top:0px; margin-bottom: 0px;">
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 has-error">
                      <label>Apellido Paterno</label>
                      <input type="hidden" name="idUsu" id="idUsu" value="<?php echo $idUsu; ?>">
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
                      <select name="idProf" id="idProf" class="form-control selectpicker">                           
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

                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <button class="btn btn-primary" type="submit" id="btnGuardar" name="btnGuardar"><i class="fa fa-save"></i>Guardar</button>
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

<script type="text/javascript" src="scripts/admmisdatosoperador.js"></script>