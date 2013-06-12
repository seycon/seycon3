<?php
	session_start();
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: ../index.php");	
	}
	ob_start();
	include("../MPDF53/mpdf.php");
	include('../conexion.php');
	include('../aumentaComa.php');
	include('../reportes/literal.php');
	  $db = new MySQL();
	  $logo = $_GET['logo'];
	  $codSolicitud = $_GET['idsolicitud'];	 
	
	$sql = "select p.tiempodecredito,p.idproveedor,s.idsolicitud
	,year(s.fecha)as 'anio',month(s.fecha)as 'mes',day(s.fecha)as 'dia',s.moneda,
	left(s.glosa,250)as 'glosa',a.nombre as 'almacen',p.nombre as 'proveedor'
	,s.contacto,left(concat(t.nombre,' ',t.apellido),30)as 'usuario'
	,left(sc.nombrecomercial,25)as 'nombrecomercial'  
	from solicitud s,proveedor p,almacen a,usuario u,trabajador t,sucursal sc 
	  where s.idalmacen=a.idalmacen 
	  and sc.idsucursal=a.sucursal 
	  and s.idproveedor=p.idproveedor 
	  and u.idusuario=s.idusuario 
	  and u.idtrabajador=t.idtrabajador 
	  and s.idsolicitud=".$codSolicitud.";";
	  $datosGenerales = $db->arrayConsulta($sql);
	  
   function nextPage()
   {
	for ($i = 1; $i <= 11; $i++) {
	    echo "<br />";	
	}
   }
	 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reporte de Solicitud Productos</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
