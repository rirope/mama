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
         Registro de Niños
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
                      <button class="btn btn-success" id="btnAgregar" onclick="buscarUsuariaMama()">
                        <i class="fa fa-plus-circle"></i> Agregar Niño</button>
                      <button class="btn btn-info" id="btnMostrarFormImportar" onclick="mostrarFormImportarNino()">
                        <i class="fa fa-recycle"></i> Importar Niño de Otro EeSs</button>
                    </h1>
                    <div class="box-tools pull-right">
                    </div>
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                   <label>Desde</label>
                   <input type="hidden" name="fechaHoy" id="fechaHoy" value="<?php echo date("Y-m-d"); ?>">
                   <input type="date" class="form-control" name="fechaIni" id="fechaIni" value="<?php echo PrimerDiaMes(); ?>">
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label>Hasta</label>
                    <input type="date" class="form-control" name="fechaFin" id="fechaFin" value="<?php echo date("Y-m-d"); ?>">
                  </div>

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Disa/Geresa</label>
                    <select name="iddisa" id="iddisa" class="form-control selectpicker" data-live-search="true" onchange='cargarRedes(this.value);'>
                      <option value="00">Todas las Geresas</option>
                    </select>
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
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

                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Establecimiento</label>
                    <select name="idestab" id="idestab" class="form-control selectpicker" data-live-search="true">
                      <option value="0">Todos los Establecimientos</option>
                    </select>
                  </div>

                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Estado</label>
                    <select name="estado" id="estado" class="form-control selectpicker" required="">
                      <option value="-Todos-">-Todos-</option>
                      <option value="Activos">Activos</option>
                      <option value="Inactivos">Inactivos</option>
                    </select>
                  </div>

                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>&nbsp</label></br>
                    <button class="btn btn-warning" onclick="listar()"><i class="fa fa-search"></i> Mostrar</button>
                  </div>

                  <table id="tbllistado" class="table table-sm table-striped table-bordered table-condensed table-hover" style="width:100%">
                    <thead>
                      <th>Opciones</th>
                      <th>Apellidos y Nombres Mamá</th>
                      <th>Documento Mamá</th>
                      <th>Nro Celular Mamá</th>
                      <th>Documento Niño</th>
                      <th>Fecha Nacimiento</th>
                      <th>Fecha Registro</th>
                      <th>Disa/Geresa</th>
                      <th>Establecimiento</th>
                      <th>Renipres</th>
                      <th>Quien Registró</th>
                      <th>Estado</th>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
                
                <!-- Este div es para el formulario de ingreso de datos -->    
                <div class="panel-body" id="formularioregistros">
                  <form name="formulario" id="formulario" method="POST">
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <h4 class="box-title">Datos de la Mamá del Niño</h4>
                      <hr/ style="margin-top:0px; margin-bottom: 0px;">
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label>Tipo Documento Identidad</label>
                      <input type="hidden" name="idNino" id="idNino" value="">
                      <input type="hidden" name="rolUsuario" id="rolUsuario" value="<?php echo $rolUsuario; ?>">
                      <input type="hidden" name="idDisaSel" id="idDisaSel" value="<?php echo $idDisa; ?>">
                      <input type="hidden" name="idRedSel" id="idRedSel" value="<?php echo $idRed; ?>">
                      <input type="hidden" name="idMredSel" id="idMredSel" value="<?php echo $idMred; ?>">
                      <input type="hidden" name="idEstablSel" id="idEstablSel" value="<?php echo $idEstabl; ?>">
                      <input type="hidden" name="idNiv" id="idNiv" value="<?php echo $idNiv; ?>">
                      <input type="hidden" name="nroCelPrincipal" id="nroCelPrincipal" value="">
                      <select name="tipoDocIdent" id="tipoDocIdent" class="form-control selectpicker" disabled>
                      </select>
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label>Nro Documento Identidad</label>
                      <input type="text" class="form-control" name="nroDocIdent" id="nroDocIdent" placeholder="Número de Documento" disabled>
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 has-error">
                      <label>Apellido Paterno</label>
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

                    <div id="fecNacGest" class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12 has-error">
                      <label>Fecha Nacimiento</label>
                      <input type="date" class="form-control" name="fechaNacMama" id="fechaNacMama" required>
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                      <label>Grado de Instruccion</label>
                      <select name="idGrInst" id="idGrInst" class="form-control selectpicker" data-live-search="true">                           
                      </select>                          
                    </div>

                    &nbsp
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <h4 class="box-title">Datos para Mensajes</h4>
                      <hr/ style="margin-top:0px; margin-bottom: 0px;">
                    </div>

                    <div id="nroCelGest" class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12 has-error">
                      <label>Nro Celular Mamá</label>
                      <input type="number" class="form-control" name="celularMadre" id="celularMadre" maxlength="9" placeholder="Celular Gestante" onkeypress="return validaCeluMama(event,9,'celularMadre');" required>
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                      <label>Nro Celular Acompañante</label>
                      <input type="number" class="form-control" name="celularAcomp" id="celularAcomp" maxlength="9" placeholder="Celular Acompañante" onkeypress="return soloNumeros(event,9,'celularAcomp');"><br>
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                      <label>Programa Social Mamá</label>
                      <select name="progSoc" id="progSoc" class="form-control selectpicker">
                      </select>
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                      <label>Mensaje de voz en</label>
                      <select name="msgVoz" id="msgVoz" class="form-control selectpicker">
                      </select>
                    </div>

                    &nbsp
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <h4 class="box-title">Datos del Niño</h4>
                      <hr/ style="margin-top:0px; margin-bottom: 0px;">
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label>Tipo Documento Identidad</label>
                      <select name="tipoDiNino" id="tipoDiNino" class="form-control selectpicker">
                      </select>
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 has-error">
                      <label>Nro Documento Identidad</label>
                      <input type="number" class="form-control" name="nroDiNino" id="nroDiNino" placeholder="Número de Documento del Niño" onkeypress="return soloNumeros(event,20,'nroDiNino');">
                      <input type="hidden" name="nroDiNinoAnt" id="nroDiNinoAnt">
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                      <label>H.C. Familiar</label>
                      <input type="text" class="form-control" name="hClFam" id="hClFam" maxlength="20" placeholder="Historia Clinica Familiar">
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                      <label>H.C. del Niño</label>
                      <input type="text" class="form-control" name="hClNino" id="hClNino" maxlength="20" placeholder="Historia Clinica del Niño">
                    </div>

                    <div id="fecNacNino" class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12 has-error">
                      <label>Fecha Nacimiento del Niño</label>
                      <input type="date" class="form-control" name="fechaNacNino" id="fechaNacNino" required>
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                      <label>Fecha de Atención del Niño</label>
                      <input type="date" class="form-control" name="fechaAtcNino" id="fechaAtcNino" value="<?php echo date("Y-m-d"); ?>">
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                      <label>Programa Social Niño</label>
                      <select name="progSocNino" id="progSocNino" class="form-control selectpicker">
                      </select>
                    </div>


                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>Guardar</button>
                      <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i>Cancelar</button>
                      <a target="_blank" href="" id="formatoNino"> <button type="button" class="btn btn-info"><i class="fa fa-print"></i> Imprimir</button></a>
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

  <!-- Modal para buscar los datos de la mamá del niño -->
  <div id="buscarUsuaria" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

          <!--=====================================
          CABEZA DEL MODAL
          ======================================-->
          <div class="modal-header" style="background:#3c8dbc; color:white">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Buscar Datos de la Mama del Niño</h4>
          </div>
          <!--=====================================
          CUERPO DEL MODAL
          ======================================-->
          <div class="modal-body">
            <div class="box-body">

              <!-- BUSCAMOS EL NOMBRE DE LA USUARIA-->
              <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label>Tipo Documento Identidad</label>
                <select name="tipoDi" id="tipoDi" class="form-control selectpicker" onchange="validaTipoDoc()">
                </select>
              </div>

              <div id="nroDoc" class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-12">
                <label>Nro Documento Identidad</label>
                <input type="number" class="form-control" name="nroDi" id="nroDi" placeholder="Número de Documento" onkeypress="return soloNumeros(event,20,'nroDi');" required>
              </div>

              <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12 pull-right">
                <label>&nbsp</label></br>
                <button class="btn btn-warning" type="button" name="buscarDatosUsuaria" id="buscarDatosUsuaria"><i class="fa fa-search"></i>Buscar</button>
              </div>

              <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12 pull-right">
                <label>&nbsp</label></br>
                <button class="btn btn-warning" type="button" data-dismiss="modal" name="btnNvaMama" id="btnNvaMama" onclick="nuevaMama(tipoDi.value,nroDi.value)"><i class="fa fa-plus-circle"></i> Agregar Mamá</button>
              </div>


              <!-- MOSTRAMOS LA LISTA DE RESULTADOS-->
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive" id="listaResultBusq">
                  <table id="tblListaGest" class="table table-striped table-bordered table-condensed table-hover" style="width:100%">
                    <thead>
                        <th>Seleccionar</th>
                        <th>Apellidos y Nombres</th>
                        <th>Documento Identidad</th>
                        <th>Celular</th>
                        <th>Fecha Nacimiento</th>
                        <th>Registrada en</th>
                    </thead>
                    <tbody id="listaUsu">
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div id="msgNoEncontrada" class="callout callout-danger">
              <h4>La Mamá del Niño NO está registrada en la Base de Datos, regístrela si desea.</h4>
            </div>

          </div>

          <!--=====================================
          PIE DEL MODAL
          ======================================-->
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>
          </div>

      </div>
    </div>
  </div>
  <!-- Fin modal -->

  <!-- Modal para importar datos de un niño de otro eess buscado por el dni de la madre -->
  <div id="importarNino" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

          <!--=====================================
          CABEZA DEL MODAL
          ======================================-->
          <div class="modal-header" style="background:#3c8dbc; color:white">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Importar Niño</h4>
          </div>
          <!--=====================================
          CUERPO DEL MODAL
          ======================================-->
          <div class="modal-body">
            <div class="box-body">

              <!-- BUSCAMOS EL NOMBRE DE LA USUARIA-->
              <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label>Tipo Documento Identidad</label>
                <select name="tipoDiImp" id="tipoDiImp" class="form-control selectpicker" onchange="validaTipoDoc()" disabled>
                </select>
              </div>

              <div id="nroDocImp" class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-12">
                <label>Nro Documento Identidad de la Madre</label>
                <input type="number" class="form-control" name="nroDiImp" id="nroDiImp" placeholder="Número de Documento" onkeypress="return soloNumeros(event,20,'nroDiImp');" required>
              </div>

              <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12 pull-right">
                <label>&nbsp</label></br>
                <button class="btn btn-warning" type="button" name="buscarDatosMamaNino" id="buscarDatosMamaNino"><i class="fa fa-search"></i>Buscar</button>
              </div>

              <!-- MOSTRAMOS LA LISTA DE RESULTADOS-->
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive" id="listaImportarNino">
                  <table id="tblListaImportarNino" class="table table-striped table-bordered table-condensed table-hover" style="width:100%">
                    <thead>
                        <th>Opcion</th>
                        <th>Nombres de la Madre</th>
                        <th>Documento Identidad</th>
                        <th>Celular</th>
                        <th>Fecha Nacimiento Niño</th>
                        <th>Nro DNI Niño</th>
                        <th>Fecha Registro</th>
                        <th>Nro Dias Transcurridos</th>
                        <th>Establecimiento Origen</th>
                    </thead>
                    <tbody id="listaNino">
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div id="msgMamaNinoNoEncontrada" class="callout callout-danger">
              <h4><span id='msgRespuesta'>La Mamá del Niño que desea importar NO está registrada en la Base de Datos, verifique por favor.</span></h4>
            </div>

          </div>

          <!--=====================================
          PIE DEL MODAL
          ======================================-->
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>
          </div>

      </div>
    </div>
  </div>
  <!-- Fin modal -->



<?php
require 'footer.php';
?>

<script type="text/javascript" src="scripts/registroninos.js"></script>