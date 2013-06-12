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
		$fechaInicio = filtro($db->GetFormatofecha($_GET['fechainicio'],'/'));
		$fechaFin =  filtro($db->GetFormatofecha($_GET['fechafin'],'/'));
		$terminado = filtro($_GET['terminado']);
		$fechacierre = 'null';	
		if ($terminado == 'true')
		 $fechacierre = date("Y/m/d");
		
		$sql = "insert into proyecto(titulo,fechainicio,fechafinalizacion
		,proyectoterminado,privado,recursos,glosa,
		porcentajeavance,presupuesto,presupuestoutil,estado,idusuario,fechacierre)";
		$sql .="values('".filtro($_GET['titulo'])."','".$fechaInicio."','"
		       .$fechaFin."',$terminado,".filtro($_GET['privado'])
			   .",'".filtro($_GET['recursos'])."','".filtro($_GET['glosa'])
			   ."','".filtro($_GET['avanceP'])."','".filtro($_GET['presupuesto'])
			   ."','".filtro($_GET['presupuestoUtil'])."','1',$_SESSION[id_usuario],'$fechacierre')";
		$db->consulta($sql);	   
		$idproyecto= $db->getMaxCampo('idproyecto','proyecto');
		$_GET['detalle'] = rawurldecode(utf8_decode($_GET['detalle']));		 
		$datos =  json_decode(stripcslashes($_GET['detalle']));		
		for ($i = 0; $i < count($datos); $i++) {
			 $fila = $datos[$i];
			 $fecha = $db->GetFormatofecha($fila[1],'/');
			 $sql = " insert into detalleproyecto(descripcion,fecha,horas,costo,idproyecto) ";
			 $sql.= " values('".filtro2($fila[0])."', '$fecha', '".filtro2($fila[2])
			 ."', '".filtro2($fila[3])."', '$idproyecto')";
			 $db->consulta($sql);
		}					 
		echo $idproyecto."---";
		$sql = "select dvdireimprimir from impresion;";  
		$dato = $db->arrayConsulta($sql);
		echo $dato['dvdireimprimir']."---";
		exit();
	  }
   
   
   if ($_GET['transaccion'] == "modificar") { 
	  $idproyecto = filtro($_GET['idproyecto']);
	  $fechaInicio = filtro($db->GetFormatofecha($_GET['fechainicio'],'/'));
	  $fechaFin =  filtro($db->GetFormatofecha($_GET['fechafin'],'/'));
	  $terminado = filtro($_GET['terminado']);
	  $fechacierre = 'null';
	  if ($terminado == 'true')
	     $fechacierre = date("Y/m/d");
	  
	  $sql .="update proyecto set titulo='".filtro($_GET['titulo'])
	  ."',fechainicio='".$fechaInicio."',fechafinalizacion='"
	  .$fechaFin."',proyectoterminado=$terminado,privado="
	  .filtro($_GET['privado']).",recursos='".filtro($_GET['recursos'])
	  ."',glosa='".filtro($_GET['glosa'])."',porcentajeavance='".filtro($_GET['avanceP'])
	  ."',presupuesto='".filtro($_GET['presupuesto'])."',presupuestoutil='".filtro($_GET['presupuestoUtil'])
	  ."',idusuario=$_SESSION[id_usuario],fechacierre='$fechacierre' where idproyecto=$idproyecto";
	   $db->consulta($sql);	
	   $sql = "delete from detalleproyecto where idproyecto=$idproyecto";
	   $db->consulta($sql);
	   $_GET['detalle'] = rawurldecode(utf8_decode($_GET['detalle']));
	   $datos =  json_decode(stripcslashes($_GET['detalle']));		
	   for ($i = 0; $i < count($datos); $i++) {
		   $fila = $datos[$i];
		   $fecha = $db->GetFormatofecha($fila[1],'/');
		   $sql = " insert into detalleproyecto(descripcion,fecha,horas,costo,idproyecto) ";
		   $sql.= " values('".filtro($fila[0])."', '$fecha', '".filtro2($fila[2])
		   ."', '".filtro2($fila[3])."', '$idproyecto')";
		   $db->consulta($sql);
	   }		  
	   echo $idproyecto."---";
	   $sql = "select dvdireimprimir from impresion;";  
	   $dato = $db->arrayConsulta($sql);
	   echo $dato['dvdireimprimir']."---";
	   exit();
    }	
	 
?>