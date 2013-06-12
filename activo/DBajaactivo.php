<?php
	session_start();
	include("../conexion.php");
	$db = new MySQL();
	
	if (!isset($_SESSION['softLogeoadmin'])) {
		   header("Location: index.php");	
	}
	
	$tipo = $_GET['transaccion'];

	if ($tipo == "activos") {
	   $idsucursal = $_GET['idsucursal']; 
	   $sql = "select idactivo,left(nombre,25)as 'nombre' from activo where idsucursal=$idsucursal and estado=1;";
	   echo "<option value=''> -- Seleccione -- </option>";
	   $db->imprimirCombo($sql);
	   echo "---00";
	   echo "---00";
	   exit(); 
	}
	
	if ($tipo == "datosactivos") {
	   $idactivo = $_GET['idactivo']; 
	   $sql = "select left(concat(t.nombre,' ',t.apellido),25)as 'trabajador',a.ubicacion,a.cantidad 
	   from activo a,trabajador t where a.idtrabajador=t.idtrabajador and idactivo=$idactivo;";
	   $datos = $db->arrayConsulta($sql);   
	   echo $datos['trabajador']."---";
	   echo $datos['ubicacion']."---";
	   echo $datos['cantidad']."---";
	   exit(); 
	}
?>