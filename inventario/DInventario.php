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
	
	if ($tipo == "consulta") {
		$sql = "select * from producto WHERE idproducto =".$_GET['codigo'];
		$respuesta = $db->arrayConsulta($sql);
		echo $respuesta['unidaddemedida']."---";
		echo $respuesta['unidadalternativa']."---";
	}
	
	if ($tipo == "insertar") {
	  $fechainicial = $db->GetFormatofecha($_GET['fecha'],"/");
	  $fechafinal =	$db->GetFormatofecha($_GET['fechafinal'],"/");
		
	  $sql = "insert into inventario(idinventario,fechainicio,fechafinal
	  ,idalmacen,supervisor,administrador,glosa,estado,idusuario)
	  values(null,'$fechainicial','$fechafinal','$_GET[idalmacen]','"
	  .filtro($_GET['supervisor'])."','"
	  .filtro($_GET['administrador'])."','".filtro($_GET['glosa'])
	  ."',1,$_SESSION[id_usuario]);";	
	  $db->consulta($sql); 	
	  
	  $codigo = $db->getMaxCampo("idinventario", "inventario");
	  $datos =  json_decode(stripcslashes($_GET['detalle']));  			
	  for ($i = 0; $i < count($datos); $i++) {
		  $fila = $datos[$i];                 
		  $idproducto = $fila[0];
		  $cantidadum = $fila[1];
		  $um = $fila[2];
		  $cantidadua = $fila[3];
		  $ua = $fila[4];		
		  $sql = "insert into detalleinventario(iddetalleinventario,idinventario,idproducto,cantidadum
		  ,unidadmedida,cantidadua,unidadalternativa)
		  value(null,$codigo,'$idproducto','$cantidadum','$um','$cantidadua','$ua');";
		  $db->consulta($sql);
      }
		  
	  $codigo = $db->getMaxCampo("idinventario","inventario");
	  echo $codigo;
	  exit();
	}

	if ($tipo == "modificar") { 
		$fechafinal =	$db->GetFormatofecha($_GET['fechafinal'],"/");	
		$codigo = $_GET['idtransaccion'];
		$sql = "update inventario set fechafinal='$fechafinal',idalmacen='$_GET[idalmacen]'
		,idusuario='$_SESSION[id_usuario]'
		,supervisor='".filtro($_GET['supervisor'])."',administrador='"
		.filtro($_GET['administrador'])."',glosa='".filtro($_GET['glosa'])
		."'  where idinventario=$codigo;";	
		$db->consulta($sql); 	  
		
		$sql = "delete from detalleinventario where idinventario=$codigo";
		$db->consulta($sql);
	  
		$datos =  json_decode(stripcslashes($_GET['detalle']));  			
		for ($i = 0; $i < count($datos); $i++) {
			$fila = $datos[$i];                 
			$idproducto = $fila[0];
			$cantidadum = $fila[1];
			$um = $fila[2];
			$cantidadua = $fila[3];
			$ua = $fila[4];		
			$sql = "insert into detalleinventario(iddetalleinventario,idinventario,idproducto,cantidadum
			,unidadmedida,cantidadua,unidadalternativa)
			value(null,$codigo,'$idproducto','$cantidadum','$um','$cantidadua','$ua');";
			$db->consulta($sql);
		}		  
		  
		echo $codigo;
		exit();		  
	}
?>