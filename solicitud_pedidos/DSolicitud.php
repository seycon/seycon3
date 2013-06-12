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
	    return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
	}

	if ($tipo == "consulta") {	
		$consulta = "select * from producto WHERE idproducto =".$_GET['codigo'];
		$respuesta = $db->arrayConsulta($consulta);
		echo $respuesta['stockactual']."---";
		echo $respuesta['costo']."---";
		exit();
	}

	if ($tipo == "insertar") {
		$fecha = $db->GetFormatofecha($_GET['fecha'],"/");
		$sql = "insert into solicitud values(null,'$fecha','".filtro($_GET['moneda'])
		."','".filtro($_GET['almacen'])."','".filtro($_GET['proveedor'])."','".
		filtro($_GET['contacto'])."','".filtro($_GET['glosa'])."','"
		.filtro($_GET['monto'])."','".filtro($_GET['externo'])
		."','$_GET[tc]',$_SESSION[id_usuario],1)";
		$db->consulta($sql);
		$codigo = $db->getMaxCampo("idsolicitud","solicitud");
		$aux = 1;
		if ($_GET['moneda'] == "Dolares") {
			$aux = $_GET['tc'];
		}	
		$datos =  json_decode(stripcslashes($_GET['detalle']));  			
		for ($i = 0; $i < count($datos); $i++) {
			$fila = $datos[$i];                 
			$id = filtro($fila[0]);
			$cantidad = filtro($fila[1]);
			$precio = round((filtro(desconvertir($fila[2]))*$aux),2);
			$total = round((filtro(desconvertir($fila[3]))*$aux),2);
			$consulta = "insert into detallesolicitud values(null,'$codigo'
			,'$id','$cantidad','$precio','$total');";
			$db->consulta($consulta);				
		}	
		echo $codigo."---";
		$sql = "select solicitudimprimir from impresion;";  
		$dato = $db->arrayConsulta($sql);
		echo $dato['solicitudimprimir']."---";  
		exit();  
	}

	if ($tipo == "modificar") {
	  $solicitud = $_GET['idsolicitud'];
	  $fecha = $db->GetFormatofecha($_GET['fecha'],"/");
	  $sql = "update solicitud set fecha='$fecha',moneda='"
	  .filtro($_GET['moneda'])."',idalmacen='".filtro($_GET['almacen'])
	  ."',idproveedor='".filtro($_GET['proveedor'])."',contacto='"
	  .filtro($_GET['contacto'])."',glosa='".filtro($_GET['glosa'])
	  ."',monto='".filtro(desconvertir($_GET['monto']))."',idusuario=$_SESSION[id_usuario],externo='".
	  filtro($_GET['externo'])."' where idsolicitud=".$_GET['idsolicitud'].";";  	
	  $db->consulta($sql);
	  $sql = "delete from detallesolicitud where idsolicitud=$solicitud";
	  $db->consulta($sql);
	  $datos =  json_decode(stripcslashes($_GET['detalle']));				
	  for ($i = 0; $i < count($datos); $i++) {
		  $fila = $datos[$i];                 
		  $id = filtro($fila[0]);
		  $cantidad = filtro($fila[1]);
		  $precio = filtro(desconvertir($fila[2]));
		  $total = filtro(desconvertir($fila[3]));
		  $consulta = "insert into detallesolicitud values(null,'$solicitud'
		  ,'$id','$cantidad','$precio','$total');";
		  $db->consulta($consulta);				
	  }
	  echo $solicitud."---";
	  $sql = "select solicitudimprimir from impresion;";  
	  $dato = $db->arrayConsulta($sql);
	  echo $dato['solicitudimprimir']."---";   	
	  exit();  
	}

?>

