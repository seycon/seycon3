<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
    session_start();
	include('../conexion.php');
	include("../aumentaComa.php");
	$db = new MySQL();
  
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
  
   if ($_GET['transaccion'] == "insertar") { 
	    $sql = "insert into reciboegreso(identregado,nombreentregado,fecha,responsable
		,pagado,totalegreso,firma_digital,estado,codigo,cargo,idusuario)";
        $fecha = filtro($db->GetFormatofecha($_GET['fecha'],"/"));
	    $sql .= "values('".filtro($_GET['idrecibido'])."','"
		.filtro($_GET['nombrerecibido'])."','$fecha','".filtro($_SESSION['nombre_usuario'])
		."',".filtro($_GET['pagado']).",'".filtro($_GET['totalingreso'])."',
		".filtro($_GET['firmaDigital']).",1,'".filtro($_GET['codigo'])."','"
		.filtro($_GET['cargo'])."',$_SESSION[id_usuario])";		
		$db->consulta($sql);
		$codigo = $db->getMaxCampo('idreciboegreso', 'reciboegreso');	
		$_GET['detalle'] = rawurldecode(utf8_decode($_GET['detalle']));					
		$datos =  json_decode(stripcslashes($_GET['detalle']));		
		for ($i = 0; $i < count($datos); $i++) {
		   $fila = $datos[$i];
		   $ingreso = filtro(desconvertir($fila[0]));
		   $sql = "insert into detallereciboegreso(idreciboegreso, egreso, descripcion) ";
		   $sql .= "values('$codigo', '$ingreso', '".filtro2($fila[1])."')";
		   $db->consulta($sql);
		}				
		echo $codigo."---";
		$sql = "select recibrgresoimprimir from impresion;";  
	    $dato = $db->arrayConsulta($sql);
	    echo $dato['recibrgresoimprimir']."---";
		exit();
    }
	
	if ($_GET['transaccion'] == "modificar") { 
	    $idreciboingreso = filtro($_GET['idReciboI']);
	    $fecha = date("Y-m-d", strtotime(fechaAMD($_GET['fecha'])));
	    $sql = "update reciboegreso set identregado='".filtro($_GET['idrecibido'])
		."',nombreentregado='".filtro($_GET['nombrerecibido'])."',fecha='$fecha'
		,responsable='$_SESSION[nombre_usuario]',pagado=".filtro($_GET['pagado'])
		.",totalegreso='".filtro($_GET['totalingreso'])."',firma_digital="
		.filtro($_GET['firmaDigital']).",codigo='".filtro($_GET['codigo'])."',cargo='".filtro($_GET['cargo'])
		."',idusuario=$_SESSION[id_usuario] where idreciboegreso=$idreciboingreso";     
		$db->consulta($sql);		
		$sql = "delete from detallereciboegreso where idreciboegreso=$idreciboingreso";
		$db->consulta($sql);
		$_GET['detalle'] = rawurldecode(utf8_decode($_GET['detalle']));		
		$datos =  json_decode(stripcslashes($_GET['detalle']));		
	    for ($i = 0; $i < count($datos); $i++) {
		    $fila = $datos[$i];
		    $ingreso = filtro(desconvertir($fila[0]));
		    $sql = "insert into detallereciboegreso(idreciboegreso, egreso, descripcion) ";
		    $sql .= "values('$idreciboingreso', '$ingreso', '".filtro2($fila[1])."')";
		    $db->consulta($sql);
		}		
		echo $idreciboingreso."---";
		$sql = "select recibrgresoimprimir from impresion;";  
	    $dato = $db->arrayConsulta($sql);
	    echo $dato['recibrgresoimprimir']."---";
		exit();
    }	
?>