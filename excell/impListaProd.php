<?php
session_start('miski');
if (empty($_SESSION['trabid'])) {
  header("Location: ../vistas/login.html");
  die();
}

$nomArch="c:/listaprecios.xlsx";
// Verificamos que exista el archivo 'c:/archivo.xlsx' 
if (file_exists ($nomArch))
{
  require_once "../modelos/AdmProductos.php";

  /** Incluir la libreria PHPExcel */
  require_once '../PHPEXcel18/Classes/PHPExcel.php';

  // Crea un nuevo objeto PHPExcel
  $objPHPExcel = new PHPExcel();

  $inputFileType = PHPExcel_IOFactory::identify($nomArch);
  $objReader = PHPExcel_IOFactory::createReader($inputFileType);
  $objPHPExcel = $objReader->load($nomArch);

  // Asignar hoja de excel activa
  $objPHPExcel->setActiveSheetIndex(0);

  // Llenamos un arreglo con los datos del archivo xlsx
  $linea=5; //line inicial en la cual empezara a realizar el barrido de datos
  $param=0;
  $numRegSub=0;

  // Creamos el objeto al modelo para actualizar los datos de los precios
  $admProd=new AdmProductos();

  //mientras el parametro siga en 0 (iniciado antes) que quiere decir que no ha encontrado un NULL entonces siga metiendo datos
  while($param==0) 
  {
    $idPres=$objPHPExcel->getActiveSheet()->getCell('A'.$linea)->getCalculatedValue();
    $prVtaMay=$objPHPExcel->getActiveSheet()->getCell('D'.$linea)->getCalculatedValue();
    $prVtaUnit=$objPHPExcel->getActiveSheet()->getCell('E'.$linea)->getCalculatedValue();
    $prVtaEsp=$objPHPExcel->getActiveSheet()->getCell('F'.$linea)->getCalculatedValue();
    
    $rspta=$admProd->actualizarPreciosProd($idPres,$prVtaMay,$prVtaUnit,$prVtaEsp);

    //pregunto que si ha encontrado un valor null en una columna inicie un parametro en 1 que indicaria el fin del ciclo while
    if($objPHPExcel->getActiveSheet()->getCell('A'.$linea)->getCalculatedValue()==NULL OR !$rspta) 
    {
      //para detener el ciclo cuando haya encontrado un valor NULL
      $param=1; 
    }
    $linea++;
    $numRegSub=$numRegSub+1;
  }
  $totalActualizados=$numRegSub-1; 
  echo "- Total de Precios de Productos actualizados: $totalActualizados.";

}else{
  echo 'El archivo listaprecios.xlsx NO SE encuentra en C:';
}

?>
