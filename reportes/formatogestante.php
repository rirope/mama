<?php
session_start();
if (empty($_SESSION['idUsr'])) {
  header("Location: ../vistas/login.html");
  die();
}

$idGest=$_GET["idGest"];
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
$pdf->Cell(190,6,utf8_decode('Autorización de Inscripcion al PROGRAMA MAMA - Gestante'),0,0,'C'); 
$pdf->Ln(10);
 
$pdf->SetFont('Arial','',11);
//Creamos las celdas para los títulos de cada columna y le asignamos un fondo gris y el tipo de letra
//$pdf->SetFillColor(255,255,255); 

//Obtenemos los datos de la gestante
require_once "../modelos/RegistroGestantes.php";
$datosGest=new RegistroGestantes();
$rspta = $datosGest->obtDatosGest($idGest);
$nombreGest=$rspta['nombres'].' '.$rspta['apePat'].' '.$rspta['apeMat'];
$nroDi=$rspta['nroDi'];
$establ=$rspta['establ'];
$fechaAtc=$rspta['fechaAtc'];
$hcf=empty($rspta['nhf']) ? 'No Tiene' : $rspta['nhf'];
$nh=$rspta['nh'];
$fechaNac=$rspta['fechaNacGest'];
$celGest=$rspta['celGest'];
$celAcomp=$rspta['celAcomp'];
$grInstr=$rspta['grInstr'];
$progSoc=empty($rspta['progSoc']) ? 'No Tiene' : $rspta['progSoc'];
$msgVoz=empty($rspta['msgVoz']) ? 'No Recibe' : $rspta['msgVoz'];

$texto1=utf8_decode('YO, '.$nombreGest.' identificada con DNI Nº '.$nroDi.', expresamente manifiesto haber sido informada sobre el PROGRAMA MAMA, programa de consejería materna a través de mensajes de texto, por lo que solicito la inscripción al programa para poder recibir los  mensajes  sobre el cuidado de la salud y las llamadas de monitoreo sin costo alguno a mi persona.');
$pdf->MultiCell(190,5,$texto1);
$pdf->Ln(5);
$texto2=utf8_decode('La siguiente informacón será ingresada por el profesional de la salud que hará efectiva la inscripción:');
$pdf->MultiCell(190,5,$texto2);
$texto3=utf8_decode('Establecimiento de Salud: '.$establ.', fecha de atención: '.$fechaAtc);
$pdf->MultiCell(190,5,$texto3);
$pdf->Ln(5);
$pdf->Cell(60,6,utf8_decode('Historia Clinica Familiar'),0,0,'L'); 
$pdf->Cell(50,6,utf8_decode($hcf),'B',0,'L',0); 
$pdf->Ln(6);
$pdf->Cell(60,6,utf8_decode('Historia Clinica'),0,0,'L'); 
$pdf->Cell(50,6,utf8_decode($nh),'B',0,'L'); 
$pdf->Ln(6);
$pdf->Cell(60,6,utf8_decode('Nùmero DNI'),0,0,'L'); 
$pdf->Cell(50,6,utf8_decode($nroDi),'B',0,'L'); 
$pdf->Ln(6);
$pdf->Cell(60,6,utf8_decode('Fecha Nacimiento'),0,0,'L'); 
$pdf->Cell(50,6,utf8_decode($fechaNac),'B',0,'L'); 
$pdf->Ln(6);
$pdf->Cell(60,6,utf8_decode('Numero Celular'),0,0,'L'); 
$pdf->Cell(50,6,utf8_decode($celGest),'B',0,'L'); 
$pdf->Ln(6);
$pdf->Cell(60,6,utf8_decode('Grado de Instrucción'),0,0,'L'); 
$pdf->Cell(50,6,utf8_decode($grInstr),'B',0,'L');
$pdf->Ln(6);
$pdf->Cell(60,6,utf8_decode('Programa Social'),0,0,'L'); 
$pdf->Cell(50,6,utf8_decode($progSoc),'B',0,'L');
$pdf->Ln(6);
$pdf->Cell(60,6,utf8_decode('Mensaje de Voz'),0,0,'L'); 
$pdf->Cell(50,6,utf8_decode($msgVoz),'B',0,'L');


$pdf->Ln(10);
$texto4=utf8_decode('Además indico el nombre de la persona de mi confianza a quien deseo que le lleguen los mensajes de texto del PROGRAMA MAMA.');
$pdf->MultiCell(190,5,$texto4);
$pdf->Cell(15,6,utf8_decode('Sr(a):'),0,0,'L'); 
$pdf->Cell(175,6,'','B',0,'L',0); 
$pdf->Ln(6);
$pdf->Cell(45,6,utf8_decode('con número de celular: '),0,0,'L'); 
$pdf->Cell(30,6,$celAcomp,'B',0,'L',0); 
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
