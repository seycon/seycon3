<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
	session_start();
	include("../conexion.php");
	include("../aumentaComa.php");
	$transaccion = $_GET['transaccion'];
	$db = new MySQL();
	if (!isset($_SESSION['softLogeoadmin'])) {
        header("Location: ../index.php");	
	}

	function filtro($cadena)
	{
	    return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
	}

	if ($transaccion == "consulta") {	
		$consulta = "select * from producto WHERE idproducto =".$_GET['codigo'];
		$respuesta = $db->arrayConsulta($consulta);
		echo $respuesta['costo']."---";
		echo $respuesta['unidaddemedida']."---";
		echo $respuesta['unidadalternativa']."---";
		echo $respuesta['conversiones']."---";
		exit();
	}

	if ($transaccion == "modificar") {	 
		$nombre = filtro($_GET['nombre']);	
		$idtipo = filtro($_GET['tipocombinacion']);
		$total  = filtro($_GET['total']);
		$glosa  = filtro($_GET['glosa']);
		$idcombinacion = $_GET['idcombinacion'];
		$sql = "update combinacion set    
		nombre='$nombre',idtipocombinacion='$idtipo',total='$total'
		,glosa='$glosa',idusuario=$_SESSION[id_usuario] where 
		idcombinacion=$idcombinacion";
		$db->consulta($sql);
		$db->consulta("delete from detallecombinacion where idcombinacion=$idcombinacion");
		$datos =  json_decode(stripcslashes($_GET['detalle']));
				
		for ($i = 0; $i < count($datos); $i++) {
			$fila = $datos[$i];                 
			$id = filtro($fila[0]);
			$cantidad = filtro($fila[1]);
			$um = filtro($fila[2]);
			$precio = filtro(desconvertir($fila[3]));
			$total = filtro(desconvertir($fila[4]));
			$consulta = "insert into detallecombinacion values
			(null,'$idcombinacion','$id','$cantidad','$um','$precio','$total');";
			$db->consulta($consulta);				
		}	
		exit();		
	}


	if ($transaccion == "insertar") {	 
		$nombre = filtro($_GET['nombre']);	
		$total  = filtro(desconvertir($_GET['total']));
		$glosa  = filtro($_GET['glosa']);
		$idtipo = filtro($_GET['tipocombinacion']);
		$sql = "insert into combinacion (nombre,idtipocombinacion,total,glosa,estado,idusuario) values (
		'$nombre','$idtipo','$total','$glosa',1,$_SESSION[id_usuario])";
		$db->consulta($sql);
		$idcombinacion = $db->getMaxCampo('idcombinacion','combinacion');
		$datos =  json_decode(stripcslashes($_GET['detalle']));
				
		for ($i = 0; $i < count($datos); $i++) {
			$fila = $datos[$i];                 
			$id = filtro($fila[0]);
			$cantidad = filtro($fila[1]);
			$um = filtro($fila[2]);
			$precio = filtro(desconvertir($fila[3]));
			$total = filtro(desconvertir($fila[4]));
			$consulta = "insert into detallecombinacion values
			(null,'$idcombinacion','$id','$cantidad','$um','$precio','$total');";
			$db->consulta($consulta);				
		}	
		 exit();		
	}

?>