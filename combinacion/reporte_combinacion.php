<?php
	ob_start();
	session_start();
	include('../MPDF53/mpdf.php');
	include('../conexion.php');
	$db = new MySQL();

    $tipoCombinacion = $_GET['tipocombinacion'];	
   // $tipoCombinacion = 1;
	

	$sql = "select imagen,left(nombrecomercial,13)as 'nombrecomercial',nit from empresa;";
	$datoGeneral = $db->arrayConsulta($sql);
    $sql = "select nombre from tipocombinacion where idtipocombinacion=$tipoCombinacion;";
	$datoCombinacion = $db->arrayConsulta($sql);
	
	function setcabecera()
	{
		echo "<tr >
		  <td width='24%' class='session1_cabecera1'>Combinación</td>
		  <td width='28%' class='session1_cabecera1'>Producto</td>
		  <td width='12%' class='session1_cabecera1'>Unidad</td>
		  <td width='12%' class='session1_cabecera1'>Cantidad</td>
		  <td width='12%' class='session1_cabecera1'>P/U</td>
		  <td width='12%' class='session1_cabecera2'>P/Total</td>
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
	     <td align='right' colspan='5'  class='session3_datosF1_1' style='border-top:1.5px solid;font-weight:bold;'>P/Ventas:</td>
	     <td  class='session3_datosF1_3' align='center' style='border-top:1.5px solid'>".number_format($total,2)."</td>
	    </tr>";	
		
	}
	
	function setTotalF($total)
	{
	    echo "
		<tr class='cebra'>
	     <td align='right' colspan='5'  class='session3_datosF1_1' style='border-bottom:1.5px solid;font-weight:bold;'>P/Ventas:</td>
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
	
	function setDato($tipo, $combinacion, $producto, $unidad , $cantidad, $precio)
	{
	 $clase1 = "";	
	 if ($tipo == "cierre") {
	     $clase1 = "border-top:1.5px solid";
	 }	
	  if ($tipo == "final") {
		 if (trim($combinacion) == "") {
			 $clase1 = "border-bottom:1.5px solid";
		 } else {
	         $clase1 = "border-top:1.5px solid;border-bottom:1.5px solid";
		 }
	 }	
	 $total = $cantidad * $precio;
	 
	 echo "<tr >
	  <td  class='session3_datosF1_1' style='$clase1' align='left'>&nbsp;$combinacion</td>
	  <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$producto</td>
	  <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$unidad</td>
	  <td  class='session3_datosF1_2' style='$clase1' align='center'>".number_format($cantidad, 2)."</td>
	  <td  class='session3_datosF1_2' style='$clase1' >".number_format($precio, 2)."</td>
	  <td  class='session3_datosF1_3' style='$clase1' >".number_format($total, 2)."</td>
	 </tr>";	
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet"  type="text/css" href="style_combinacion.css"/>
<!--La linea anterior incluye el archivo .css-->
<title>Reporte de Combinacion</title>
</head>

<body>
<?php
	$consulta = "select left(c.nombre,25) as 'combinacion',c.total,left(p.nombre,25)as 'nombre'
	,dc.cantidad,dc.precio,left(dc.unidadmedida,25)as 'unidadmedida' 
	from detallecombinacion dc,combinacion c,producto p 
	where c.idcombinacion=dc.idcombinacion 
	and p.idproducto=dc.idproducto and c.estado=1
	and c.idtipocombinacion=$tipoCombinacion order by c.nombre asc;";
	
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
     <tr><td align="center" class="session1_titulo1"><?php echo "LISTA DE COMBINACION";?></td></tr>      
  </table>
</div>


<div class="session3_datos">

<table width="100%" border="0">
  <tr><td width="6%" align="right" class="session1_subtitulo1">Tipo:</td>
    <td width="94%" class="session1_subtitulo1"><?php echo $datoCombinacion['nombre'];?></td>
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
	  
	  if (($nota == $data['combinacion'] || $i == 1) && $numero < $cant && $i < 46) { 	       	      
	      $nota = $data['combinacion'];
		  $subtotal = $data['total'];
		  if ($i == 1) {
			  setDato("normal", $data['combinacion'], $data['nombre'], $data['unidadmedida'] , $data['cantidad'], $data['precio']);  		          } else {
			  setDato("normal", "", $data['nombre'], $data['unidadmedida'] , $data['cantidad'], $data['precio']);	  
		  }
	  } else {
		  $i++;			  
		  if ($numero < $cant && $i < 46) {
			  setTotal($subtotal);
			  setDato("cierre", $data['combinacion'], $data['nombre'], $data['unidadmedida'] , $data['cantidad'], $data['precio']);	
			  $subtotal = 0; 
			  $subtotal = $data['total']; 
			  
			  
		  } else {
			  $nota = ($data['combinacion'] != $nota) ? $data['combinacion'] : "";
			  if ($nota != "") {
			      setTotal($subtotal);
			  }
			  setDato("final", $nota, $data['nombre'], $data['unidadmedida'] , $data['cantidad'], $data['precio']);	
			  $subtotal = 0;
			  $subtotal = $data['total'];
			  
			  if ($numero == $cant){
			      setTotalF($subtotal);  
			  }
			  
			  	  
		  }
		  $nota =  $data['combinacion'];
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