<?php
	session_start();
	if (!isset($_SESSION['softLogeoadmin'])) {
	    header("Location: ../index.php");	
	}
	ob_start();
	include("../MPDF53/mpdf.php");
	include("../conexion.php");
	include('../reportes/literal.php');
	$db = new MySQL();
	$logo = $_GET['logo'];
	$sql = " select idalmacen,date_format(fechainicio,'%d/%m/%Y') as 'desde',"
	    ."date_format(fechafinal,'%d/%m/%Y') as 'hasta' from inventario where idinventario=".$_GET['idinventario'];
	$datoInventario = $db->arrayConsulta($sql);
	
	$idalmacen = $datoInventario['idalmacen'];	
	$fechaInicial = $db->GetFormatofecha($datoInventario['desde'],"/");
    $fechaFinal = $db->GetFormatofecha($datoInventario['hasta'],"/");
	$fechaInicio = explode("/",$fechaInicial);
    $fechaFin = explode("/",$fechaFinal);
	$sql = "select imagen,left(nombrecomercial,25)as 'nombrecomercial',nit from empresa;";
	$datoGeneral = $db->arrayConsulta($sql);
    $sql = "select idalmacen, nombre from almacen where idalmacen=$idalmacen";
	$almacen = $db->arrayConsulta($sql);
	$sql = "select glosa,left(supervisor,15)as 'supervisor',left(administrador,15)as 'administrador'
	,left(concat(t.nombre,' ',t.apellido),15)as 'usuario' from inventario i,trabajador t,
	usuario u where i.idusuario=u.idusuario and u.idtrabajador=t.idtrabajador and	i.fechafinal='$fechaFinal' and i.estado=1 
	and i.idalmacen=$idalmacen;";
    $observacion = $db->arrayConsulta($sql);
   
	function setCabecera($desde, $hasta)
	{
		echo "
		<tr>
		<td>&nbsp;</td>   
		<td>&nbsp;</td>
		<td colspan='4' class='borderSubCabecera'>Del $desde al $hasta</td>
		<td colspan='4' class='borderSubCabecera_cerrar'>Saldo a la fecha $hasta</td>
		<td colspan='3' >&nbsp;</td>
		</tr>
	  <tr>
		<td width='4%'>&nbsp;</td>
		<td width='22%'>&nbsp;</td>
		<td colspan='2' class='borderSubCabecera2'>Ingresos</td>
		<td colspan='2' class='borderSubCabecera2'>Ventas</td>
		<td colspan='2' class='borderSubCabecera2'>Inv. Sistema</td>
		<td colspan='2' class='borderSubCabecera2'>Inv. Fisico</td>
		<td colspan='2' class='borderSubCabecera2'>Diferencia</td>
		<td colspan='1' class='borderSubCabecera_cerrar2'>F/S</td>
		</tr>
	  <tr>
		<td class='bordeCabecera'>Nº</td>
		<td class='bordeCabecera'>Productos</td>
		<td width='6%' class='bordeCabecera'>U.M.</td>
		<td width='6%' class='bordeCabecera'>U.A.</td>
		<td width='6%' class='bordeCabecera'>U.M.</td>
		<td width='6%' class='bordeCabecera'>U.A.</td>
		<td width='6%' class='bordeCabecera'>U.M.</td>
		<td width='6%' class='bordeCabecera'>U.A.</td>
		<td width='6%' class='bordeCabecera'>U.M.</td>
		<td width='6%' class='bordeCabecera'>U.A.</td>
		<td width='6%' class='bordeCabecera'>U.M.</td>
		<td width='6%' class='bordeCabecera'>U.A.</td>
		<td width='3%' class='bordeCabecera_cerrar'>&nbsp;</td>
		</tr>
		"; 
	}
 
	function getCantidades($cantidad, $conversion)
	{
		$total = array(0,0);
		if ($cantidad != "") {
		    $dato = explode(".",$cantidad);
		    $total[0] = $dato[0];
		    $cant = "0.".$dato[1];
		    $total[1] = (float) $cant * $conversion;
		}
		return $total;	
	}
	
	function setData($num, $dato, $tipo)
	{
		$clase = ""; 
		$estilo = "";	
		if ($num % 2 == 0) {
		   $clase = "cebra";
		}
		
		if ($tipo == "cierre") {
		   $estilo = "border-bottom:1.5px solid;"; 
		}
		
		$ingresos = getCantidades($dato['Ingresos'], $dato['conversiones']);
		$ventas = getCantidades($dato['Ventas'], $dato['conversiones']);
		$inventarioS = getCantidades($dato['InvSistemas'], $dato['conversiones']);
		$inventarioF = getCantidades($dato['InvFisico'], $dato['conversiones']);		
		$diferenciaUM = round($inventarioF[0], 2) - round($inventarioS[0], 2); 
		$diferenciaUA = round($inventarioF[1], 2) - round($inventarioS[1], 2); 
		$tipo = "";
		if (round($diferenciaUM, 2) < 0) {
		 $tipo = "F";	
		} else {
			if (round($diferenciaUM, 2) > 0) {
				$tipo = "S";
			} else {
				if (round($diferenciaUA, 2) < 0) {
					$tipo = "F";
				} else {
					if (round($diferenciaUA, 2) > 0)
						$tipo = "S";
				}
			}
		}
		echo "
		 <tr class=$clase>
		  <td class='session3_datos1_1' style='".$estilo."'>$num</td>
  		  <td class='session3_datos1' style='".$estilo."'>$dato[nombre]</td>
		  <td class='session3_datos1' style='".$estilo."'>".number_format($ingresos[0],2)."</td>
		  <td class='session3_datos2' style='".$estilo."'>".number_format($ingresos[1],2)."</td>
		  <td class='session3_datos1' style='".$estilo."'>".number_format($ventas[0],2)."</td>
		  <td class='session3_datos2' style='".$estilo."'>".number_format($ventas[1],2)."</td>
		  <td class='session3_datos1' style='".$estilo."'>".number_format($inventarioS[0],2)."</td>
		  <td class='session3_datos2' style='".$estilo."'>".number_format($inventarioS[1],2)."</td>
		  <td class='session3_datos1' style='".$estilo."'>".number_format($inventarioF[0],2)."</td>
		  <td class='session3_datos2' style='".$estilo."'>".number_format($inventarioF[1],2)."</td>
		  <td class='session3_datos1' style='".$estilo."'>".number_format($diferenciaUM,2)."</td>
		  <td class='session3_datos2' style='".$estilo."'>".number_format($diferenciaUA,2)."</td>
		  <td class='session3_datos3' align='center' style='".$estilo."'>".$tipo."</td>
		</tr>
		";
	}
 
	function setAlmacen($almacen)
	{
	   echo "
	   <table width='100%' border='0'>
		 <tr><td width='7%' class='titulo_1'>Almacén:</td> 
		 <td width='93%' class='titulo_datos'>$almacen</td>
		 </tr>
	   </table>"; 	 
	}
 
	function nextPage()
	{
	   for ($m = 1; $m < 55; $m++) {
		   echo "<br />";
	   } 
	}
 
 
 function getConsulta($desde, $hasta, $almacen, $condicion)
 {
     $sql = "	 
	SELECT 
	pp.idproducto, a.nombre AS  'almacen', left(pp.nombre,25)as 'nombre', 
	(
	SELECT 
	round((SUM( IF( d.unidadmedida = pi.unidaddemedida, d.cantidadingresada, d.cantidadingresada / pi.conversiones ) ) ),4)
	 as 'Ingresos' 
	FROM  producto pi, detalleingresoproducto d, ingresoproducto i, almacen a 
	WHERE pi.idproducto = d.idproducto AND d.idingresoprod = i.idingresoprod 
	AND a.idalmacen = i.idalmacen AND a.idalmacen =$almacen 
	AND d.estado =1 and i.fecha>='$desde' 
	and i.fecha<='$hasta' and pi.idproducto=pp.idproducto and i.estado=1 
	GROUP BY pi.idproducto 
	) as 'Ingresos',
	(
	round(
	COALESCE((
	
	SELECT 
	SUM( IF( d.unidadmedida = p.unidaddemedida, d.cantidad, d.cantidad / p.conversiones ) ) AS 'Total_Venta' 
	FROM producto p, detallenotaventa d,detalleingresoproducto di,notaventa n 
	, almacen a,ingresoproducto i 
	WHERE 
	p.idproducto = d.idproducto AND d.idnotaventa = n.idnotaventa 
	and d.iddetalleingreso = di.iddetalleingreso and di.idingresoprod = i.idingresoprod 
	AND a.idalmacen = i.idalmacen AND a.idalmacen =$almacen 
	and n.fecha>='$desde' and n.fecha<='$hasta' 
	and p.idproducto=pp.idproducto and n.estado=1 
	GROUP BY p.idproducto 
	),0) + 
	
	COALESCE((
	SELECT 
	SUM( IF( d.unidadmedida = p.unidaddemedida, d.cantidad, d.cantidad / p.conversiones ) ) AS 'Total_VentaR' 
	FROM producto p, detallerequerimiento d,detalleingresoproducto di,detalleatencion da,atencion at  
	, almacen a,ingresoproducto i 
	WHERE 
	 da.idatencion = at.idatencion  
	and d.iddetalleatencion = da.iddetalleatencion and d.iddetalleingreso = di.iddetalleingreso 
	and di.idingresoprod = i.idingresoprod and p.idproducto = di.idproducto 
	AND a.idalmacen = i.idalmacen AND a.idalmacen =$almacen 
	and date(at.fecha)>='$desde' and date(at.fecha)<='$hasta' 
	and da.estado=1 and p.idproducto=pp.idproducto 
	GROUP BY p.idproducto 
	),0),4)
	
	) as 'Ventas'
	,
		
	(
	
	select 
	round((SUM( IF( d.unidadmedida = pps.unidaddemedida, d.cantidadingresada, d.cantidadingresada / pps.conversiones ) ) 
	- 
	COALESCE((
	
	SELECT 
	SUM( IF( d.unidadmedida = p.unidaddemedida, d.cantidad, d.cantidad / p.conversiones ) ) AS 'Total_Engreso' 
	FROM  producto p, detalleegresoproducto d, egresoproducto e, almacen a
	WHERE 
	p.idproducto = d.idproducto AND d.idegresoprod = e.idegresoprod 
	AND a.idalmacen = e.idalmacen AND a.idalmacen =$almacen  
	and e.fecha<='$hasta' and p.idproducto=pps.idproducto and e.estado=1 
	GROUP BY p.idproducto 
	
	),0) -
	COALESCE((
	SELECT 
	SUM( IF( d.unidadmedida = p.unidaddemedida, d.cantidad, d.cantidad / p.conversiones ) ) AS 'Total_Trapaso' 
	FROM  producto p, detalletraspaso d, traspaso t, almacen a 
	WHERE 
	 p.idproducto = d.idproducto AND d.idtraspaso = t.idtraspaso 
	AND a.idalmacen = t.idalmacenorigen AND a.idalmacen =$almacen 
	and t.fecha<='$hasta' and p.idproducto=pps.idproducto and t.estado=1 
	GROUP BY p.idproducto 
	
	),0)-
	
	COALESCE((
	SELECT 
	SUM( IF( d.unidadmedida = p.unidaddemedida, d.cantidad, d.cantidad / p.conversiones ) ) AS 'Total_VentaR' 
	FROM producto p, detallerequerimiento d,detalleingresoproducto di,detalleatencion da,atencion at  
	, almacen a,ingresoproducto i 
	WHERE 
	 da.idatencion = at.idatencion and d.iddetalleatencion = da.iddetalleatencion 
	and d.iddetalleingreso = di.iddetalleingreso and di.idingresoprod = i.idingresoprod 
	and p.idproducto = di.idproducto AND a.idalmacen = i.idalmacen 
	AND a.idalmacen =$almacen and date(at.fecha)<='$hasta' 
	and da.estado=1 and p.idproducto=pps.idproducto 
	GROUP BY p.idproducto 
	),0)-
	
	COALESCE((
	
	SELECT 
	SUM( IF( d.unidadmedida = p.unidaddemedida, d.cantidad, d.cantidad / p.conversiones ) ) AS 'Total_Venta' 
	FROM producto p, detallenotaventa d,detalleingresoproducto di,notaventa n
	, almacen a,ingresoproducto i 
	WHERE 
	p.idproducto = d.idproducto AND d.idnotaventa = n.idnotaventa 
	and d.iddetalleingreso = di.iddetalleingreso and di.idingresoprod = i.idingresoprod 
	AND a.idalmacen = i.idalmacen AND a.idalmacen =$almacen 
	and n.fecha<='$hasta' and p.idproducto=pps.idproducto and n.estado=1 
	GROUP BY p.idproducto 
	
	),0)),4)as 'InvSistema' 
	FROM  producto pps, detalleingresoproducto d, ingresoproducto i, almacen a 
	WHERE pps.idproducto = d.idproducto AND d.idingresoprod = i.idingresoprod 
	AND a.idalmacen = i.idalmacen AND a.idalmacen =$almacen 
	AND d.estado =1 and pps.idproducto=pp.idproducto 
	and i.fecha<='$hasta'  and i.estado=1 
	GROUP BY pps.idproducto
	
	)as 'InvSistemas',
	
	(
	SELECT 
	round((SUM(COALESCE(d.cantidadum,0) +  COALESCE(d.cantidadua/ pi.conversiones,0) ) ),4) as 'Ingresos'  
	FROM  producto pi, detalleinventario d, inventario i, almacen a 
	WHERE pi.idproducto = d.idproducto 
	AND d.idinventario= i.idinventario AND a.idalmacen = i.idalmacen 
	AND a.idalmacen =$almacen and i.fechafinal='$hasta' and i.estado=1 
	and d.idproducto=pp.idproducto GROUP BY pi.idproducto 
	)as 'InvFisico' ".$condicion; 
	return $sql;
 }
 
    $header = "            
        <table width='100%'>
          <tr>
            <td width='4%' rowspan='3'></td>
            <td width='56%' style='text-align:center;'>&nbsp;</td>
            <td width='25%'>&nbsp;</td>
            <td width='13%'>&nbsp;</td>
            <td width='2%' >&nbsp;</td>
          </tr>
          <tr>
            <td style='text-align:center; font-size:11px;'>        </td>
            <td>&nbsp;</td>
            <td style='text-align:center;background: #E6E6E6;border:1px solid;font-size:10px;'>
			<strong>Nº $_GET[idinventario]</strong> - Pag. {PAGENO}/{nb}</td>
            <td>&nbsp;</td>
          </tr>
          
        </table>";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="reporte.css" type="text/css" />
