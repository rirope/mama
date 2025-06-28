<?php
// Obntenemos el id del usuario logeado
session_start();
if (!isset($_SESSION["rolUsuario"]))
{
  header("Location: login.html");
}else{
  $idUsr= $_SESSION['idUsr'];
  $apePatUsr=$_SESSION['apePatUsr'];
  $apeMatUsr=$_SESSION['apeMatUsr'];
  $nomUsr=$_SESSION['nomUsr'];
  $profUsr=$_SESSION['profUsr'];
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SMS - Mama V.3.1</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../public/css/font-awesome.css">
   
    <!-- Theme style -->
    <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../public/css/blue.css">

    <!-- DATATABLES -->
    <link rel="stylesheet" type="text/css" href="../public/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../public/datatables/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../public/datatables/responsive.dataTables.min.css">

  </head>

  <body style="background: linear-gradient(rgba(0,0,0,1), rgba(0,30,50,1));">
    <div class="content" style="justify-content: center; width: 40%;">
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <!-- Este div es para el Titulo del formulario -->
            <div class="box box-info">
              <div class="box-header" style="background: #F8F9F9;">
                <h1><p class="login-box-msg">Bienvenido Usuario</p></h1>
                <div class="form-group has-feedback table-responsive">
                  <input type="hidden" id="idUsr" name="idUsr" value="<?php echo $idUsr; ?>">
                  <table class="table table-striped table-bordered table-condensed table-hover" style="background: white;">
                    <thead style="background: black; color: white;">
                      <th style="text-align: center;">Datos</th>
                      <th style="text-align: center;">Detalles</th>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Apellidos</td>
                        <td id="apellidos" style="color:blue; font-size: 1.8rem;"><?php echo $apePatUsr. ' '.$apeMatUsr; ?></td>
                      </tr>
                      <tr>
                        <td>Nombres</td>
                        <td id="nombres" style="color:blue; font-size: 2rem;"><?php echo $nomUsr; ?></td>
                      </tr>
                      <tr>
                        <td>Profesión</td>
                        <td id="profesion" style="color:blue; font-size: 2rem;"><?php echo $profUsr; ?></td>
                      </tr>
                    </tbody>
                    
                  </table>
                </div>

                <div class="form-group has-feedback">
                  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                    <thead class="thead-dark">
                      <th scope="col" width="20%">Elegir</th>
                      <th scope="col">Lugar de Acceso</th>
                      <th scope="col">Tipo Usuario</th>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>

                
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- jQuery -->
    <script src="../public/js/jquery-3.1.1.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="../public/js/bootstrap.min.js"></script>
     <!-- Bootbox -->
    <script src="../public/js/bootbox.min.js"></script>
    <!--DATATABLES -->
    <script src="../public/datatables/jquery.dataTables.min.js"></script>
    <script src="../public/datatables/dataTables.buttons.min.js"></script>
    <script src="../public/datatables/buttons.html5.min.js"></script>
    <script src="../public/datatables/buttons.colVis.min.js"></script>
    <script src="../public/datatables/jszip.min.js"></script>
    <script src="../public/datatables/pdfmake.min.js"></script>
    <script src="../public/datatables/vfs_fonts.js"></script>

    <script type="text/javascript" src="scripts/selecclugaracceso.js"></script>
  </body>
</html> 
