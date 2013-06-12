<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
	session_start();
	include('../conexion.php');
	include('../aumentaComa.php');
	$db = new MySQL();
	$transaccion = $_GET['transaccion'];
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: ../index.php");	
	}
	
	function filtro($cadena)
	{
	   return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
	}
	
	
	if ($transaccion == "insertar") {
	   $fecha = $db->GetFormatofecha($_GET['fecha'],"/");	
	   $sqlF = "insert into finiquitos(fecha,idtrabajador,motivo
	   ,mesesvacaciones,diasvacaciones,mesesprima,diasprima,
	   descripcionotros,totalotros,idusuario,estado)values('$fecha','"
	   .filtro($_GET['trabajador'])."','".filtro($_GET['motivo'])."','".filtro($_GET['mesesvacaciones'])."'
	   ,'".filtro($_GET['diasvacaciones'])."','".filtro($_GET['mesesprima'])
	   ."','".filtro($_GET['diasprima'])."','".filtro($_GET['descripcionotros'])
	   ."','".filtro($_GET['totalotros'])."','$_SESSION[id_usuario]',1);";
	   $db->consulta($sqlF);
	   $idfiniquito = $db->getMaxCampo("idfiniquitos","finiquitos");	
	   $datos =  json_decode(stripcslashes($_GET['detalle']));				
	   for ($i = 0; $i < count($datos); $i++) {
			$fila = $datos[$i];                 
			$descripcion = filtro($fila[0]);
			$total = filtro(desconvertir($fila[1]));
			$sql = "insert into descuentofiniquitos(descripcion,monto,idfiniquitos) values
			('$descripcion','$total','$idfiniquito')";
			$db->consulta($sql);				
	   }			
	   exit();			
	}
	
	if ($transaccion == "modificar") {
	   $fecha = $db->GetFormatofecha($_GET['fecha'],"/");	
	   $idfiniquito = $_GET['idfiniquito'];
		$sqlF = "update finiquitos set fecha='$fecha',idtrabajador='"
		.filtro($_GET['trabajador'])."',motivo='".filtro($_GET['motivo']).
		"',mesesvacaciones='".filtro($_GET['mesesvacaciones'])
		."',diasvacaciones='".filtro($_GET['diasvacaciones']).
		"',mesesprima='".filtro($_GET['mesesprima'])."',diasprima='".filtro($_GET['diasprima'])."',
		descripcionotros='".filtro($_GET['descripcionotros'])
		."',totalotros='".filtro($_GET['totalotros'])."',
		idusuario='$_SESSION[id_usuario]' where idfiniquitos=$idfiniquito;";
	   $db->consulta($sqlF);
	   $db->consulta("delete from descuentofiniquitos where idfiniquitos=$idfiniquito");
	   $datos =  json_decode(stripcslashes($_GET['detalle']));				
	   for ($i = 0; $i < count($datos); $i++) {
			$fila = $datos[$i];                 
			$descripcion = filtro($fila[0]);
			$total = filtro(desconvertir($fila[1]));
			$sql = "insert into descuentofiniquitos(descripcion,monto,idfiniquitos) values
			('$descripcion','$total','$idfiniquito')";
			$db->consulta($sql);					
	   }	
	   exit();			
	}


?>