<?php


function volcarFecha($fecha){
 $fec = explode("/", $fecha);	
 return $fec[2]."/".$fec[1]."/".$fec[0];
}

function restaurarFecha($fecha){
  $fec = explode("-", $fecha);	
 return $fec[2]."/".$fec[1]."/".$fec[0];	
}

?>

