<?php
	session_start();
	include("../conexion.php");
	$db = new MySQL();
	$tipo = $_GET['transaccion'];
	
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: ../index.php");	
	}
	
	if ($tipo == "consulta") {
		$sql = "select *from detallelibrodiario dl,plandecuenta p 
		where p.codigo=dl.idcuenta and p.idplandecuenta=$_GET[codigo];";
		$num = $db->getnumRow($sql);
		if ($num > 0) {
		  echo "si";
		} else {
		  echo "no";	
		}	
	}

?>