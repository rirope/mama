<?php
session_start();
if (empty($_SESSION['idUsr'])) {
  header("Location: ../vistas/login.html");
  die();
}

$idNino=$_GET["idNino"];
$region=$_GET["region"];
//Incluímos a la clase PDF_MC_Table
require('PDF_MC_Table.php');
//Establecemos zona horaria por defecto
date_default_timezone_set('America/Lima');

$pdf=new PDF_MC_Table();
 
//Agregamos la primera página al documento pdf
$pdf->AddPage();
 
//Seteamos el inicio del margen superior en 25 pixeles 
$y_axis_initial = 25;
 
//Seteamos el tipo de letra y creamos el título de la página. No es un encabezado no se repetirá
$pdf->SetFont('Arial','B',8);
$pdf->Ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,6,utf8_decode('Región - ').$region,0,0,'C');
$pdf->Ln(5);
$pdf->Cell(190,6,utf8_decode('Autorización de Inscripcion al PROGRAMA MAMA - Niño/a'),0,0,'C'); 
$pdf->Ln(10);
 
$pdf->SetFont('Arial','',11);
//Creamos las celdas para los títulos de cada columna y le asignamos un fondo gris y el tipo de letra
//$pdf->SetFillColor(255,255,255); 

//Obtenemos los datos de la gestante
require_once "../modelos/RegistroNinos.php";
$datosNino=new RegistroNinos();
$rspta = $datosNino->obtDatosNinos($idNino);

$establ=$rspta['establ'];
//Datos de la mamá
$nombreMama=$rspta['nombres'].' '.$rspta['apePat'].' '.$rspta['apeMat'];
$nroDiMa=$rspta['nroDiMa'];
$fechaNacMa=$rspta['fechaNacMama'];
$grInstrMa=$rspta['grInstrMama'];
$celMa=$rspta['celMa'];
$celAcom=$rspta['celAcomp'];
$progSocMa=empty($rspta['progSocMa']) ? 'No Tiene' : $rspta['progSocMa'];
$msgVozMa=empty($rspta['msgVozMa']) ? 'No Recibe' : $rspta['msgVozMa'];

//Datos del niño
$nroDiNino=$rspta['nroDiNino'];
$nhFam=empty($rspta['nhFam']) ? 'No Tiene' : $rspta['nhFam'];
$nhNino=empty($rspta['nhNino']) ? 'No Tiene' : $rspta['nhNino'];
$fecNacNino=$rspta['fecNacNino'];
$fecAtcNino=$rspta['fecAtcNino'];
$progSocNi=empty($rspta['progSocNi']) ? 'No Tiene' : $rspta['progSocNi'];

$texto1=utf8_decode('YO, '.$nombreMama.' identificada con DNI Nº '.$nroDiMa.', expresamente manifiesto haber sido informada sobre el PROGRAMA MAMA, programa de consejería materna a través de mensajes de texto, por lo que solicito la inscripción al programa para poder recibir los  mensajes  sobre el cuidado de la salud y las llamadas de monitoreo sin costo alguno a mi persona.');
$pdf->MultiCell(190,5,$texto1);
$pdf->Ln(5);
$texto2=utf8_decode('La siguiente informacón será ingresada por el profesional de la salud que hará efectiva la inscripción:');
$pdf->MultiCell(190,5,$texto2);
$texto3=utf8_decode('Establecimiento de Salud: '.$establ.', fecha de atención: '.$fecAtcNino);
$pdf->MultiCell(190,5,$texto3);
$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(50,6,utf8_decode('Datos de la Madre'),'B',0,'C');
$pdf->Cell(30,6,'','B',0,'L');
$pdf->Cell(30,6,'',0,0,'L');
$pdf->Cell(50,6,utf8_decode('Datos del Niño/a'),'B',0,'C');
$pdf->Cell(30,6,'','B',0,'L');
$pdf->Ln(6);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,6,utf8_decode('Fecha Nacimiento'),0,0,'L'); 
$pdf->Cell(30,6,utf8_decode($fechaNacMa),'B',0,'L'); 
$pdf->Cell(30,6,'',0,0,'L');
$pdf->Cell(50,6,utf8_decode('Historia Familiar'),0,0,'L');
$pdf->Cell(30,6,utf8_decode($nhFam),'B',0,'L');

$pdf->Ln(6);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,6,utf8_decode('Grado Instrucción'),0,0,'L'); 
$pdf->Cell(30,6,utf8_decode($grInstrMa),'B',0,'L'); 
$pdf->Cell(30,6,'',0,0,'L');
$pdf->Cell(50,6,utf8_decode('Número Historia'),0,0,'L');
$pdf->Cell(30,6,utf8_decode($nhNino),'B',0,'L');

$pdf->Ln(6);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,6,utf8_decode('Nro. Celular Mamá'),0,0,'L'); 
$pdf->Cell(30,6,utf8_decode($celMa),'B',0,'L'); 
$pdf->Cell(30,6,'',0,0,'L');
$pdf->Cell(50,6,utf8_decode('Nro DNI'),0,0,'L');
$pdf->Cell(30,6,utf8_decode($nroDiNino),'B',0,'L');

$pdf->Ln(6);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,6,utf8_decode('Programa Social'),0,0,'L'); 
$pdf->Cell(30,6,utf8_decode($progSocMa),'B',0,'L'); 
$pdf->Cell(30,6,'',0,0,'L');
$pdf->Cell(50,6,utf8_decode('Fecha Nacimiento'),0,0,'L');
$pdf->Cell(30,6,utf8_decode($fecNacNino),'B',0,'L');

$pdf->Ln(6);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,6,utf8_decode('Mensaje Voz'),0,0,'L'); 
$pdf->Cell(30,6,utf8_decode($msgVozMa),'B',0,'L'); 
$pdf->Cell(30,6,'',0,0,'L');
$pdf->Cell(50,6,utf8_decode('Programa Social'),0,0,'L');
$pdf->Cell(30,6,utf8_decode($progSocNi),'B',0,'L');

$pdf->Ln(10);
$texto4=utf8_decode('Además indico el nombre de la persona de mi confianza a quien deseo que le lleguen los mensajes de texto del PROGRAMA MAMA.');
$pdf->MultiCell(190,5,$texto4);
$pdf->Cell(15,6,utf8_decode('Sr(a):'),0,0,'L'); 
$pdf->Cell(175,6,'','B',0,'L',0); 
$pdf->Ln(6);
$pdf->Cell(45,6,utf8_decode('con número de celular: '),0,0,'L'); 
$pdf->Cell(30,6,$celAcom,'B',0,'L',0); 
$pdf->Ln(50);

$pdf->Cell(100,6,'','B',0,'L',0); 
$pdf->Cell(20,6,'',0,0,'L',0); 
$pdf->Cell(40,6,'','B',0,'L',0); 
$pdf->Ln(6);
$pdf->Cell(100,6,'Firma Beneficiaria',0,0,'C',0); 
$pdf->Cell(20,6,'',0,0,'L',0); 
$pdf->Cell(40,6,'Huella Digital',0,0,'C',0); 
//Mostramos el documento pdf
$pdf->Output();

$datosGest->close();

?>
