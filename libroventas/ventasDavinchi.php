<?php
     session_start();	 
	 include('../conexion.php');
	 $db = new MySQL();
	 
     if (strlen($_GET['mes']) == 1)
	    $numMes = '0'.$_GET['mes'];
	 else
	    $numMes = $_GET['mes'];
	   $numAnio = $_GET['anio'];
	   $sucursal = $_GET['sucursal'];
	 	 
	 $consulta = mysql_query("select nit from empresa where estado=1");
 	 $cant = mysql_num_rows($consulta);
	 $dato = mysql_fetch_array($consulta);
     
	 if ($cant=0)
	 $nit = "0000";
	 else
	 $nit = $dato['nit']; 

     $archivo= "ventas_".$numMes.$numAnio."_".$nit.".txt"; 
     $contenido= ""; 
     $sql = "select idsucursal from sucursal where estado=1;";
	 $sucursal = $db->consulta($sql);
     while($datoSucursal = mysql_fetch_array($sucursal)){       
		 $sql = "select DATE_FORMAT(fechadeemision,'%d/%m/%Y') as 'fechadeemision',numcinitcliente,
		 nomrazonsocicliente,numfactura,numautorizacion,codigodecontrol,round(totalfactura,2)as 'totalfactura',
		 round(totalice,2)as 'totalice',round(importeexcento,2)as 'importeexcento',round(importeneto,2)as 
		 'importeneto', round(debitofiscal,2)as 'debitofiscal',estadofactura from libroventasiva where 
		 Month(fechadeemision)=$numMes and year(fechadeemision)=$numAnio and estado=1 and folio=$datoSucursal[idsucursal] order by numfactura asc";
         $consulta = mysql_query($sql);
    	 while($dato = mysql_fetch_array($consulta)){		
		  $contenido = $contenido.$dato['numcinitcliente']."|".$dato['nomrazonsocicliente']."|".$dato['numfactura']."|".
		  $dato['numautorizacion']."|".$dato['fechadeemision']."|".$dato['totalfactura']."|".$dato['totalice']."|".
		  $dato['importeexcento']."|".$dato['importeneto']."|".$dato['debitofiscal']."|".$dato['estadofactura']."|".
		  $dato['codigodecontrol'].PHP_EOL;
	     }
	 }
 

	 header( "Content-Type: application/octet-stream");
     header( "Content-Disposition: attachment; filename=".$archivo.""); 	 
	 echo $contenido; 
?> 