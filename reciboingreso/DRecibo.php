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
       $fecha = filtro($db->GetFormatofecha($_GET['fecha'],"/"));
	   $sql = "insert into reciboingreso(idrecibido,nombrerecibido,fecha,responsable
		,pagado,totalingreso,firma_digital,estado,codigo,cargo,idusuario)";
	   $sql .= "values('".filtro($_GET['idrecibido'])."','".filtro($_GET['nombrerecibido'])
		."','$fecha','$_SESSION[nombre_usuario]',".filtro($_GET['pagado']).",'"
		.filtro($_GET['totalingreso'])."',".filtro($_GET['firmaDigital'])
		.",1,'$_GET[codigo]','".filtro($_GET['cargo'])."',$_SESSION[id_usuario])";		
	   $db->consulta($sql);
	   $codigo = $db->getMaxCampo('idreciboingreso', 'reciboingreso');		
	   $_GET['detalle'] = rawurldecode(utf8_decode($_GET['detalle']));		
	   $datos =  json_decode(stripcslashes($_GET['detalle']));		
	   for ($i = 0; $i < count($datos); $i++) {
		   $fila = $datos[$i];
		   $ingreso = filtro(desconvertir($fila[0]));
		   $sql = "insert into detallereciboingreso(idreciboingreso, ingreso, descripcion) ";
		   $sql .= "values('$codigo', '$ingreso', '".filtro2($fila[1])."')";
		   $db->consulta($sql);
	   }				
	   echo $codigo."---";
	   $sql = "select recibingimprimir from impresion;";  
	   $dato = $db->arrayConsulta($sql);
	   echo $dato['recibingimprimir']."---";
	   exit();
    }
	
	if ($_GET['transaccion'] == "modificar") { 
	    $idreciboingreso= filtro($_GET['idReciboI']);
	    $fecha = date("Y-m-d", strtotime(fechaAMD($_GET['fecha'])));
	    $sql = "update reciboingreso set idrecibido='".filtro($_GET['idrecibido'])
		."',nombrerecibido='".filtro($_GET['nombrerecibido'])."',fecha='$fecha'
		,responsable='".filtro($_SESSION['nombre_usuario'])."',pagado="
		.filtro($_GET['pagado']).",totalingreso='".filtro($_GET['totalingreso'])
		."',firma_digital=".filtro($_GET['firmaDigital']).",codigo='"
		.filtro($_GET['codigo'])."',cargo='".filtro($_GET['cargo'])
		."',idusuario=$_SESSION[id_usuario] where idreciboingreso=$idreciboingreso";     
		$db->consulta($sql);		
		$sql = "delete from detallereciboingreso where idreciboingreso=$idreciboingreso";
		$db->consulta($sql);
	    $_GET['detalle'] = rawurldecode(utf8_decode($_GET['detalle']));			
		$datos =  json_decode(stripcslashes($_GET['detalle']));		
		for ($i = 0; $i < count($datos); $i++) {
		    $fila = $datos[$i];
		    $ingreso = filtro(desconvertir($fila[0]));
		    $sql = "insert into detallereciboingreso(idreciboingreso, ingreso, descripcion) ";
		    $sql .= "values('$idreciboingreso', '$ingreso', '".filtro2($fila[1])."')";
		    $db->consulta($sql);
		}		
		
		echo $idreciboingreso."---";
		$sql = "select recibingimprimir from impresion;";  
	    $dato = $db->arrayConsulta($sql);
	    echo $dato['recibingimprimir']."---";
		exit();
    }
	
?>