<title>Reporte de Inventario</title>
</head>

<body>

<?php
    if ($_GET['grupo'] == "todos") {
    $condicion = ", pp.unidaddemedida, pp.conversiones 
	FROM  producto pp, detalleingresoproducto d, ingresoproducto i, almacen a 
	WHERE  pp.idproducto = d.idproducto AND d.idingresoprod = i.idingresoprod 
	AND a.idalmacen = i.idalmacen AND a.idalmacen =$almacen[idalmacen] 
	AND d.estado =1  
	and i.estado=1 
	GROUP BY pp.idproducto order by pp.nombre;";
	} else {	
	$condicion = ", pp.unidaddemedida, pp.conversiones 
	FROM  producto pp, detalleingresoproducto d, ingresoproducto i, almacen a,grupo g, subgrupo sg  
	WHERE  pp.idproducto = d.idproducto AND d.idingresoprod = i.idingresoprod 
	AND a.idalmacen = i.idalmacen AND a.idalmacen =$almacen[idalmacen] 
	AND d.estado =1  
	and i.estado=1 
	and pp.idsubgrupo=sg.idsubgrupo 
	and sg.idgrupo=g.idgrupo 
	and g.idgrupo=$_GET[grupo] 
	GROUP BY pp.idproducto order by pp.nombre;";
	}
	$totalIngreso = 0;
	$sql = getConsulta($fechaInicial, $fechaFinal, $almacen['idalmacen'], $condicion);  
	$cuentas = $db->consulta($sql);
	$num = 0;
	$totalItem = $db->getnumRow($sql);
	while ($num < $totalItem ) {
?>

<div style=" position : absolute;left:5%; top:20px;"></div>
<div class="borde"></div>

<div class="session1_logotipo"><?php if ($logo == 'true'){ echo  "<img src='../$datoGeneral[imagen]' width='200' height='70'/>";} ?></div>
<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1"><?php echo "CONTROL DE INVENTARIO";?></td></tr>
      <tr><td align="center" class="session1_titulo1_1"><?php echo "Del ".$fechaInicio[2]." de "
	  .$db->mes($fechaInicio[1])." de ".$fechaInicio[0]	 ." al ".$fechaFin[2]
	  ." de ".$db->mes($fechaFin[1])." de ".$fechaFin[0]; ?></td></tr> 
    </table>
</div>
<div class="session3_datos">
<?php
    setAlmacen($almacen['nombre']); 
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php
    setCabecera($datoInventario['desde'], $datoInventario['hasta']);
    $cantidad = 0;
    while ($cuenta = mysql_fetch_array($cuentas)) {	    
	    $cantidad++;
		$num++;
		if ($num == $totalItem || $cantidad == 58) {
		    setData($num, $cuenta, "cierre");	
		} else {
			setData($num, $cuenta, "dato");	
		}
		
	    if ($cantidad == 58) {
	        break;
		}	
    }
?>  

</table>

<?php
    if ($num == $totalItem) {
?>   
<table width="90%" border="0" align="center">
  <tr>
    <td width="133" class="negrita" align="left"></td>
    <td width="749"></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo "<strong>Observaciones:</strong>&nbsp;". $observacion['glosa'];?></td>
  </tr>
  <tr><td colspan="2" height="50">&nbsp;</td></tr>
</table>
 
<table width="90%" border="0" align="center">
  <tr>
    <td width="73">&nbsp;</td>
    <td width="158" class="negrita">........................................</td>
    <td width="47">&nbsp;</td>
    <td width="154" class="negrita">.........................................</td>
    <td width="54">&nbsp;</td>
    <td width="156" class="negrita">.......................................</td>
    <td width="57">&nbsp;</td>
    <td width="155">.....................................</td>
    <td width="85">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold">Encargado-Almacén</td>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold">Aux. Contable</td>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold">Vº.Bº.</td>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold">Supervisor</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold"><?php echo $observacion['administrador'];?></td>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold"><?php echo $observacion['usuario'];?></td>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold"><?php echo $observacion['supervisor'];?></td>
    <td>&nbsp;</td>
  </tr>
</table> 	   
	   
<?php 
    }
?>

</div>
 
<div class="session4_pie"> 
  <table width="93%" border="0" align="center">
  <tr>
    <td width="120" align="right"></td>
    <td width="324"></td>
    <td width="93">&nbsp;</td>
    <td width="189">&nbsp;</td>
    <td width="201">&nbsp;</td>
    <td width="170" >Impreso: <?php echo date("d/m/Y");?></td>
    <td width="130">Hora: <?php echo date("H:i:s");?></td>
  </tr>
  </table>
</div> 

<?php
    if ($num < $totalItem) {
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