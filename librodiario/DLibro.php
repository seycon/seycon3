<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
	session_start();
	include("../conexion.php");
	include("../aumentaComa.php");
	$db = new MySQL();
	$tipo = $_GET['transaccion'];
	
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: ../index.php");	
	}
	
	function filtro($cadena)
	{
	    return htmlspecialchars(strip_tags($cadena));
	}
	
	function filtro2($cadena)
	{
	    return htmlspecialchars(addslashes(strip_tags($cadena)));
	}	 

	if ($tipo == "insertar") {
		$fecha = filtro($db->GetFormatofecha($_GET['fecha'],'/'));	
		$numero = $db->getNextID("numero","librodiario where idsucursal='$_GET[sucursal]'");	
		$sql = "insert into librodiario values(null,'$numero','".filtro($_GET['sucursal'])."','".filtro($_GET['moneda'])
		."','Libro Diario','"
		.filtro($_GET['tipotransaccion'])."','$fecha','".filtro($_GET['glosa'])."',null,'"
		.filtro($_GET['tipocambio'])."','$_SESSION[id_usuario]',1)";
		$db->consulta($sql);
		$codigo = $db->getMaxCampo("idlibrodiario","librodiario");
		$_GET['detalle'] = rawurldecode(utf8_decode($_GET['detalle']));
		$datos =  json_decode(stripcslashes($_GET['detalle']));  			
		for ($i = 0; $i < count($datos); $i++) {
			$fila = $datos[$i];                 
			$cuenta = filtro2($fila[0]);
			$descripcion = filtro2($fila[1]);
			$debe = filtro2(desconvertir($fila[2]));
			$haber = filtro2(desconvertir($fila[3]));
			$documento = filtro2($fila[4]);
			$consulta = "insert into detallelibrodiario values
			(null,'$codigo','$cuenta','$descripcion','$debe','$haber','$documento');";
			$db->consulta($consulta);				
		}	
		echo $codigo;  
		exit();  	
	}
	
	if ($tipo == "modificar") {
		$fecha = filtro($db->GetFormatofecha($_GET['fecha'],'/'));
		$idlibro = filtro($_GET['idlibro']);	
		$sql = "update librodiario set idsucursal='".filtro($_GET['sucursal'])
		."',moneda='".filtro($_GET['moneda'])."',tipotransaccion='"
		.filtro($_GET['tipotransaccion'])."',fecha='$fecha',glosa='"
		.filtro($_GET['glosa'])."',tipocambio='".filtro($_GET['tipocambio'])
		."',idusuario='$_SESSION[id_usuario]' where idlibrodiario=$idlibro";
		$db->consulta($sql);
		$sql = "delete from detallelibrodiario where idlibro=$idlibro";
		$db->consulta($sql);
		$_GET['detalle'] = rawurldecode(utf8_decode($_GET['detalle']));		
		$datos =  json_decode(stripcslashes($_GET['detalle']));  			
		for ($i = 0; $i < count($datos); $i++) {
			$fila = $datos[$i];                 
			$cuenta = filtro2($fila[0]);
			$descripcion = filtro2($fila[1]);
			$debe = filtro2(desconvertir($fila[2]));
			$haber = filtro2(desconvertir($fila[3]));
			$documento = filtro2($fila[4]);
			$consulta = "insert into detallelibrodiario values
			(null,'$idlibro','$cuenta','$descripcion','$debe','$haber','$documento');";
			$db->consulta($consulta);				
		}	
		echo $idlibro;  
		exit();  
	}

?>