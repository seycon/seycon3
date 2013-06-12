<?php
     session_start();
	 include('../conexion.php');
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
		$fecha = filtro($db->GetFormatofecha($_GET['fecha'],'/'));
		$sql = "insert into informetrabajo(nrofactura,estadocobranza,fecha,privado
		,firmadigital,comentario,estado,idcliente,idusuario)";
		$sql .= "values('".filtro($_GET['nrofactura'])."','".filtro($_GET['estado'])
		."','$fecha',".filtro($_GET['privado'])
		.",".filtro($_GET['firmaDigital']).",'".filtro($_GET['comentario'])
		."',1,'".filtro($_GET['idcliente'])."',$_SESSION[id_usuario])";		
		$db->consulta($sql);
		$idinforme = $db->getMaxCampo('idinformetrabajo', 'informetrabajo');
		$_GET['detalle'] = rawurldecode(utf8_decode($_GET['detalle']));				
		$datos =  json_decode(stripcslashes($_GET['detalle']));		
		for ($i = 0; $i < count($datos); $i++) {
		   $fila = $datos[$i];
		   $sql = "insert into detalleinformetrabajo(idinformetrabajo, descripcion, importe) ";
		   $sql .= "values('$idinforme', '".filtro2($fila[0])."', '".filtro2($fila[1])."')";
		   $db->consulta($sql);
		}		
		echo $idinforme."---";
		$sql = "select notacobranzaimprimir from impresion;";  
		$dato = $db->arrayConsulta($sql);
		echo $dato['notacobranzaimprimir']."---";
		exit();
	  }
	
	  if ($_GET['transaccion'] == "modificar") { 
		  $idinforme=filtro($_GET['idReciboI']);
		  $fecha = filtro($db->GetFormatofecha($_GET['fecha'],'/'));
		  $sql = "update informetrabajo set nrofactura='".filtro($_GET['nrofactura'])
		  ."',estadocobranza='".filtro($_GET['estado'])."',fecha='$fecha'
		  ,privado=".filtro($_GET['privado']).",firmadigital=".filtro($_GET['firmaDigital'])
		  .",comentario='".filtro($_GET['comentario'])."'
		  ,idcliente='".filtro($_GET['idcliente'])."',idusuario=$_SESSION[id_usuario]
		   where idinformetrabajo=$idinforme";	    
		  $db->consulta($sql);	
		  
		  $sql = "delete from detalleinformetrabajo where idinformetrabajo=$idinforme";
		  $db->consulta($sql);
		  $_GET['detalle'] = rawurldecode(utf8_decode($_GET['detalle']));		  
		  $datos =  json_decode(stripcslashes($_GET['detalle']));		
		  for ($i = 0; $i < count($datos); $i++) {
			  $fila = $datos[$i];
			  $sql = "insert into detalleinformetrabajo(idinformetrabajo, descripcion, importe) ";
			  $sql .= "values('$idinforme', '".filtro2($fila[0])."', '".filtro2($fila[1])."')";
			  $db->consulta($sql);
		  }		
		  echo $idinforme."---";
		  $sql = "select notacobranzaimprimir from impresion;";  
		  $dato = $db->arrayConsulta($sql);
		  echo $dato['notacobranzaimprimir']."---";
		  exit();
	  }        
   
?>