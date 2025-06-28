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
        <section class="content-header">
          <h1>
           Nivel Administrativo - Listado de DISAS/GERESAS
           </h1>
        </section>
        
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <div class="box box-info">

                <!-- Este div es para el listado de Disas -->
                <div class="panel-body table-responsive" id="listageresa">
                  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                      <th width="10%">Opciones</th>
                      <th>Disa/Geresa</th>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>

                <!-- Este div es para el listado de las Redes de la DISA seleccionada -->
                <div class="panel-body table-responsive" id="listaredes">
                  <h3 class="box-title"> Lista de Redes - <span id="lblGeresa"> </span> </h3>
                  <div class="panel-body">

                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <hr/ style="margin-top:0px;">
                    </div>

                    <table id="tbllistared" class="table table-striped table-bordered table-condensed table-hover">
                      <thead>
                        <th width="10%">Opciones</th>
                        <th>Red</th>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>

                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <!--Este boton va a llamar a la funcion cancelarform() de javascript -->
                      <button class="btn btn-danger" onclick="mostrarform(1)" type="button"><i class="fa fa-arrow-circle-left"></i> Volver</button>
                    </div>

                  </div>
                </div>

                <!-- Este div es para el listado de las Redes de la DISA seleccionada -->
                <div class="panel-body table-responsive" id="listamicroredes">
                  <h3 class="box-title"> Lista de Micro Redes - <span id="lblRed"> </span> </h3>
                  <div class="panel-body">

                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <hr/ style="margin-top:0px;">
                    </div>
                    
                    <table id="tbllistamicrored" class="table table-striped table-bordered table-condensed table-hover">
                      <thead>
                        <th width="10%">Opciones</th>
                        <th>Micro Red</th>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>

                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <!--Este boton va a llamar a la funcion cancelarform() de javascript -->
                      <button class="btn btn-danger" onclick="mostrarform(2)" type="button"><i class="fa fa-arrow-circle-left"></i> Volver</button>
                    </div>

                  </div>
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

<script type="text/javascript" src="scripts/admdisageresa.js"></script>