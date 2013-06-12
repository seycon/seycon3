<?php
	ob_start();
	session_start();
	include('../MPDF53/mpdf.php');
	include('../conexion.php');
	$db = new MySQL();

    $almacen = $_GET['almacen'];
	$desde = $db->GetFormatofecha($_GET['desde'],"/");
	$hasta = $db->GetFormatofecha($_GET['hasta'],"/");
/*	$almacen = 9 ;
	$desde = "2012/09/01";
	$hasta = "2012/12/24";*/
	
	$fechaI = explode('/',$desde);
	$fechaF = explode('/',$hasta);
	

	$sql = "select imagen,left(nombrecomercial,13)as 'nombrecomercial',nit from empresa;";
	$datoGeneral = $db->arrayConsulta($sql);
    $sql = "select nombre from almacen where idalmacen=$almacen;";
	$datoAlmacen = $db->arrayConsulta($sql);
	
	function setcabecera()
	{
		echo "<tr >
		  <td width='9%' rowspan='2' class='session1_cabecera1'>Nº Nota</td>
		  <td width='11%' rowspan='2' class='session1_cabecera1'>Fecha</td>
		  <td width='34%' rowspan='2' class='session1_cabecera1'>Producto</td>
		  <td colspan='2' class='session1_cabecera1'>Ingresos</td>
		  <td width='21%' rowspan='2' class='session1_cabecera2'>Precio Total</td>
		</tr>
		<tr>
		  <td width='13%' class='session1_cabecera3'>U.P.</td>
		  <td width='12%' class='session1_cabecera3'>Cantidad</td>
		</tr>";
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
	
	function nextPage()
	{
	   for ($m = 1; $m < 55; $m++) {
		   echo "<br />";
	   } 
	}	


    function setTotal($total)
	{
	    echo "
		<tr class='cebra'>
	     <td align='right' colspan='5'  class='session3_datosF1_1' style='border-top:1.5px solid;font-weight:bold;'>Sub Total:</td>
	     <td  class='session3_datosF1_3' align='center' style='border-top:1.5px solid'>".number_format($total,2)."</td>
	    </tr>";	
		
	}
	
	function setTotalF($total)
	{
	    echo "
		<tr class='cebra'>
	     <td align='right' colspan='5'  class='session3_datosF1_1' style='border-bottom:1.5px solid;font-weight:bold;'>Sub Total:</td>
	     <td  class='session3_datosF1_3' align='center' style='border-bottom:1.5px solid'>".number_format($total,2)."</td>
	    </tr>";	
		
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
	
	function setDato($tipo, $nota, $transaccion, $fecha , $producto, $um, $cantidad, $precio)
	{
	 $clase1 = "";	
	 if ($tipo == "cierre") {
	     $clase1 = "border-top:1.5px solid";
	 }	
	  if ($tipo == "final") {
		 if (trim($nota) == "") {
			 $clase1 = "border-bottom:1.5px solid";
		 } else {
	         $clase1 = "border-top:1.5px solid;border-bottom:1.5px solid";
		 }
	 }	
	 $stock = getCantidades($cantidad, $conversion);		
	 $total = $cantidad * $precio;	
	 $codigoT = "";
	 if (trim($nota) != ""){
	     $codigoT = "$transaccion$nota";
	 }
	 
	 echo "<tr >
	  <td  class='session3_datosF1_1' style='$clase1' align='center'>&nbsp;$codigoT</td>
	  <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$fecha</td>
	  <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$producto</td>
	  <td  class='session3_datosF1_2' style='$clase1' align='center'>&nbsp;$um</td>
	  <td  class='session3_datosF1_2' style='$clase1' >".number_format($cantidad,2)."</td>
	  <td  class='session3_datosF1_3' style='$clase1' >".number_format($total,2)."</td>
	 </tr>";	
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet"  type="text/css" href="style_ingresodetallado.css"/>
<!--La linea anterior incluye el archivo .css-->
<title>Reporte de Almacén</title>
</head>

<body>
<?php
	$consulta = "SELECT 
 	i.idingresoprod as 'idtransaccion',i.fecha,pi.nombre,d.unidadmedida
    , d.cantidadingresada as 'cantidad',d.precio,if (i.tipoingreso = 'traspaso', 'TI-', 'I-') as 'transaccion'  
	FROM  producto pi, detalleingresoproducto d, ingresoproducto i, almacen a 
	WHERE pi.idproducto = d.idproducto AND d.idingresoprod = i.idingresoprod 
	AND a.idalmacen = i.idalmacen AND a.idalmacen =$almacen 
	AND d.estado =1 and i.fecha>='$desde' 
	and i.fecha<='$hasta' and i.estado=1 order by i.fecha desc;";
	
	$tope = 35;
	$cant = $db->getnumrow($consulta);
	$numero = 0;
	$row = $db->consulta($consulta);
	$subtotal = 0;
	while($numero < $cant) {
?>


<div style=" position : absolute;left:5%; top:20px;"></div>
<div class='margenprincipal'></div>
<div class='session1_logotipo'><?php echo  "<img src='../$datoGeneral[imagen]' width='200' height='70'/>"; ?></div>

<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1"><?php echo "INGRESO DE PRODUCTO DETALLADO";?></td></tr> 
     <tr><td align="center" class="session1_titulo2">
	 <?php echo "Del $fechaI[2] de ".$db->mes($fechaI[1])." del $fechaI[0] al $fechaF[2] de ".$db->mes($fechaF[1])." del $fechaF[0]" ;?></td></tr>
  </table>
</div>


<div class="session3_datos">

<table width="100%" border="0">
  <tr><td width="10%" align="right" class="session1_subtitulo1">Almacén:</td>
    <td width="90%" class="session1_subtitulo1"><?php echo $datoAlmacen['nombre'];?></td>
  </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<?php
  setcabecera();
  $nota = "";
  $i = 0;
  while ($data = mysql_fetch_array($row)) {
	  $numero++;
	  $i++;	  	  
	  $fecha = $db->GetFormatofecha($data['fecha'],"-");
	  
	  if (($nota == $data['idtransaccion'] || $i == 1) && $numero < $cant && $i < 46) { 	       	      
	      $nota = $data['idtransaccion'];
		  $subtotal = $subtotal + $data['cantidad'] * $data['precio'];
		  if ($i == 1) {
			  setDato("normal", $nota, $data['transaccion'], $fecha , $data['nombre'], $data['unidadmedida']
			  , $data['cantidad'], $data['precio']);                 	  
		  } else {
			  setDato("normal", "", $data['transaccion'], $fecha , $data['nombre'], $data['unidadmedida']
			  , $data['cantidad'], $data['precio']);    	  
		  }
	  } else {
		  $i++;			  
		  if ($numero < $cant && $i < 46) {
			  setTotal($subtotal);
			  setDato("cierre", $data['idtransaccion'], $data['transaccion'], $fecha , $data['nombre'], $data['unidadmedida']
			  , $data['cantidad'], $data['precio']);  
			  	
			  $subtotal = 0; 
			  $subtotal = $subtotal + $data['cantidad'] * $data['precio']; 
			  
			  
		  } else {
			  $nota = ($data['idtransacion'] != $nota) ? $data['idtransaccion'] : "";
			  if ($nota != "") {
			      setTotal($subtotal);
			  }
			  setDato("final", $nota, $data['transaccion'], $fecha , $data['nombre'], $data['unidadmedida']
			  , $data['cantidad'], $data['precio']); 
			  
			  $subtotal = 0;
			  $subtotal = $subtotal + $data['cantidad'] * $data['precio'];
			  
			  if ($numero == $cant){
			      setTotalF($subtotal);  
			  }
			  
			  	  
		  }
		  $nota =  $data['idtransaccion'];
	  }
	  if ($i > 45) 
	      break;
	  	  
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