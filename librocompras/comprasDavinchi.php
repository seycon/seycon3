<?php
     session_start();	 
	 include('../conexion.php');
	 $db = new MySQL();
	 
	 function aumentarDigito($valor){
		 if (strlen($valor)==1)
		 return "0".$valor;
		return $valor; 
	 } 
	 
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

     $archivo= "compras_".aumentarDigito($numMes).$numAnio."_".$nit.".txt"; 
     $contenido= ""; 
	 $sql = "select idsucursal from sucursal where estado=1;";
	 $sucursal = $db->consulta($sql);
	 
	 while($datoSucursal = mysql_fetch_array($sucursal)){      
          $sql = "select DATE_FORMAT(fechadeemision,'%d/%m/%Y') as 'fechadeemision',numdenitproveedor,nomrazonsociprove,numfacturaopoliza,
            numautorizacion,codigodecontrol,round(totalfactura,2)as 'totalfactura',round(totalice,2)as 'totalice',round(importeexcento,2)as    
			'importeexcento',round(importeneto,2)as 'importeneto',round(creditofiscal,2)as 'creditofiscal' from librocomprasiva where 
			Month(fechadeemision)=$numMes and year(fechadeemision)=$numAnio  and estado=1 and folio=$datoSucursal[idsucursal] ORDER BY fechadeemision ;";
          $consulta = mysql_query($sql);
		  while($dato = mysql_fetch_array($consulta)){
			  $contenido = $contenido ."1|".$dato['numdenitproveedor']."|".strtoupper($dato['nomrazonsociprove'])."|".$dato['numfacturaopoliza']."|0|".$dato['numautorizacion']
			  ."|".$dato['fechadeemision']."|".$dato['totalfactura']."|".$dato['totalice']."|".$dato['importeexcento']
			  ."|".$dato['importeneto']."|".$dato['creditofiscal']."|".$dato['codigodecontrol'].PHP_EOL;		
		  }
	 }

	 header( "Content-Type: application/octet-stream");
     header( "Content-Disposition: attachment; filename=".$archivo.""); 	 
	 echo $contenido;
?> 


