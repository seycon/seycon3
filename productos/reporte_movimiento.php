<?php
	ob_start();
	session_start();
	include('../MPDF53/mpdf.php');
	include('../conexion.php');
	$db = new MySQL();
	$almacen = $_GET['almacen'];
    $producto = $_GET['producto'];
	$desde = $db->GetFormatofecha($_GET['desde'],"/");
	$hasta = $db->GetFormatofecha($_GET['hasta'],"/");

	$fechaI = explode('/',$desde);
	$fechaF = explode('/',$hasta);
	
	$sql = "select imagen,left(nombrecomercial,13)as 'nombrecomercial',nit from empresa;";
	$datoGeneral = $db->arrayConsulta($sql);
	$sql = "select nombre from producto where idproducto=$producto;";
	$datoProducto = $db->arrayConsulta($sql);
    $sql = "select nombre from almacen where idalmacen=$almacen;";
	$datoAlmacen = $db->arrayConsulta($sql);
	
	function setcabecera()
	{
	  echo "
	  <tr >
		<td width='10%'  rowspan='2' class='session1_cabecera1'>Nota </td>
		<td width='11%' rowspan='2' class='session1_cabecera1'>Fecha</td>
		<td colspan='2' class='session1_cabecera1'>Entradas</td>
		<td colspan='2' class='session1_cabecera1'>Salidas</td>
        <td width='11%' rowspan='2' class='session1_cabecera1'>Costo Unitario</td>
        <td width='33%'  rowspan='2' class='session1_cabecera2'>Glosa</td>
	  </tr>
	  <tr>
		<td width='9%' class='session1_cabecera3'>U.P.</td>
		<td width='9%' class='session1_cabecera3'>U.A.</td>
        <td width='9%' class='session1_cabecera3'>U.P.</td>
		<td width='8%' class='session1_cabecera3'>U.A.</td>
	  </tr>";
	}
	
	function nextPage()
	{
	   for ($m = 1; $m < 55; $m++) {
		   echo "<br />";
	   } 
	}
		
	
	function pie()
	{
	  echo "<div class='session4_pie'> 
	  <table width='93%' border='0' align='center'>
	  <tr>
		<td width='120' align='right'></td>
		<td width='324'></td>
		<td width='93'>&nbsp;</td>
		<td width='189'>&nbsp;</td>
		<td width='201'>&nbsp;</td>
		<td width='170' >Impreso:".date('d/m/Y');echo"</td>
		<td width='130'>Hora:".date('H:i:s');echo"</td>
	  </tr>
	  </table>
	  </div>";
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
	
	
	function getRestante($trasaccion, $cantidad, $acumulado){
	  if ($trasaccion == "I-" || $trasaccion == "TI-") {
		 return $cantidad + $acumulado;  
	  }
	  if ($trasaccion == "E-" || $trasaccion == "TE-" || $trasaccion == "V-" || $trasaccion == "R-") {
		 return $acumulado - $cantidad; 
	  }
	}
	
	
	function getCantidad($trasaccion, $cantidad, $conversion)
	{
	 $totalCantidad = array(0, 0, 0, 0);	
	 $stock = getCantidades($cantidad, $conversion);	
	 if ($trasaccion == "I-" || $trasaccion == "TI-") {
		$totalCantidad[0] = $stock[0];
		$totalCantidad[1] = $stock[1]; 
	 }
	 if ($trasaccion == "E-" || $trasaccion == "TE-" || $trasaccion == "V-" || $trasaccion == "R-") {
		$totalCantidad[2] = $stock[0];
		$totalCantidad[3] = $stock[1]; 
	 }
	 return $totalCantidad;	
	}
	
	function setDato($fecha, $transaccion ,$cantidad, $conversion, $glosa, $tipo, $idtransaccion, $precio, $num)
	{
		
	 $clase = "";
	if ($num % 2 == 0) {
		$clase = "cebra";
	}
			
	 $clase1 = "";	
	  if ($tipo == "final") {
			 $clase1 = "border-bottom:1.5px solid";
		 
	 }	
	 $movimiento = array(1 , 0, 0 ,1);
	 $movimiento = getCantidad($transaccion, $cantidad, $conversion);	
		
	 echo "<tr class=$clase>
	  <td  class='session3_datosF1_1' style='$clase1' align='center'>$transaccion$idtransaccion</td>
	  <td  class='session3_datosF1_2' style='$clase1' align='center'>$fecha</td>
	  <td  class='session3_datosF1_2'   style='$clase1' >".number_format($movimiento[0],2)."</td>
       <td  class='session3_datosF1_2'   style='$clase1' >".number_format($movimiento[1],2)."</td>
	  <td  class='session3_datosF1_2' style='$clase1' >".number_format($movimiento[2],2)."</td>
      <td  class='session3_datosF1_2' style='$clase1' >".number_format($movimiento[3],2)."</td>
	  <td  class='session3_datosF1_2'  style='$clase1' >".number_format($precio,2)."</td>
	  <td  class='session3_datosF1_3' style='$clase1' align='left'>&nbsp;$glosa</td>
	 </tr>";	
	}
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet"  type="text/css" href="style_movimiento.css"/>
<!--La linea anterior incluye el archivo .css-->
<title>Kardex Producto</title>
</head>

<body>
<?php
	$consulta = "(SELECT 
 	i.idingresoprod as 'idtransaccion',i.fecha,left(i.glosa,35) as 'glosa',pi.unidaddemedida,pi.conversiones
      , IF( d.unidadmedida = pi.unidaddemedida, d.cantidadingresada, d.cantidadingresada / pi.conversiones )
      as 'total','I-' as 'transaccion',d.precio   
	FROM  producto pi, detalleingresoproducto d, ingresoproducto i, almacen a 
	WHERE pi.idproducto = d.idproducto AND d.idingresoprod = i.idingresoprod 
	AND a.idalmacen = i.idalmacen AND a.idalmacen =$almacen 
	AND d.estado =1 and i.fecha>='$desde' 
	and i.fecha<='$hasta' and pi.idproducto=$producto and i.estado=1 
) union all (

SELECT 
	e.idegresoprod as 'idtransaccion',e.fecha,left(e.glosa,35)as 'glosa',p.unidaddemedida,p.conversiones, 
        IF( d.unidadmedida = p.unidaddemedida, d.cantidad, d.cantidad / p.conversiones ) AS 'total',
        'E-' as 'transaccion',d.precio  
	FROM  producto p, detalleegresoproducto d, egresoproducto e, almacen a 
	WHERE 
	p.idproducto = d.idproducto AND d.idegresoprod = e.idegresoprod 
	AND a.idalmacen = e.idalmacen AND a.idalmacen =$almacen 
	and e.fecha>='$desde' and e.fecha<='$hasta'
	and p.idproducto=$producto and e.estado=1 
) union all(	

SELECT  t.idtraspaso as 'idtransaccion',t.fecha,left(t.glosa,35)as 'glosa',p.unidaddemedida,p.conversiones,
	IF( d.unidadmedida = p.unidaddemedida, d.cantidad, d.cantidad / p.conversiones ) AS 'total',
        'TE-' as 'transaccion',d.precio   
	FROM  producto p, detalletraspaso d, traspaso t, almacen a 
	WHERE 
	 p.idproducto = d.idproducto AND d.idtraspaso = t.idtraspaso 
	AND a.idalmacen = t.idalmacenorigen AND a.idalmacen =$almacen 
	and t.fecha>='$desde' and t.fecha<='$hasta'  
	and p.idproducto=$producto and t.estado=1 
) union all(

SELECT  t.idtraspaso as 'idtransaccion',t.fecha,left(t.glosa,35)as 'glosa',p.unidaddemedida,p.conversiones,
	IF( d.unidadmedida = p.unidaddemedida, d.cantidad, d.cantidad / p.conversiones ) AS 'total',
        'TI-' as 'transaccion',d.precio   
	FROM  producto p, detalletraspaso d, traspaso t, almacen a 
	WHERE 
	 p.idproducto = d.idproducto AND d.idtraspaso = t.idtraspaso 
	AND a.idalmacen = t.idalmacendestino AND a.idalmacen =$almacen 
	and t.fecha>='$desde' and t.fecha<='$hasta'  
	and p.idproducto=$producto and t.estado=1 
) union all(

SELECT  at.idatencion as 'idtransaccion',date(at.fecha) as 'fecha','venta restaurante' as 'glosa',p.unidaddemedida
        ,p.conversiones,
	 IF( d.unidadmedida = p.unidaddemedida, d.cantidad, d.cantidad / p.conversiones ) AS 'total',
        'R-' as 'transaccion',di.precio   
	FROM producto p, detallerequerimiento d,detalleingresoproducto di,detalleatencion da,atencion at  
	, almacen a,ingresoproducto i 
	WHERE 
	 da.idatencion = at.idatencion  and d.iddetalleatencion = da.iddetalleatencion 
	and d.iddetalleingreso = di.iddetalleingreso and di.idingresoprod = i.idingresoprod 
	and p.idproducto = di.idproducto AND a.idalmacen = i.idalmacen 
	AND a.idalmacen =$almacen and date(at.fecha)>='$desde' and date(at.fecha)<='$hasta'
	and da.estado=1 and p.idproducto=$producto 
	
) union all(

select  n.idnotaventa as 'idtransaccion',n.fecha,left(n.glosa,35)as 'glosa',p.unidaddemedida,p.conversiones,
IF( d.unidadmedida = p.unidaddemedida, d.cantidad, d.cantidad / p.conversiones ) AS 'total',
'V-' as 'transaccion',d.precio  
	FROM producto p, detallenotaventa d,detalleingresoproducto di,notaventa n
	, almacen a,ingresoproducto i 
	WHERE 
	p.idproducto = d.idproducto AND d.idnotaventa = n.idnotaventa 
	and d.iddetalleingreso = di.iddetalleingreso and di.idingresoprod = i.idingresoprod 
	AND a.idalmacen = i.idalmacen AND a.idalmacen =$almacen 
	and n.fecha>='$desde' and n.fecha<='$hasta'    
	and p.idproducto=$producto and n.estado=1 
) order by fecha;";
	
	
	
	$totalSaldo = 0;
	
	$tope = 35;
	$cant = $db->getnumrow($consulta);
	$numero = 0;
	$row = $db->consulta($consulta);
	while($numero < $cant) {
?>


<div style=" position : absolute;left:5%; top:20px;"></div>
<div class='margenprincipal'></div>
<div class='session1_logotipo'><?php echo  "<img src='../$datoGeneral[imagen]' width='200' height='70'/>"; ?></div>

<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1"><?php echo "MOVIMIENTO DE PRODUCTOS";?></td></tr> 
     <tr><td align="center" class="session1_titulo2">
<?php echo "Del $fechaI[2] de ".$db->mes($fechaI[1])." del $fechaI[0] al $fechaF[2] de ".$db->mes($fechaF[1])."del $fechaF[0]" ;?>
     </td></tr>
  </table>
</div>


<div class="session3_datos">

<table width="100%" border="0">
  <tr><td width="10%" align="right" class="session1_subtitulo1">Almacén:</td>
    <td width="90%" class="session1_subtitulo1"><?php echo $datoAlmacen['nombre'];?></td>
  </tr>
  <tr><td align="right" class="session1_subtitulo1">Producto:</td>
    <td class="session1_subtitulo1"><?php echo $datoProducto['nombre'];?></td>
  </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<?php
  setcabecera();
  $subgrupo = "";
  $i = 0;
  while ($data = mysql_fetch_array($row)) {
	 $numero++;
	 $i++;

	 $fecha = $db->GetFormatofecha($data['fecha'],"-");	  	  
	 if($numero < $cant && $i <= 45) {
      setDato($fecha, $data['transaccion'] ,$data['total'], $data['conversiones'], $data['glosa']
      , "inicio", $data['idtransaccion'], $data['precio'], $i);    	  
	 } else {
	  setDato($fecha, $data['transaccion'] ,$data['total'], $data['conversiones'], $data['glosa']
      , "final", $data['idtransaccion'], $data['precio'], $i); 
	 } 
	 
	  if ($i > 45) {
	      break;
	  }	  
  }
?>
</table>
</div>
<?php
  pie();
       if($numero < $cant) {
	       nextPage();
	   }

	}
?>
</body>
</html>

<?php
	$header = "
	<table align='right' width='10%' >  
	  <tr><td align='center' style='border:1px solid;' bgcolor='#E6E6E6' >{PAGENO}/{nb}</td></tr>
	</table>";
	$mpdf=new mPDF('utf-8','Letter'); 
	$content = ob_get_clean();
	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit; 
?>