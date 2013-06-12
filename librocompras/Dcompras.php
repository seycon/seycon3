<?php
     session_start();
	 include('../conexion.php');
	 $db = new MySQL();
	 
	 if (!isset($_SESSION['softLogeoadmin'])) {
       header("Location: ../index.php");	
     } 
	 
	 if (isset($_GET['transaccion']) && $_GET['transaccion'] == "insertar") { 
		$fecha = $_GET['anio']."-".$_GET['mes']."-".$_GET['dia'];
		
		$sql = "select numdenitproveedor from librocomprasiva 
		where folio=$_GET[sucursal] and fechadeemision=LAST_DAY('$fecha') and estado=1;";
		$libro = $db->arrayConsulta($sql);
		 if (isset($libro['numdenitproveedor']) && $libro['numdenitproveedor'] == 0) {
			echo "-1";
			exit(); 
		 }
		
		$sql = "insert into librocomprasiva(folio,fechadeemision,numdenitproveedor
		,nomrazonsociprove,numfacturaopoliza,numautorizacion,codigodecontrol,
				totalfactura,totalice,importeexcento,importeneto,creditofiscal
				,idtransaccion,transaccion,tipo,tipolibro,idcuenta,idusuario,estado)
				values ('$_GET[sucursal]','$fecha','$_GET[nit]','$_GET[razonsocial]'
				,'$_GET[numerofactura]','$_GET[numeroautorizacion]',
				'$_GET[codigocontrol]','$_GET[importetotal]','$_GET[ice]'
				,'$_GET[excento]','$_GET[neto]','$_GET[iva]',null,'Libro Compras','LC',
				'$_GET[tipolibro]','$_GET[cuenta]','$_SESSION[id_usuario]',1)";
		$db->consulta($sql);	   
		echo $db->getMaxCampo("idlibrocomprasiva","librocomprasiva");
		exit();
	 }	 
  
     if (isset($_GET['transaccion']) && $_GET['transaccion'] == "modificar") { 
		$fecha = $_GET['anio']."-".$_GET['mes']."-".$_GET['dia'];
		$idlibro = $_GET['idtransaccion'];
		$sql = "update librocomprasiva set folio='$_GET[sucursal]',fechadeemision='$fecha',
		 numdenitproveedor='$_GET[nit]',nomrazonsociprove='$_GET[razonsocial]'
		 ,numfacturaopoliza='$_GET[numerofactura]'
		 ,numautorizacion='$_GET[numeroautorizacion]'
		 ,codigodecontrol='$_GET[codigocontrol]',idcuenta='$_GET[cuenta]',
		 totalfactura='$_GET[importetotal]',totalice='$_GET[ice]'
		 ,importeexcento='$_GET[excento]',tipolibro='$_GET[tipolibro]'
		 ,importeneto='$_GET[neto]',creditofiscal='$_GET[iva]'
		 ,idusuario='$_SESSION[id_usuario]' where idlibrocomprasiva=$idlibro;";
		$db->consulta($sql);	   
		echo $idlibro;
		exit();
     }	 
	 
	 if (isset($_GET['transaccion']) && $_GET['transaccion'] == "eliminar") {
		$idlibro = $_GET['idlibro'];
		$sql = "update librocomprasiva set estado=0 where idlibrocomprasiva=$idlibro;";
		$db->consulta($sql);
		echo $sql;
		exit();	  
	 }
	 
	 if (isset($_GET['transaccion']) && $_GET['transaccion'] == "sinMovimiento") {
		$fecha = $_GET['ano']."-".$_GET['mes']."-".date("d");
		$sql = "select count(*) as 'cantidad' from librocomprasiva 
		where folio=$_GET[sucursal] and month(fechadeemision)=$_GET[mes]
		 and year(fechadeemision)=$_GET[ano]
		 and  estado=1;";
		$compra = $db->arrayConsulta($sql);
		$tipo = -1;
		$idlibro = "";
		if ($compra['cantidad'] > 0) {
		    echo $tipo."---";
		    echo $fecha."---";
		    echo $idlibro."---";
		    exit();
		}	
	  
		$sql = "select LAST_DAY('$fecha') as 'fechaFinal';";
		$fechaFinal = $db->arrayConsulta($sql);
	  	$sqlCompras = "insert into librocomprasiva(folio,fechadeemision,numdenitproveedor
		,nomrazonsociprove,numfacturaopoliza,numautorizacion,codigodecontrol,
            totalfactura,totalice,importeexcento,importeneto,creditofiscal
			,idtransaccion,transaccion,tipo,tipolibro,idcuenta,idusuario,estado)
            values ('$_GET[sucursal]','$fechaFinal[fechaFinal]','0','0','0','0',
	        '0','0','0','0','0','0',null,'Libro Compras','LC','0','0','$_SESSION[id_usuario]',1)";
		$db->consulta($sqlCompras);
		$sql = "select idlibrocomprasiva,date_format(fechadeemision,'%d/%m/%Y')
		as 'fechadeemision' from librocomprasiva where folio=$_GET[sucursal] and 
		fechadeemision=LAST_DAY('$fecha');";
    	$libro = $db->arrayConsulta($sql);
		$fecha = $libro['fechadeemision'];
		$idlibro = $libro['idlibrocomprasiva'];
		$tipo = 1;

		echo $tipo."---";
		echo $fecha."---";
		echo $idlibro."---";
		exit();		  
	  }
	 
   if (isset($_GET['transaccion']) && $_GET['transaccion'] == "consultar") {	   
	   $sql = "select numautorizacion from sucursal where idsucursal = '$_GET[sucursal]'";
	   $sucursal = $db->arrayConsulta($sql);
	   echo $sucursal['numautorizacion']."---";
	   
	   $sql = "SELECT DATE_FORMAT(fechadeemision, '%d/%m/%Y')as 'fecha'
	     ,numdenitproveedor,left(nomrazonsociprove,25) as 'nomrazon',numfacturaopoliza,
		 numautorizacion,codigodecontrol,totalfactura
		 ,totalice,importeexcento,importeneto,tipolibro,nomrazonsociprove as 'razon',
		 creditofiscal,idtransaccion,tipo,idcuenta,idlibrocomprasiva
		 ,nomrazonsociprove,fechadeemision from librocomprasiva 
		 where MONTH(fechadeemision) = '$_GET[mes]' and YEAR(fechadeemision) = '$_GET[ano]' 
		 and folio = '$_GET[sucursal]' and estado=1 ORDER BY fechadeemision ";
		$libro = $db->consulta($sql);
		$i = 1;
		while ($dato = mysql_fetch_array($libro)) {
			if ($i%2 == 0)
			 $color = "background-color:#CCC;line-height:14px;font-size:11px";
			else
			 $color = "background-color:#FFF;line-height:14px;font-size:11px"; 				
		  echo "
		  <tr style='$color'>
			<td><img src='../iconos/edit.png' style='cursor:pointer' title='Modificar' onclick='editar(this)'/></td>
			<td><img src='../iconos/borrar.gif' style='cursor:pointer' title='Eliminar' onclick='eliminarItem(this)'/></td>
			<td>$i</td>
			<td>$dato[fecha]</td>
			<td style='display:none'>$dato[numdenitproveedor]</td>
			<td>$dato[nomrazon]</td>
			<td>$dato[numfacturaopoliza]</td>
			<td>$dato[numautorizacion]</td>
			<td>$dato[codigodecontrol]</td>
			<td align='center'>".number_format($dato['totalfactura'],2)."</td>
			<td align='center'>".number_format($dato['totalice'],2)."</td>
			<td align='center'>".number_format($dato['importeexcento'],2)."</td>
			<td align='center'>".number_format($dato['importeneto'],2)."</td>
			<td align='center'>".number_format($dato['creditofiscal'],2)."</td>
			<td align='center'>$dato[tipo]</td>
			<td style='display:none'>$dato[idcuenta]</td>
			<td style='display:none'>$dato[idlibrocomprasiva]</td>
			<td style='display:none'>$dato[tipolibro]</td>
			<td style='display:none'>$dato[razon]</td>
		  </tr>
		  ";
		  $i++;	
		}
		echo "---";
	   exit();
   }
   
   if (isset($_GET['nit'])) {
	   $sql_nit = "SELECT nomrazonsociprove, numautorizacion, fechadeemision
          FROM librocomprasiva WHERE numdenitproveedor ='$_GET[nit]'
		   order by fechadeemision desc limit 1;";	   
	   echo $db->getCampo('nomrazonsociprove',$sql_nit)."---".$db->getCampo('numautorizacion',$sql_nit);
	   exit();
   }     
   
   
?>
