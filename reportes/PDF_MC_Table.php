<?php
require('../fpdf181/fpdf.php');
require_once "../config/global.php";
class PDF_MC_Table extends FPDF
{
var $widths;
var $aligns;

function Footer(){
  $this->SetY(-15);
  $this->SetFont('Arial','I',8);
  $this->Cell(50,10,utf8_decode(PRO_NOMBRE),'T',0,'L');
  $this->Cell(141,10,utf8_decode('Página ').$this->PageNo(),'T',0,'R');
}
 
function Header(){
 //Define tipo de letra a usar, Arial, Negrita, 15
 $this->SetFont('Arial','B',9);
 /* Líneas paralelas
  * Line(x1,y1,x2,y2)
  * El origen es la esquina superior izquierda
  * Cambien los parámetros y chequen las posiciones
  * */
 //$this->Line(10,10,198,10);
 $this->Line(10,18.5,201,18.5);
 /* Explicaré el primer Cell() (Los siguientes son similares)
  * 30 : de ancho
  * 25 : de alto
  * ' ' : sin texto
  * 0 : sin borde
  * 0 : Lo siguiente en el código va a la derecha (en este caso la segunda celda)
  * 'C' : Texto Centrado
  * $this->Image('images/logo.png', 152,12, 19) Método para insertar imagen
  *     'images/logo.png' : ruta de la imagen
  *         152 : posición X (recordar que el origen es la esquina superior izquierda)
  *         12 : posición Y
  *     19 : Ancho de la imagen <span class="wp-smiley emoji emoji-wordpress" title="(w)">(w)</span>
  *     Nota: Al no especificar el alto de la imagen (h), éste se calcula automáticamente
  * */
 //$this->Cell(30,25,'',0,0,'C',$this->Image('images/logo.png', 152,12, 19));
 $this->Cell(191,10,'Fecha:'.date('d/m/Y H:ia'),0,0,'R', $this->Image('../logo-mama.png',10,5,20));
 //$this->Cell(40,25,'',0,0,'C',$this->Image('images/logoDerecha.png', 175, 12, 19));
 //Se da un salto de línea de 15
 $this->Ln(10);
}

function SetWidths($w)
{
	//Set the array of column widths
	$this->widths=$w;
}

function SetAligns($a)
{
	//Set the array of column alignments
	$this->aligns=$a;
}

function Row($data)
{
	//Calculate the height of the row
	$nb=0;
	for($i=0;$i<count($data);$i++)
		$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	$h=5*$nb;
	//Issue a page break first if needed
	$this->CheckPageBreak($h);
	//Draw the cells of the row
	for($i=0;$i<count($data);$i++)
	{
		$w=$this->widths[$i];
		$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
		//Save the current position
		$x=$this->GetX();
		$y=$this->GetY();
		//Draw the border
		//$this->Rect($x,$y,$w,$h);
		//Print the text
		$this->MultiCell($w,5,$data[$i],0,$a);
		//Put the position to the right of the cell
		$this->SetXY($x+$w,$y);
	}
	//Go to the next line
	$this->Ln($h);
}

function CheckPageBreak($h)
{
	//If the height h would cause an overflow, add a new page immediately
	if($this->GetY()+$h>$this->PageBreakTrigger)
		$this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
	//Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	return $nl;
}
}
?>