<?php
	$totalPrecio  = 0.00;
	$totalImporte = 0.00;  
	$totalcantidad = 0;
	
	$limite = "1";
	$maxFila = 35;
	$cons = mysql_query("SELECT p.idproducto, left(p.nombre,45)as 'descripcion'
	, round(ds.precio,2)as 'precio', p.unidaddemedida, ds.cantidad, round(ds.total,2)as 'total' 
	FROM detallesolicitud ds, producto p, solicitud s 
	WHERE ds.idsolicitud = s.idsolicitud AND ds.idproducto = p.idproducto
	AND s.idsolicitud =".$codSolicitud);
	$cantidad = mysql_num_rows($cons);
	$contadorFila = 0;  
	$sql = "select *from empresa";
	$empresa = $db->arrayConsulta($sql); 
	
	if ($cantidad <= 10) {
		 // $claseBorde = "borde2";
		 // $clasePie = "session4_pie2";
		 // $claseSubPie = "session3_subPie2";
		 $claseBorde = "borde";   
		 $clasePie = "session4_pie";
		 $claseSubPie = "session3_subPie";
	} else {
		 $claseBorde = "borde";   
		 $clasePie = "session4_pie";
		 $claseSubPie = "session3_subPie";
	}
	
	
	while ($limite != "") {  
?>

<div class="<?php echo $claseBorde;?>"></div>
 
<div class="session1_numTransaccion">
 <table width="100%" border="0">
   <tr><td align="center" height="3"></td></tr> 
   <tr><td class="session1_titulo_num"><?php echo "Nº $datosGenerales[idsolicitud]"; ?></td></tr> 
 </table>
</div>
<div class="session1_sucursal">
    <table width="100%" border="0">
      <tr><td align="center" class="session1_tituloSucursal"><?php 	  
	 	  echo strtoupper($datosGenerales['nombrecomercial']); ?></td></tr>
    </table>
</div>
<div class="session1_logotipo">
<?php if ($logo == 'true'){ echo "<img src='../$empresa[imagen]' width='200' height='70'/>";} ?></div>
<div class="session1_contenedorTitulos">
  <table width="100%" border="0">
   <tr><td align="center" class="session1_titulo1">SOLICITUD DE PRODUCTOS</td></tr> 
  </table>
</div>
 

<table align="center">
<tr><td>


<div style="border:solid 1px #000;width:90%;margin:0 auto;">

<table width="90%" border="0" align="center">
  <tr>
    <td colspan="2">
       
    </td>
    <td width="165">&nbsp;</td>
    <td width="115">&nbsp;</td>
    <td width="115">&nbsp;</td>
    <td width="115">&nbsp;</td>
    <td width="115">&nbsp;</td>
    <td width="115">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" align="center">
  <tr>
    <td width="153">&nbsp;</td>
    <td width="322">&nbsp;</td>
    <td width="19">&nbsp;</td>
    <td width="9">&nbsp;</td>
    <td width="14">&nbsp;</td>
    <td width="128">&nbsp;</td>
    <td width="137">&nbsp;</td>
    <td width="85">&nbsp;</td>
  </tr>
  <tr>
 
    <td align="right"><div align="right" class="negrita">Fecha:</div></td>
    <td><?php
	      echo $datosGenerales['dia']." de ".mes($datosGenerales['mes'])." del ".$datosGenerales['anio'];
	    ?>    
    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right"><div align="right" class="negrita">T.C.:</div></td>
    <td><?php  
	$indicador = mysql_query("select dolarcompra, ufv from indicadores order by idindicador desc limit 1");
	$valores  = mysql_fetch_array($indicador);
	echo  $valores['dolarcompra'];
	?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="negrita">Proveedor:</td>
    <td><?php echo $datosGenerales['proveedor'];?>   </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right"><div align="right" class="negrita">Almacén:</div></td>
    <td><?php 
		 echo $datosGenerales['almacen'];
	?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="negrita">Contacto:</td>
    <td><?php echo $datosGenerales['contacto'];?> </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right" class="negrita">Moneda:</td>
    <td><?php echo $datosGenerales['moneda'];?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

</table>


<table width="120%" border="0" align="center" cellspacing="0px">
  <tr bgcolor="#E6E6E6">
    <td width="51" class="cabecera"><div align="center" >Nº</div></td>
    <td width="400" class="cabecera">Descripción</td>
    <td width="109" class="cabecera"><div align="center" class="negrita">Unidad de Medida</div></td>
    <td width="114" class="cabecera"><div align="center" class="negrita">Cantidad</div></td>
    <td width="116" class="cabecera"><div align="center" class="negrita">Precio/Unitario</div></td>
    <td width="108" class="cabecera" style="border-right:solid 1px #000;"><div align="center" class="negrita">Importe</div></td>
  </tr>
  <?php
  
   $contador = 0;
   while ( $detalleSolicitud = mysql_fetch_array($cons)) {
    $contador++;	
	$contadorFila++; 
	
	$totalPrecio = $totalPrecio + $detalleSolicitud['precio'];
	$totalImporte = $totalImporte + $detalleSolicitud['total'];
	$totalcantidad = $totalcantidad + $detalleSolicitud['cantidad'];
	
	 if ($contador == $maxFila || $contador == $cantidad ) {
	   echo "<tr>";
        echo "<td style='border-bottom:solid 1px #000;border-left:solid 1px #000;text-align:center'>$contador</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;'>$detalleSolicitud[descripcion]</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;text-align:center'>&nbsp;
		$detalleSolicitud[unidaddemedida]</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;text-align:center'>$detalleSolicitud[cantidad]</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;text-align:center'>"
		.convertir($detalleSolicitud['precio'])."</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;border-right:solid 1px #000;text-align:center'>"
		.convertir($detalleSolicitud['total'])."</td>";
       echo "</tr>";
	   break;
	 } else {
      echo " <tr>";
          echo " <td style='border-bottom:solid 1px #000;border-bottom-style:dotted;border-left:
		  solid 1px #000;text-align:center'>$contador</td>";
          echo " <td class='contenido'>$detalleSolicitud[descripcion]</td>";
          echo " <td class='contenido' style='text-align:center;'>&nbsp;$detalleSolicitud[unidaddemedida]</td>";
          echo " <td class='contenido' style='text-align:center;'>$detalleSolicitud[cantidad]</td>";
          echo " <td class='contenido' style='text-align:center;'>".convertir($detalleSolicitud['precio'])."</td>";
          echo " <td class='contenido' style='border-right:solid 1px #000;text-align:center;'>"
		  .convertir($detalleSolicitud['total'])."</td>";
      echo "</tr>";
	}
  
  
   }
  
  ?>
  
  <tr>
   <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td class="negrita" align="right" >Totales</td>
    <td style="border-bottom:solid 1px #000;border-left:solid 1px #000;text-align:center;background:#E6E6E6;">
	<?php echo convertir($totalcantidad);?></td>
    <td style="border-bottom:solid 1px #000;border-left:solid 1px #000;text-align:center;background:#E6E6E6;">
	<?php echo convertir(number_format($totalPrecio,2));?></td>
    <td style="border-bottom:solid 1px #000;border-left:solid 1px #000;border-right:solid 1px #000
    ;text-align:center;background:#E6E6E6;">
	<?php echo convertir(number_format($totalImporte,2));?></td>
  </tr>
  <tr>
  <td align="right">Son:</td>
  <td colspan="5"><?php echo strtoupper(NumerosALetras($totalImporte)) ?></td>
  </tr>

</table>

</div>
</td></tr></table>

<?php
   if ($contadorFila >= $cantidad ) {
	   $limite = "";
   } else {
	   nextPage();   	   
   }
  }
?>

 <table width="90%" border="0" align="center">
  <tr>
    <td width="133" class="negrita" align="left"></td>
    <td width="749"></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo "<strong>Glosa: </strong>".$datosGenerales['glosa'];?></td>
  </tr>
</table>

 <div class="<?php echo $claseSubPie;?>"> 
 <table width="93%" border="0" align="center">
  <tr>
    <td width="153">&nbsp;</td>
    <td width="191" class="negrita">............................................</td>
    <td width="93">&nbsp;</td>
    <td width="193" class="negrita">...........................................</td>
    <td width="141">&nbsp;</td>
    <td width="191" class="negrita">..........................................</td>
    <td width="266">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold">Elaborado Por</td>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold">Autorizado</td>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold">Vo.Bo.</td>
    <td>&nbsp;</td>
  </tr>
</table> 
 </div>

 <div class="<?php echo $clasePie;?>"> 
  <table width="93%" border="0" align="center">
  <tr>
    <td width="120" align="right">Elaborado por:</td>
    <td width="324"><?php echo $datosGenerales['usuario'];?></td>
    <td width="93">&nbsp;</td>
    <td width="189">&nbsp;</td>
    <td width="201">&nbsp;</td>
    <td width="170" >Impreso: <?php echo date("d/m/Y");?></td>
    <td width="130">Hora: <?php echo date("H:i:s");?></td>
  </tr>
  </table>
 </div> 
 
</body>
</html>

<?php
	$mpdf=new mPDF('utf-8','Letter'); 
	$content = ob_get_clean();
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit;
?>