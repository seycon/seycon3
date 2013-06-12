<?php
	include("conexion.php");
	$db = new MySQL();
	$estructura = $_SESSION['estructura'];
	if (!isset($_SESSION['softLogeoadmin'])) {
	    header("Location: index.php");	
	}
	if (!$db->tieneAccesoFile($estructura['Administracion'],'Respaldo del Sistema','bacukp_datos.php')) {
	    header("Location: cerrar.php");	
	}
	
	
	$nombre = "backup_datos";
	$nombreCompleto = date("d/m/Y").$nombre.".sql";
	header( "Content-type: application/savingfile" );
	header( "Content-Disposition: attachment; filename=$nombreCompleto" );
	header( "Content-Description: Document." );	
	$tables = $db->consulta("show tables");
	
	echo "-- Systema: Seycon\n";
	echo "-- Tiempo de generacion: ".date("d/m/Y")." a las ".date("H:i:s")."\n";
	
	while ($tabla = mysql_fetch_array($tables)) {
		$nombreTabla = $tabla[0];	
		$datos = $db->consulta("select * from $nombreTabla");
		$n = $db->getnumRow("select * from $nombreTabla");
		$cantidad = $db->getnumRow("show columns from $nombreTabla");  
		  
		if ($n > 0) {
		  echo "\n";  
		  echo "--\n";  
		  echo "-- Datos de la tabla $nombreTabla\n";  
		  echo "--\n\n";
		}
		  
		while($dato = mysql_fetch_array($datos)) {
			$cadena = "insert into $nombreTabla values(";  
			for ($i = 0;$i < $cantidad ;$i++){
			   $cadena.=  ($i == $cantidad-1) ? "'".$dato[$i]."');\n" : "'".$dato[$i]."',"; 
			}
			echo $cadena;
		}
	}

?>