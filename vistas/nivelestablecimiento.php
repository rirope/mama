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
           Niveles de Establecimiento de Salud
           </h1>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box box-info">

                    <div class="box-header with-border">
                          <h1 class="box-title">Listado
                            <button class="btn btn-success" id="btnAgregar" onclick="mostrarform(true)">
                              <i class="fa fa-plus-circle"></i> Agregar Nivel</button>
                          </h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>

                    <!-- Este div es para el listado de registros -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                      <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                        <thead>
                          <th width="20%">Opciones</th>
                          <th>Nivel Establecimento</th>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                    
                    <!-- Este div es para el formulario de ingreso de datos -->    
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                      <form name="formulario" id="formulario" method="POST">

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Ingresar Nivel de Establecimiento:</label>
                          <input type="hidden" name="idwsdat01" id="idwsdat01">
                          <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" name="descripcion" id="descripcion" maxlength="50" placeholder="Nivel Establecimento" required>
                        </div>

                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>Guardar</button>
                          <!--Este boton va a llamar a la funcion cancelarform() de javascript -->
                          <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i>Cancelar</button>
                        </div>
                      
                      </form>
                      
                    </div>
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
<?php
require 'footer.php';
?>

<script type="text/javascript" src="scripts/nivelestablecimiento.js"></script>