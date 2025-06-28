<?php
session_start();
require 'header.php';
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Página en Construcción
      </h1>
<!--      <ol class="breadcrumb">
        <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Página en Construcción</li>
      </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="error-page">
        <h2 class="headline text-primary">404</h2>
          <div class="error-content">
            <h3>
              <i class="fa fa-warning text-primary"></i>
              Oooops Página En Construcción.
            </h3>
            <p>
              Ingresa al menú lateral y allí podrás encontrar las páginas
              disponibles.
            </p>
          </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
require 'footer.php';
?>
