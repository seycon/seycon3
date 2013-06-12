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
	$logo = $_GET['logo'];
	$db = new MySQL();
	$codIngreso = $_GET['idingreso'];	 
	$sql = "select i.nombreasignado,i.receptor,i.idingresoprod,year(i.fecha)as 'anio',month(i.fecha)as 'mes'
	,day(i.fecha)as 'dia',i.moneda,i.cuentacontable,i.costooperativo ,i.caja
	,left(i.glosa,250)as 'glosa',a.nombre as 'almacen',left(s.nombrecomercial,25)as 'nombrecomercial',
	i.facproveedor,round(i.efectivo,2)as 'efectivo',round(i.diascredito,2)as 'diascredito',i.nroingresoprod,
	date_format(i.fechavencimiento,'%d/%m/%Y')as 'fechavencimiento',left(concat(t.nombre,' ',t.apellido),30)as 'usuario' 
	from ingresoproducto i,almacen a,usuario u,trabajador t,sucursal s  
	where i.idalmacen=a.idalmacen 
	and a.sucursal=s.idsucursal 
	and i.idusuario=u.idusuario 
	and u.idtrabajador=t.idtrabajador 
	and i.idingresoprod=".$codIngreso.";";	 
	$consulta = mysql_query($sql );
	$datosGenerales = mysql_fetch_array($consulta);
    $sql = "select left(cuenta,25)as 'cuenta' from plandecuenta 
	where codigo='$datosGenerales[cuentacontable]' and estado=1";
	$datoCuentaC = $db->arrayConsulta($sql);
	$sql = "select left(cuenta,25)as 'cuenta' from plandecuenta 
	where codigo='$datosGenerales[caja]' and estado=1";
	$datoCaja = $db->arrayConsulta($sql);
	
	
	function nextPage()
	{
	    for($i = 1; $i <= 11; $i++) {
			echo "<br />";
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ingreso de Productos</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>

<?php    
	$totalPrecio = 0;
	$totalImporte = 0;
	$totalCantidad = 0;
	$limite = "1";
	$maxFila = 38;
	$sql = "SELECT p.idproducto, left(p.nombre,20)as 'descripcion', round(ds.precio,4)as 'precio', ds.lote
	as 'fechavencimiento', round(ds.cantidadingresada,4)as 'cantidad', round(ds.total,4)as 'total'  
	FROM detalleingresoproducto ds, producto p WHERE  
	 ds.estado=1 and ds.idproducto = p.idproducto and ds.idingresoprod =".$codIngreso." order by ds.iddetalleingreso ";
	$cons = mysql_query($sql);
	$cantidad = mysql_num_rows($cons);
	$contadorFila = 0;  
	$sql = "select *from empresa";
	$empresa = $db->arrayConsulta($sql);
	
	if ($cantidad <= 10) {
		$claseBorde = "borde2";
		$clasePie = "session4_pie2";
		$claseSubPie = "session3_subPie";
	} else {
		$clasePie = "session4_pie";
		$claseBorde = "borde";  
		$claseSubPie = "session3_subPie1";
	}	
	
	while ($limite != "") {   
?>
<div style=" position : absolute;left:5%; top:20px;"></div>
<div class='<?php echo $claseBorde; ?>'></div>
<div class="session1_numTransaccion">
    <table width="100%" border="0">
      <tr><td align="center" height="3"></td></tr> 
      <tr><td class="session1_titulo_num"><?php echo "Nº $datosGenerales[nroingresoprod]"; ?></td></tr> 
    </table>
</div>
<div class="session1_sucursal">
    <table width="100%" border="0">
      <tr><td align="center" class="session1_tituloSucursal"><?php 	  
	 	  echo strtoupper($datosGenerales['nombrecomercial']); ?></td></tr>
    </table>
</div>
<div class="session1_logotipo">
<?php if ($logo == 'true'){ 
echo "<img src='../$empresa[imagen]' width='200' height='70'/>";
} ?></div>
<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1">INGRESO DE PRODUCTOS</td></tr> 
    </table>
</div> 
<br />

<table align="center">
<tr><td>

<div style="border:1px solid #000;width:90%;margin:0 auto;top:10px;">

<table width="90%" border="0" align="center">
  <tr>
    <td colspan="2"></td>
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
    <td align="right" class="negrita">T.C.:</td>
    <td>
	<?php  
		$sql = "select dolarcompra, ufv from indicadores order by idindicador desc limit 1";
		$valores  = $db->arrayConsulta($sql);
		echo $valores['dolarcompra'];
	?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="negrita"><?php echo ucfirst($datosGenerales['receptor']).":";?> </td>
    <td> <?php echo $datosGenerales['nombreasignado'];?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right" class="negrita">Doc.:</td>
    <td><?php echo $datosGenerales['facproveedor'];?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="negrita">Almacén:</td>
    <td><?php
	      echo $datosGenerales['almacen'];
	    ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right" class="negrita">Moneda:</td>
    <td><?php echo $datosGenerales['moneda'];?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="negrita">
    <?php if (isset($datoCuentaC['cuenta'])) 
	       echo "Cuenta Contable:";
		  else
		   echo "Caja:"; 
    ?>    
    </td>
    <td><?php 
	if (isset($datoCuentaC['cuenta']))
	  echo $datoCuentaC['cuenta'];
	else
	  echo $datoCaja['cuenta'];
	  ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr> 
</table>


<table width="100%" border="0" align="center" cellspacing="0px">
  <tr bgcolor="#E6E6E6">
    <th width="51" class="cabecera">Nº</th>
    <th width="380" class="cabecera">Descripción</td>
    <th width="89" class="cabecera">Lote</th>
    <th width="114" class="cabecera">Cantidad</th>
    <th width="116" class="cabecera">P.U.</th>
    <th width="108" class="cabecera" style="border-right:solid 1px #000;">Importe</th>
  </tr>
<?php  
    $contador = 0;
	while ( $detalleSolicitud = mysql_fetch_array($cons)) {
		$contador++;
		$contadorFila++;		
		$totalPrecio = $totalPrecio + $detalleSolicitud['precio'];
		$totalImporte = $totalImporte + ($detalleSolicitud['precio'] * $detalleSolicitud['cantidad']);
		$totalCantidad = $totalCantidad + $detalleSolicitud['cantidad'];
		if ($contador == $maxFila || $contador == $cantidad) {
		  echo "<tr>";
		  echo "<td style='border-bottom:solid 1px #000;border-left:solid 1px #000;text-align:center'>$contador</td>";
		  echo "<td class='contenido' style='border-bottom:solid 1px #000;'>$detalleSolicitud[descripcion]</td>";
		  echo "<td class='contenido' style='border-bottom:solid 1px #000;text-align:center'>
		  &nbsp;$detalleSolicitud[fechavencimiento]</td>";
		  echo "<td class='contenido' style='border-bottom:solid 1px #000;text-align:center'>$detalleSolicitud[cantidad]</td>";
		  echo "<td class='contenido' style='border-bottom:solid 1px #000;text-align:center'>"
		  .number_format($detalleSolicitud['precio'],4)."</td>";
		  echo "<td class='contenido' style='border-bottom:solid 1px #000;border-right:solid 1px #000;text-align:center'>"
		  .number_format($detalleSolicitud['total'],4)."</td>";
		  echo "</tr>";
		  break;
		} else {
		echo " <tr>";
			echo " <td style='border-bottom:solid 1px #000;border-bottom-style:dotted;
			border-left:solid 1px #000;text-align:center'>$contador</td>";
			echo " <td class='contenido'>$detalleSolicitud[descripcion]</td>";
			echo " <td class='contenido' style='text-align:center;'>&nbsp;$detalleSolicitud[fechavencimiento]</td>";
			echo " <td class='contenido' style='text-align:center;'>$detalleSolicitud[cantidad]</td>";
			echo " <td class='contenido' style='text-align:center;'>".number_format($detalleSolicitud['precio'],4)."</td>";
			echo " <td class='contenido' style='border-right:solid 1px #000;text-align:center;'>"
			.number_format($detalleSolicitud['total'],4)."</td>";
		echo "</tr>";
		}	
	}  
?>
  
  <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td class="negrita" style="text-align:right">Sub Totales:</td>
    <td style="border-bottom:solid 1px #000;border-left:solid 1px #000;text-align:center;background:#E6E6E6;">
	<?php echo number_format($totalCantidad,2);?></td>
    <td style="border-bottom:solid 1px #000;border-left:solid 1px #000;text-align:center;background:#E6E6E6;">
	<?php echo number_format($totalPrecio, 2);?></td>
    <td style="border-bottom:solid 1px #000;border-left:solid 1px #000;
    border-right:solid 1px #000;text-align:center;background:#E6E6E6;">
	<?php echo number_format($totalImporte, 2);?></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td ></td>
    <td></td>
    <td align="right" class="negrita">Costo Op.:</td>
    <td style="border-bottom:solid 1px #000;border-left:solid 1px #000;
    border-right:solid 1px #000;text-align:center;background:#E6E6E6;">
	<?php echo number_format($datosGenerales['costooperativo'], 2);?></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td ></td>
    <td></td>
    <td align="right" class="negrita">Total:</td>
    <td style="border-bottom:solid 1px #000;border-left:solid 1px #000;
    border-right:solid 1px #000;text-align:center;background:#E6E6E6;">
	<?php echo number_format(($totalImporte + $datosGenerales['costooperativo']), 2);?></td>
  </tr>
  <tr>
    <td align="right">SON:</td>
    <td colspan="5"><?php echo strtoupper(NumerosALetras(($totalImporte + $datosGenerales['costooperativo']))) ?></td>
  </tr>
</table>

</div>
</td></tr></table>

<table width="90%" border="0" align="center">
  <tr>
    <td colspan="2" class="textoGlosa" align="left">
	<?php echo "<strong>Glosa:</strong> ".$datosGenerales['glosa'];?>
    </td>
  </tr>
</table>

 <div class="<?php echo $claseSubPie;?>"> 
  <table width="90%" border="0" align="center">  
  <tr>    
    <td width="191" >&nbsp;</td>
    <td width="93">&nbsp;</td>
    <td width="193">&nbsp;</td>
    <td width="93">&nbsp;</td>    
    <td width="191">&nbsp;</td>
    <td width="266" align="center" class="negritaTitulo" colspan="2">Forma de Pago</td>
  </tr>
   <tr>   
    <td>&nbsp;</td>
    <td>&nbsp;</td>    
    <td>&nbsp;</td>
    <td>&nbsp;</td>    
    <td>&nbsp;</td>
    <td align="right" class="negrita">Efectivo:</td>
    <td><?php echo convertir($datosGenerales['efectivo']);?></td>
  </tr>
   <tr>   
    <td class="negrita">............................................</td>
    <td>&nbsp;</td>
    <td class="negrita">...........................................</td>
    <td>&nbsp;</td>    
    <td class="negrita">..........................................</td>
    <td align="right" class="negrita">Crédito:</td>
    <td><?php echo convertir($datosGenerales['diascredito']);?></td>
  </tr>
  <tr>   
    <td align="center" style="font-weight:bold">Elaborado Por</td>
     <td>&nbsp;</td>    
    <td align="center" style="font-weight:bold">Almacén</td>
    <td>&nbsp;</td>    
    <td align="center" style="font-weight:bold">Vo.Bo.</td>
    <td align="right" class="negrita">
    <?php 
	if ($datosGenerales['fechavencimiento'] != "00/00/0000") {
	    echo "Fecha Vto.:";
	}
	?>
    </td>
    <td><?php 
	if ($datosGenerales['fechavencimiento'] != "00/00/0000") {
	    echo $datosGenerales['fechavencimiento'];
	}
	?></td>
  </tr>
  </table> 
 </div>
 
<div class="<?php echo $clasePie; ?>"> 
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


<?php
	if ($contadorFila >= $cantidad ) {
	    $limite = "";
	} else {
		nextPage();	     	   
	}
 
}
?>


</body>
</html>

<?php
	$mpdf = new mPDF('utf-8','Letter'); 
	$content = ob_get_clean();
	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit;
?>