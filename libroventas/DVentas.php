<?php
     session_start();
	 include('../conexion.php');
	 $db = new MySQL();
	
	 if (!isset($_SESSION['softLogeoadmin'])) {
         header("Location: ../index.php");	
     } 
	 
	
      function getFacturasDisponibles()
	  {
		  $sql = "select numfactfinal from sucursal where idsucursal = '$_GET[sucursal]'";
		  $sucursal = $db->arrayConsulta($sql);
		  return $sucursal['disponible'];
	  }
	
	if (isset($_GET['transaccion']) && $_GET['transaccion'] == "disponible") {  
	      $sql = "select numfactfinal from sucursal where idsucursal = '$_GET[sucursal]'";
		  $sucursal = $db->arrayConsulta($sql);
		  if ($_GET['final'] > $sucursal['numfactfinal']) {
			  echo "limite";
		  } else {
			  echo "";  
		  }
		  exit();
	}
	
	 
	if (isset($_GET['transaccion']) && $_GET['transaccion'] == "insertar") {  
	  $fecha = $_GET['anio']."-".$_GET['mes']."-".$_GET['dia'];
	  $sql = "select numcinitcliente from libroventasiva where folio=$_GET[sucursal]
	   and fechadeemision=LAST_DAY('2011-02-10') and 
	  importeneto=0 and debitofiscal=0 and importeexcento=0 and totalice=0 and totalfactura=0 and
	  nomrazonsocicliente=0 and numfactura=0 and estado=1;";
	  $libro = $db->arrayConsulta($sql);
	  if (isset($libro['numcinitcliente']) && $libro['numcinitcliente'] == 0){
		  echo "-1"."---";
		  exit(); 
	  }	
	 
	  $sql = "select 
	  case when fechalimitemision<=current_date 
	  then 'si' else 'no' end as 'limite',
	  case when numfacturaactual>numfactfinal
	  then 'no' else 'si' end as 'emite',numfacturaactual,numautorizacion from sucursal
	   where idsucursal='$_GET[sucursal]';";
	  $datoSucursal = $db->arrayConsulta($sql);
	  
	  if ($datoSucursal['limite'] == "no" && $datoSucursal['emite'] == "si") {	
		  if ($_GET['cuenta'] == "servicios") {
		      $codcuenta = '';	
		  } else {
		      $codcuenta = '';	
		  }	
		  
		  $limite = $datoSucursal['numfacturaactual'];
		  if (isset($_GET['limiteregistro'])) {
			  $limite = $_GET['limiteregistro'];
			  echo "multiple---";
		  }
		  
		  for ($i = $datoSucursal['numfacturaactual']; $i <= $limite; $i++) {		     
			  $sql = "insert into libroventasiva(folio,fechadeemision,numcinitcliente
			  ,nomrazonsocicliente,numfactura,numautorizacion,codigodecontrol,
				  totalfactura,totalice,importeexcento,importeneto,debitofiscal
				  ,idtransaccion,transaccion,tipo,estadofactura,idcuenta,tipocuenta,idusuario,estado)
				  values ('$_GET[sucursal]','$fecha','$_GET[nit]','$_GET[razonsocial]'
				  ,'$i','$_GET[numeroautorizacion]',
				  '$_GET[codigocontrol]','$_GET[importetotal]','$_GET[ice]'
				  ,'$_GET[excento]','$_GET[neto]','$_GET[iva]',null,'Libro Ventas','LV',
				  '$_GET[estado]','$codcuenta','$_GET[cuenta]','$_SESSION[id_usuario]',1)";
			  $db->consulta($sql);
			  $sql = "update sucursal set numfacturaactual=numfacturaactual+1 where idsucursal='$_GET[sucursal]'";
			  $db->consulta($sql);		
		  }
		  
		  			   
		  echo $db->getMaxCampo("idlibroventasiva","libroventasiva")."---";
		  $sql = "select 
		  case when fechalimitemision<=current_date 
		  then 'si' else 'no' end as 'limite',
		  case when numfacturaactual>numfactfinal
		  then 'no' else 'si' end as 'emite',numfacturaactual,numautorizacion from sucursal
		   where idsucursal='$_GET[sucursal]';";
		  $datoSucursal = $db->arrayConsulta($sql);
		  $num = $datoSucursal['numfacturaactual']; 
		  echo $num."---";
		  echo $datoSucursal['numautorizacion']."---";
	  } else {
		  if ($datoSucursal['limite'] == "si") {
		   echo "fecha"."---";	
		  } else {
		   echo "numero"."---";	
		  }		
	  }	  
	  exit();
	}	 
  
    if (isset($_GET['transaccion']) && $_GET['transaccion'] == "modificar") { 
		$sql = "select  case when fechalimitemision<=current_date 
		then 'si' else 'no' end as 'limite',
		case when numfacturaactual>numfactfinal
		then 'no' else 'si' end as 'emite',numfacturaactual,numautorizacion 
		from sucursal where idsucursal='$_GET[sucursal]'";
		$datoSucursal = $db->arrayConsulta($sql);
		if ($_GET['cuenta'] == "servicios") {
		    $codcuenta = "";	
		} else {
		    $codcuenta = "";	
		}	
		$fecha = $_GET['anio']."-".$_GET['mes']."-".$_GET['dia'];
		$idlibro = $_GET['idtransaccion'];
		$sql = "update libroventasiva set folio='$_GET[sucursal]',fechadeemision='$fecha',
		 numcinitcliente='$_GET[nit]',nomrazonsocicliente='$_GET[razonsocial]'
		 ,numfactura='$_GET[numerofactura]'
		 ,numautorizacion='$_GET[numeroautorizacion]',codigodecontrol='$_GET[codigocontrol]'
		 ,idcuenta='$codcuenta',tipocuenta='$_GET[cuenta]',
		 totalfactura='$_GET[importetotal]',totalice='$_GET[ice]',importeexcento='$_GET[excento]'
		 ,estadofactura='$_GET[estado]'
		 ,importeneto='$_GET[neto]',debitofiscal='$_GET[iva]'
		 ,idusuario='$_SESSION[id_usuario]' where idlibroventasiva=$idlibro;";
		$db->consulta($sql);	
		if ($datoSucursal['limite'] == "si" || $datoSucursal['emite'] == "no" ) {
			if ($datoSucursal['limite'] == "si") {
			    echo "fecha"."---";	
			} else {
			    echo "numero"."---";	
			}
		} else {	   
		    echo $idlibro."---";
		    $num = $datoSucursal['numfacturaactual']; 
		    echo $num."---";
		    echo $datoSucursal['numautorizacion']."---";
		}
		exit();
    }	 
	 
	if (isset($_GET['transaccion']) && $_GET['transaccion'] == "eliminar") {
		$idlibro = $_GET['idlibro'];
		$sql = "update libroventasiva set estado=0 where idlibroventasiva=$idlibro;";
		$db->consulta($sql);
		echo $sql;
		exit();	  
	}
	 
  if (isset($_GET['transaccion']) && $_GET['transaccion'] == "sinMovimiento") {
	  $fecha = $_GET['ano']."-".$_GET['mes']."-".date("d");  
	  
	  $sql = "select count(*) as 'cantidad' from libroventasiva
	   where folio=$_GET[sucursal] and month(fechadeemision)=$_GET[mes]
		and year(fechadeemision)=$_GET[ano] and  estado=1;";
	  $venta = $db->arrayConsulta($sql);
	  $tipo = -1;
	  $idlibro = "";
	  if ($venta['cantidad'] > 0) {
		echo $tipo."---";
		echo $fecha."---";
		echo $idlibro."---";
		exit();
	  }	
	  
	  $sql = "select LAST_DAY('$fecha') as 'fechaFinal';";
	  $fechaFinal = $db->arrayConsulta($sql);
	  $sqlCompras = "insert into libroventasiva(folio,fechadeemision,numcinitcliente
	  ,nomrazonsocicliente,numfactura,numautorizacion,codigodecontrol,
			  totalfactura,totalice,importeexcento,importeneto,debitofiscal
			  ,idtransaccion,transaccion,tipo,estadofactura,idcuenta,tipocuenta,idusuario,estado)
			  values ('$_GET[sucursal]','$fechaFinal[fechaFinal]','0','0','0','0',
			  '0','0','0','0','0','0',null,'Libro Ventas','LV','0','0','0','$_SESSION[id_usuario]',1)";
	  $db->consulta($sqlCompras);
	  $sql = "select idlibroventasiva,date_format(fechadeemision,'%d/%m/%Y')as 'fechadeemision'
	   from libroventasiva where folio=$_GET[sucursal] and fechadeemision=LAST_DAY('$fecha');";
	  $libro = $db->arrayConsulta($sql);
	  $fecha = $libro['fechadeemision'];
	  $idlibro = $libro['idlibroventasiva'];
	  $tipo = 1;	
	  echo $tipo."---";
	  echo $fecha."---";
	  echo $idlibro."---";
	  exit();		  
  }
	 
   if (isset($_GET['transaccion']) && $_GET['transaccion'] == "consultar") {	   
	   $sql = "select case when fechalimitemision<=current_date 
               then 'si' else 'no' end as 'limite',
               case when numfacturaactual>numfactfinal
               then 'no' else 'si' end as 'emite',numautorizacion,numfacturaactual
			    from sucursal where idsucursal = '$_GET[sucursal]'";
	   $sucursal = $db->arrayConsulta($sql);
	   if ($sucursal['limite'] == "si" || $sucursal['emite'] == "no") {
		 	if ($sucursal['limite'] == "si") {
				echo "fecha"."---";
			} else {
				echo "numero"."---";
			}
		} else {
		   echo $sucursal['numfacturaactual']."---";
	   }	   	
	   
	   echo $sucursal['numautorizacion']."---";	   
	   $sql = "SELECT DATE_FORMAT(fechadeemision, '%d/%m/%Y')as 'fecha'
	   ,numcinitcliente,left(nomrazonsocicliente,25) as 'nomrazon',numfactura,
			   numautorizacion,codigodecontrol,totalfactura,totalice,importeexcento
			   ,importeneto,estadofactura,nomrazonsocicliente as 'razon',
			   debitofiscal,idtransaccion,tipo,idcuenta,tipocuenta,idlibroventasiva
			   ,nomrazonsocicliente,fechadeemision from libroventasiva 
			   where MONTH(fechadeemision) = '$_GET[mes]' and YEAR(fechadeemision) = '$_GET[ano]' 
			   and folio = '$_GET[sucursal]' and estado=1 ORDER BY numfactura desc ";
	   $libro = $db->consulta($sql);
	   $i = $db->getnumRow($sql);
	   while ($dato = mysql_fetch_array($libro)) {
		  if ($i % 2 == 0)
		   $color = "background-color:#CCC;line-height:14px;font-size:11px";
		  else
		   $color = "background-color:#FFF;line-height:14px;font-size:11px"; 				
		  echo "
		  <tr style='$color'>
			<td><img src='../iconos/edit.png' style='cursor:pointer' 
			title='Modificar' onclick='editar(this)'/></td>
			<td><img src='../iconos/borrar.gif' style='cursor:pointer' 
			title='Eliminar' onclick='eliminarItem(this)'/></td>
			<td>$i</td>
			<td>$dato[fecha]</td>
			<td style='display:none'>$dato[numcinitcliente]</td>
			<td>$dato[nomrazon]</td>
			<td>$dato[numfactura]</td>
			<td>$dato[numautorizacion]</td>
			<td>$dato[codigodecontrol]</td>
			<td align='center'>".number_format($dato['totalfactura'],2)."</td>
			<td align='center'>".number_format($dato['totalice'],2)."</td>
			<td align='center'>".number_format($dato['importeexcento'],2)."</td>
			<td align='center'>".number_format($dato['importeneto'],2)."</td>
			<td align='center'>".number_format($dato['debitofiscal'],2)."</td>
			<td align='center'>$dato[tipo]</td>
			<td style='display:none'>$dato[tipocuenta]</td>
			<td style='display:none'>$dato[idlibroventasiva]</td>
			<td style='display:none'>$dato[estadofactura]</td>
			<td style='display:none'>$dato[razon]</td>
		  </tr>
		  ";
		  $i--;	
	  }
			echo "---";
	   exit();
   }
   
   if (isset($_GET['nit'])) {
	   $sql_nit = "SELECT nomrazonsocicliente, numautorizacion, fechadeemision
          FROM libroventasiva WHERE numcinitcliente ='$_GET[nit]'
		   order by fechadeemision desc limit 1;";	   
	   echo $db->getCampo('nomrazonsocicliente',$sql_nit)."---".$db->getCampo('numautorizacion',$sql_nit);
	   exit();
   }        
   
?>