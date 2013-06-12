<?php
  session_start();
  
  include("../conexion.php");
  $db = new MySQL();
  $idatencion = $_GET['idatencion'];
  $cadena = "-1-1--1-1-";
  $vector = explode("-", $cadena);
  
  if ($vector[0] == "")
      echo "No Trabaja";
  
  echo "Datos:".$vector[1]; 
  
?>

