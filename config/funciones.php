<?php 
function PrimerDiaMes() {
 // Funcion que devuelve el primer dia del mes en formato: AAAA-MM-DD
 $month = date('m');
 $year = date('Y');
 return date('Y-m-d', mktime(0,0,0, $month, 1, $year));
}

function UltimoDiaMes() { 
 // Funcion que devuelve el ultimo dia del mes en formato: AAAA-MM-DD
 $month = date('m');
 $year = date('Y');
 $day = date("d", mktime(0,0,0, $month+1, 0, $year));
 
 return date('Y-m-d', mktime(0,0,0, $month, $day, $year));
}

function edad($fechaNac) {
  // Funcion que calcula la edad basado en la fecha de nacimiento 
  $tiempo = strtotime($fechaNac); 
  $ahora = time(); 
  $edad = ($ahora-$tiempo)/(60*60*24*365.25); 
  $edad = floor($edad); 
  return $edad; 
} 

?>