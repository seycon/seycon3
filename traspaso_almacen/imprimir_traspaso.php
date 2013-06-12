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
	$codtraspaso = $_GET['idtraspaso'];
	$logo = $_GET['logo'];	
	$cprecios = $_GET['cprecios']; 
	$sql = "select  t.idtraspaso,year(t.fecha)as 'anio',month(t.fecha)as 'mes',day(t.fecha)as 'dia',
	 t.moneda,left(t.glosa,250)as 'glosa',left(solicitado,20) as 'solicitado' 
	,o.nombre as 'almacenorigen',left(s.nombrecomercial,25)as 'nombrecomercial'
	 ,d.nombre as 'almacendestino',left(concat(tb.nombre,' ',tb.apellido),30)as 'usuario' 
	 from traspaso t,almacen o,almacen d,usuario u,trabajador tb,sucursal s  
	 where t.idalmacenorigen=o.idalmacen 
	 and o.sucursal=s.idsucursal  
	 and t.idusuario=u.idusuario 
	 and tb.idtrabajador=u.idtrabajador 
	 and d.idalmacen=t.idalmacendestino 
	 and t.idtraspaso=$codtraspaso;";	   
	$datosGenerales = $db->arrayConsulta($sql);
	
	function obtenerCadenaConsulta($codigo, $tipo)
	{
	    return "select nombre from $tipo where id".$tipo."=$codigo";
	}
 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Traspaso de Almacen</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
<?php    
  $totalPrecio = 0;
  $totalImporte = 0;
  $totalCantidad = 0;
  $limite = "1";
  $maxFila = 35;
  $sql = "SELECT p.idproducto, left(p.nombre,30) as 'descripcion' , round(ds.precio,4)as 'precio'
  ,ds.lote as 'fechavencimiento', ds.cantidad, round(ds.total,4)as 'total'
   FROM detalletraspaso ds, producto p, traspaso t WHERE ds.idtraspaso = t.idtraspaso AND ds.idproducto = p.idproducto
   AND t.idtraspaso =".$codtraspaso;
  $cons = mysql_query($sql);
  $cantidad = mysql_num_rows($cons);
  $contadorFila = 0;  
  $sql = "select *from empresa";
  $empresa = $db->arrayConsulta($sql);
  
   if ($cantidad <= 10) {
       $claseBorde = "borde2";
       $clasePie = "session4_pie2";
       $claseSubPie = "session3_subPie2";
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
     <tr><td class="session1_titulo_num"><?php echo "Nº $datosGenerales[idtraspaso]"; ?></td></tr> 
    </table>
</div>
<div class="session1_sucursal">
    <table width="100%" border="0">
      <tr><td align="center" class="session1_tituloSucursal"><?php 	  
	 	  echo strtoupper($datosGenerales['nombrecomercial']); ?></td></tr>
    </table>
</div>
<div class="session1_logotipo"><?php if ($logo == 'true'){ echo "<img src='../$empresa[imagen]' width='200' height='70'/>";} ?></div>
<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1">TRASPASO DE PRODUCTOS</td></tr> 
    </table>
</div><br />

<table align="center">
<tr><td>

<div style="border:solid 1px #000;width:100%;margin:0 auto;">
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
    <td>
	<?php
	    echo $datosGenerales['dia']." de ".mes($datosGenerales['mes'])." del ".$datosGenerales['anio'];
	?>    
    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right"><div align="right" class="negrita">T.C.:</div></td>
    <td>
	<?php  
	    $indicador = mysql_query("select dolarcompra, ufv from indicadores order by idindicador desc limit 1");
	    $valores  = mysql_fetch_array($indicador);
	    echo  $valores['dolarcompra'];
	?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="negrita">Almacén Origen:</td>
    <td><?php echo $datosGenerales['almacenorigen'];  ?>    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right" class="negrita">Moneda:</td>
    <td><?php echo $datosGenerales['moneda'];  ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right"><div align="right" class="negrita">Almacén Destino:</div></td>
    <td>  <?php echo $datosGenerales['almacendestino'];?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right" class="negrita">Solicitado por:</td>
    <td><?php echo $datosGenerales['solicitado'];  ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right"></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right" class="negrita"></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

<table width="100%" border="0" align="center" cellspacing="0px">
  <tr bgcolor="#E6E6E6">
    <td width="51" class="cabecera"><div align="center" >Nº</div></td>
    <td width="389" class="cabecera">Descripción</td>
    <td width="80" class="cabecera"><div align="center" class="negrita">Lote</div></td>
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
	$totalImporte = $totalImporte + ($detalleSolicitud['precio'] * $detalleSolicitud['cantidad']);
	$totalCantidad = $totalCantidad + $detalleSolicitud['cantidad'];
	if ($cprecios == 'true') {
		$precioD = number_format($detalleSolicitud['precio'],4);
		$totalD = number_format(($detalleSolicitud['precio'] * $detalleSolicitud['cantidad']),4);
	} else {
		$precioD = "--";
		$totalD = "--";		
	}
	
	 if ($contador == $maxFila || $contador == $cantidad) {
	   echo "<tr>";
        echo "<td style='border-bottom:solid 1px #000;border-left:solid 1px #000;text-align:center'>$contador</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;'>$detalleSolicitud[descripcion]</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;text-align:center'>&nbsp;
		$detalleSolicitud[fechavencimiento]</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;text-align:center'>"
		.number_format($detalleSolicitud['cantidad'],2)."</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;text-align:center'>".$precioD."</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;border-right:solid 1px #000;text-align:center'>".
		$totalD."</td>";
       echo "</tr>";
	 } else {
      echo " <tr>";
          echo " <td style='border-bottom:solid 1px #000;border-bottom-style:dotted;border-left:solid 1px #000;
		  text-align:center'>$contador</td>";
          echo " <td class='contenido'>$detalleSolicitud[descripcion]</td>";
          echo " <td class='contenido' style='text-align:center;'>&nbsp;$detalleSolicitud[fechavencimiento]</td>";
          echo " <td class='contenido' style='text-align:center;'>".number_format($detalleSolicitud['cantidad'],2)."</td>";
          echo " <td class='contenido' style='text-align:center;'>".$precioD."</td>";
          echo " <td class='contenido' style='border-right:solid 1px #000;text-align:center;'>".$totalD."</td>";
      echo "</tr>";
	}
   }
  ?>
  
  <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>

    <td class="negrita" style="text-align:right">Totales</td>
    <td style="border-bottom:solid 1px #000;border-left:solid 1px #000;text-align:center;background:#E6E6E6;">
	<?php echo number_format($totalCantidad,2);?></td>
    <td style="border-bottom:solid 1px #000;border-left:solid 1px #000;text-align:center;background:#E6E6E6;">
	<?php echo convertir(number_format($totalPrecio,2));?></td>
    <td style="border-bottom:solid 1px #000;border-left:solid 1px #000;border-right:solid 1px #000;
    text-align:center;background:#E6E6E6;">
    <?php echo convertir(number_format($totalImporte,2));?>
    </td>
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
	echo "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";   	   
 }
}
?>

 <table width="90%" border="0" align="center">
  <tr>
    <td width="133" class="negrita" align="left"></td>
    <td width="749"></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo "<strong>Glosa:</strong> ".$datosGenerales['glosa'];?></td>
  </tr>
</table>
 
  <div class="<?php echo $claseSubPie; ?>"> 
  <table width="90%" border="0" align="center">
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
    <td align="center" style="font-weight:bold">Contabilidad</td>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold">Destino</td>
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
	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit;
?>