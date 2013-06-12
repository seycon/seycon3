<?php
  session_start();
  include('conexion.php');
  if (!isset($_SESSION['softLogeoadmin'])){
   header("Location: index.php");	
  }
  $db = new MySQL();	 

  $estructura = $_SESSION['estructura'];
  $mod = $_GET['mod'];
  $option = $_GET['opt'];
  $menus = $estructura[$mod];
   
  $privilegios = $db->getOpciones($menus, $option);  
  if (count($privilegios) <= 0) {
	  header("Location: cerrar.php");
  }   
  header("Location: ".$privilegios[0]["Enlace"]);
?>