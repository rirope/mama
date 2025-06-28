<?php

//session_start();

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sms MAMA | V3.1</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../public/css/font-awesome.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="../public/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../public/css/_all-skins.min.css">
    <link rel="apple-touch-icon" href="../public/img/apple-touch-icon.png">
    <link rel="shortcut icon" href="../public/img/favicon.ico">

    <!-- DATATABLES -->
    <link rel="stylesheet" type="text/css" href="../public/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../public/datatables/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../public/datatables/responsive.dataTables.min.css">

    <link rel="stylesheet" type="text/css" href="../public/css/bootstrap-select.min.css">
    
    <!-- Time picker -->
    <link rel="stylesheet" type="text/css" href="../public/plugins/timepicker/bootstrap-timepicker.min.css">
    <!-- Se utiliza para refrescar la pantalla cada 5 segundos
    <meta http-equiv="refresh" content="5" />
    -->
  </head>
  <body class="hold-transition skin-red sidebar-mini">
    <div class="wrapper">

      <header class="main-header">

        <!-- Logo -->
        <a href="#" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>MAMA</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Sms-MAMA</b></span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Navegación</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
              
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="../files/user.jpg" class="user-image" alt="User Image">

                  <span class="hidden-xs">Usuario: <?php echo $_SESSION['apePatUsr'].' '.$_SESSION['apeMatUsr'].' '.$_SESSION['nomUsr']; ?> </span>
                  <span class="hidden-xs">Nivel: <?php echo $_SESSION['rolUsuario'].' '.$_SESSION['nivelUsuario']; ?> </span>
                  <span class="hidden-xs">Lugar: <?php echo $_SESSION['origenUsuario']; ?> </span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="../files/user.jpg" class="img-circle" alt="User Image">
                    <p>
                      MAMA - Cuidando tu salud
                      <small>soportemama@gmail.com</small>
                    </p>
                  </li>
                  
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="../vistas/selecclugaracceso.php" class="btn btn-default btn-flat">Cambiar de Acceso</a>
                    </div>
                    
                    <div class="pull-right">
                      <a href="../ajax/login.php?op=cerrar_sesion" class="btn btn-default btn-flat">Cerrar</a>
                    </div>
                  </li>
                </ul>
              </li>
              
            </ul>
          </div>

        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">       
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header"></li>
            <li>
              <a href="escritorio.php">
                <i class="fa fa-tasks"></i> <span>DASHBOARD</span>
              </a>
            </li>
             <?php
                require_once '../modelos/AdmLogin.php';
                $AdmLogin = new AdmLogin();
                $AdmLogin->listarMenu();
                //echo $_SERVER['REMOTE_ADDR'];
             ?>  
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
