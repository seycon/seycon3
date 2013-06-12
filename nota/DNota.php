<?php
	session_start();
	include_once('../conexion.php');  
	$db = new MySQL();
	
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: ../index.php");	
	}
	
	function filtro($cadena)
	{
	    return htmlspecialchars(strip_tags($cadena));
	} 

	if ($_GET['transaccion'] == "insertar") {
	  $fecha = filtro($db->GetFormatofecha($_GET['fecha'],'/'));  
	  $sql = "insert into nota(fecha,privado,titulo,contenido,idusuario,estado) values
	  ('$fecha','".filtro($_GET['privado'])."','".filtro($_GET['titulo'])."','"
	  .filtro($_GET['contenido'])."','$_SESSION[id_usuario]',1);";
	  $db->consulta($sql);
	  $idnota= $db->getMaxCampo('idnota','nota');
	   
	}
  
	if ($_GET['transaccion'] == "modificar") { 
	  $fecha = filtro($db->GetFormatofecha($_GET['fecha'],'/'));
	  $idnota = filtro($_GET['idnota']);
	  $sql = "UPDATE nota SET  fecha='$fecha', privado='".filtro($_GET['privado'])
	  ."', titulo='".filtro($_GET['titulo'])."', contenido='".filtro($_GET['contenido'])."', 
	  idusuario='$_SESSION[id_usuario]' WHERE idnota= '$idnota';";
	  $db->consulta($sql);	
	  
	}
?>