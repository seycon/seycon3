<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
	include('../conexion.php');
	$db = new MySQL();
	$transaccion = $_GET['transaccion'];
	
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: ../index.php");	
	}
	
	function filtro($cadena)
	{
	  return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
	}
	
	function filtro2($cadena)
	{
	    return htmlspecialchars(addslashes(strip_tags($cadena)));
	}	
	
	if ($transaccion == "insertar") {
	   $nombre = filtro($_GET['nombre']);	
	   $db->consulta("insert into grupo(nombre,estado)values('$nombre',1);");
	   $idgrupo = $db->getMaxCampo("idgrupo","grupo");
	   $_GET['detalle'] = rawurldecode(utf8_decode($_GET['detalle']));	
	   $datos =  json_decode(stripcslashes($_GET['detalle']));
				
		for ($i=0; $i<count($datos); $i++) {
			$fila = $datos[$i];                 
			$nombre = filtro2($fila[0]);
			$descripcion = filtro2($fila[1]);
			$consulta = "insert into subgrupo(nombre,descripcion,idgrupo,estado) 
			values('$nombre','$descripcion','$idgrupo',1)";
			$db->consulta($consulta);				
		}	
	  exit();			
	}

	if ($transaccion == "modificar") {
	   $nombre = filtro($_GET['nombre']);	
	   $idgrupo = $_GET['idgrupo'];
	   $db->consulta("update grupo set nombre='$nombre' where idgrupo=$idgrupo;");
	   $db->consulta("update subgrupo set estado=0 where idgrupo=$idgrupo;");
	   $_GET['detalle'] = rawurldecode(utf8_decode($_GET['detalle']));
	   $datos =  json_decode(stripcslashes($_GET['detalle']));
				
	   for ($i = 0; $i < count($datos); $i++) {
			$fila = $datos[$i];                 
			$nombre = filtro2($fila[0]);
			$descripcion = filtro2($fila[1]);
			$idsubgrupo = filtro($fila[2]);
			if ($idsubgrupo == 0)
			$consulta = "insert into subgrupo(nombre,descripcion,idgrupo,estado)
			 values('$nombre','$descripcion','$idgrupo',1)";
			else
			$consulta = "update subgrupo set nombre='$nombre',descripcion='$descripcion'
			,estado=1 where idsubgrupo='$idsubgrupo'";
			$db->consulta($consulta);				
	   }	
	   exit();			
	}


?>

