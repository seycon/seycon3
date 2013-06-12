<?php
    session_start(); 
	// $_SESSION['BDname'] = "bdkiwis";
	$_SESSION['BDname'] = "jorge_bdinsersprueba";
	//$_SESSION['BDname'] = "jorge_bdinsers";
	include("../conexion.php");
	$db = new MySQL();
	
	$idusuario = $_GET['usuario'];
	$sql = "select u.idusuario,u.idtrabajador,u.tipo,left(s.nombrecomercial,18)as 'sucursal',s.idsucursal" 
	    ." from usuariorestaurante u,sucursal s where s.idsucursal=u.idsucursal and u.idusuario=$idusuario";
	$data = $db->arrayConsulta($sql);
	if ($data['tipo'] == "fijo") {
	    $sql = "select left(concat(nombre,' ',apellido) ,15)as 'nombre' from trabajador "
		    ."where idtrabajador='$data[idtrabajador]'";	
	} else {
	    $sql = "select left(concat(nombre,' ',apellido),15)as 'nombre' from personalapoyo "
		    ." where idpersonalapoyo='$data[idtrabajador]';"; 	
	}
	$dato = $db->arrayConsulta($sql);	  
	$_SESSION['nombretrestaurante'] = $dato['nombre'];
	$_SESSION['idusuariorestaurante'] = $data['idusuario'];
	$_SESSION['sucursalrestaaurante'] = $data['sucursal'];
	$_SESSION['IDsucursalrestaaurante'] = $data['idsucursal'];
	header("Location: nuevo_atencion.php");
?>