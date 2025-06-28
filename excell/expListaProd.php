<?php
session_start('miski');
if (empty($_SESSION['trabid'])) {
  header("Location: ../vistas/login.html");
  die();
}
require_once "../modelos/AdmProductos.php";

$nomProd=isset($_POST["nomProd"]) ? limpiarCadena($_POST["nomProd"]):"";
$estado=isset($_POST["estado"]) ? limpiarCadena($_POST["estado"]):"";
$usuarioLogeado=$_SESSION['usernom'];

//La URL del artículo para leer archivos Excel con PHP es esta:

//http://www.parentesys.es/Leer-archivos-EXCEL-en-PHP-32811

/** Incluir la libreria PHPExcel */
require_once '../PHPEXcel18/Classes/PHPExcel.php';

// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Richard RP")
							 ->setLastModifiedBy("Richard RP")
							 ->setTitle("Lista de Precios")
							 ->setSubject("Lista de Precios - Sistema SIDUR")
							 ->setDescription("Lista de Precios Exportados del Sistema.")
							 ->setKeywords("Lista")
							 ->setCategory("Listado");

// Add some data
date_default_timezone_set('America/Lima');
$date=new DateTime();
$result = $date->format('Y-m-d-H-i-s');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Listado de Precios de Productos')
            ->setCellValue('A2', 'Fecha :'.$result)
            ->setCellValue('D2', 'Usuario que genera listado: '.$usuarioLogeado);

// Miscellaneous glyphs, UTF-8
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'Codigo')
            ->setCellValue('B4', 'Un. Medida')
            ->setCellValue('C4', 'Nombre del Producto')
            ->setCellValue('D4', 'Pr. Mayor')
            ->setCellValue('E4', 'Pr. Unidad')
            ->setCellValue('F4', 'Pr. Especial')
            ->setCellValue('G4', 'Fecha Ult Act.');

$objPHPExcel->getActiveSheet()->mergeCells('A1:G1');

$style = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));

$objPHPExcel->getActiveSheet()->getStyle("A1:G1")->applyFromArray($style);


$objPHPExcel->getActiveSheet()->getStyle("A4:G4")->getFont()->setBold(true);
//$objPHPExcel->getActiveSheet()->setCellValue('A8',"Hello\nWorld");
//$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);

//$value = "-ValueA\n-Value B\n-Value C";
//$objPHPExcel->getActiveSheet()->setCellValue('A10', $value);
//$objPHPExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(-1);
//$objPHPExcel->getActiveSheet()->getStyle('B4')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(80);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
$objPHPExcel->getActiveSheet()->getStyle('D4')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
$objPHPExcel->getActiveSheet()->getStyle('E4')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
$objPHPExcel->getActiveSheet()->getStyle('F4')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel->getActiveSheet()->getStyle('G4')->getAlignment()->setWrapText(true);



//$objPHPExcel->getActiveSheet()->getStyle('A10')->setQuotePrefix(true);

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('listado');

// Aqui ponemos el codigo para imprimir los datos que se vana exportar

$admProd=new AdmProductos();
$rspta=$admProd->listarPreciosProd($nomProd,$estado);
//Comenzamos a pasar los registros linea a linea
$linea=5;
$cLinea='';
while($reg=$rspta->fetch_object())
{
  $idPres=$reg->idPres;
  $unMed=$reg->unidadMedida;
  $nomProd=$reg->nomProducto;
  $prVtaMay=$reg->prVtaMay;
  $prVtaUnit=$reg->prVtaUnit;
  $prVtaEsp=$reg->prVtaEsp;
  $fuac=$reg->fUltAct;

  $cLinea=(string)$linea;
  $colA='A'.$cLinea;
  $objPHPExcel->getActiveSheet()->setCellValue($colA,$idPres);
  $colB='B'.$cLinea;
  $objPHPExcel->getActiveSheet()->setCellValue($colB,$unMed);
  $colC='C'.$cLinea;
  $objPHPExcel->getActiveSheet()->setCellValue($colC,$nomProd);
  $colD='D'.$cLinea;
  $objPHPExcel->getActiveSheet()->setCellValue($colD,$prVtaMay);
  $colE='E'.$cLinea;
  $objPHPExcel->getActiveSheet()->setCellValue($colE,$prVtaUnit);
  $colF='F'.$cLinea;
  $objPHPExcel->getActiveSheet()->setCellValue($colF,$prVtaEsp);
  $colG='G'.$cLinea;
  $objPHPExcel->getActiveSheet()->setCellValue($colG,$fuac);
  $linea++;
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter->save(str_replace('.php', '.xlsx', _FILE_));
$objWriter->save('c:/listaprecios.xlsx');
echo 'El archivo fue grabado en C:';
//echo 'Completado';

?>
