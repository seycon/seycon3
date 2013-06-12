<?php
	ob_start();
	session_start();
	include('../../MPDF53/mpdf.php');
	include('../../conexion.php');
	$db = new MySQL();
    
	
    $idplanilla = $_GET['planilla'];

	$sql = "select imagen,left(nombrecomercial,13)as 'nombrecomercial',nit from empresa;";
	$datoGeneral = $db->arrayConsulta($sql);
	$sql = "select * from planillaapoyo where idplanilla=$idplanilla;";
	$datoPlanilla = $db->arrayConsulta($sql);
	
	$datosFecha = explode("-", $datoPlanilla['fecha']);
	
    $sql = "select left(concat(nombre,' ',apellido),40) as 'nombre',left(cargo,20)as 'cargo',honorario from personalapoyo 
	where estado=1 and idpersonalapoyo=$datoPlanilla[idtrabajador];";
	$datoTrabajador = $db->arrayConsulta($sql);

	
	
	function setcabecera()
	{
	  echo "<tr >
      <td colspan='2' >&nbsp;</td>
      <td colspan='4' class='session1_cabecera5'>INGRESOS</td>
      <td  class='session1_cabecera7'>DESCUENTOS</td>
	  </tr>
	  <tr >
	  <td width='25%' class='session1_cabecera1'>Sucursal</td>
	  <td width='10%' class='session1_cabecera1'>Fecha</td>
	  <td width='9%' class='session1_cabecera1'>Venta</td>
	  <td width='11%' class='session1_cabecera1'>Comisión</td>
	  <td width='11%' class='session1_cabecera1'>Botella</td>
	  <td width='11%' class='session1_cabecera1'>Haber</td>
	  <td width='11%' class='session1_cabecera2'>Faltante</td>
	  </tr> ";
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


	function setTotalF($total)
	{
		$clase1 = "border-top:1.5px solid";
	    echo "
		<tr >
		  <td align='left' style='$clase1'>&nbsp;</td>
		  <td align='left' style='$clase1'>&nbsp;</td>
		  <td align='left' style='$clase1'>&nbsp;</td>
		  <td  class='session3_datosF1_Total' style='$clase1' align='center'>".number_format($total[0] ,2)."</td>
		  <td  class='session3_datosF1_Total' style='$clase1' align='center'>".number_format($total[1], 2)."</td>
		  <td  class='session3_datosF1_Total' style='$clase1' align='center'>".number_format($total[2], 2)."</td>
		  <td  class='session3_datosF1_Total' style='$clase1' align='center'>".number_format($total[3], 2)."</td>
		  <td  class='session3_datosF2_Total' style='$clase1' align='center'>".number_format($total[4], 2)."</td>
		</tr>";	
		
	}

	function setDato($tipo, $nro, $sucursal, $fecha, $venta, $comision, $botella, $haber, $faltante)
	{
	 $clase1 = "";	
	 if ($tipo == "cierre") {
	     $clase1 = "border-top:1.5px solid";
	 }	
	  if ($tipo == "final") {
			 $clase1 = "border-bottom:1.5px solid";	
	 }		 
 
	 
	 echo "<tr >
	  <td  class='session3_datosF1_1' style='$clase1' align='left'>&nbsp;$sucursal</td>
	  <td  class='session3_datosF1_2' style='$clase1' align='center'>&nbsp;$fecha</td>
	  <td  class='session3_datosF1_2' style='$clase1' align='center'>".number_format($venta ,2)."</td>
	  <td  class='session3_datosF1_2' style='$clase1' align='center'>".number_format($comision ,2)."</td>
	  <td  class='session3_datosF1_2' style='$clase1' align='center'>".number_format($botella, 2)."</td>
	  <td  class='session3_datosF1_2' style='$clase1' align='center'>".number_format($haber, 2)."</td>
	  <td  class='session3_datosF1_3' style='$clase1' >".number_format($faltante, 2)."</td>	 
	 </tr>";	
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet"  type="text/css" href="style_apoyo.css"/>
<!--La linea anterior incluye el archivo .css-->
<title>Reporte de Planillas de Apoyo</title>
</head>

<body>
<?php
	$consulta = "select left(s.nombrecomercial,25)as 'sucursal',d.fecha,d.venta,d.comision
	,d.botella,d.haber,d.faltante from detalleplanilla d,sucursal s 
	where s.idsucursal=d.idsucursal and d.idplanilla=$idplanilla";	
	$cant = $db->getnumrow($consulta);
	$numero = 0;
	$row = $db->consulta($consulta);
	$subtotal = 0;
	$totalGeneral = array(0, 0, 0, 0, 0);
	while($numero < $cant) {
?>


<div style=" position : absolute;left:5%; top:20px;"></div>
<div class='margenprincipal'></div>
<div class='session1_logotipo'><?php echo  "<img src='../../$datoGeneral[imagen]' width='200' height='70'/>"; ?></div>

<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1"><?php echo "PLANILLA DE APOYO";?></td></tr>  
     <tr><td align="center" class="session1_titulo2"><?php echo "Al $datosFecha[2] de ".$db->mes($datosFecha[1])." del "
	 ." $datosFecha[0]"  ;?></td></tr>     
  </table>
</div>


<div class="session3_datos">

<table width="100%" border="0">
  <tr>
    <td width="6%" height="30" class="session1_subtitulo1" align="right" >Nombre:</td>
    <td width="23%" class="session1_subtitulo2"><?php echo $datoTrabajador['nombre'];?></td>
    <td width="34%" class="session1_subtitulo1" align="right">Cargo:</td>
    <td width="37%" class="session1_subtitulo2"><?php echo ucfirst($datoTrabajador['cargo']);?></td>
  </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
       
<?php
 setcabecera();
 $nota = "";
  $i = 0;
  $item = 0;
  while ($data = mysql_fetch_array($row)) {
	  $numero++;
	  $i++;	  	  
	  $fecha = $db->GetFormatofecha($data['fecha'], "-");
	  if ($numero < $cant && $i < 46) { 	       	      
	     setDato("normal", $i, $data['sucursal'], $fecha, $data['venta'], $data['comision']
		 , $data['botella'], $haber['haber'], $data['faltante']);
	  } else {
		 setDato("final", $i, $data['sucursal'], $fecha, $data['venta'], $data['comision']
		 , $data['botella'], $haber['haber'], $data['faltante']);
	  }
	  if ($i > 45) 
	      break;
	  	  
  }
?>
</table>
<br />
<br />
<table width="93%" border="0" align="center">
  <tr>
    <td width="100">&nbsp;</td>
    <td width="231" class="session4_bordeFirma"></td>
    <td width="97">&nbsp;</td>
    <td width="193" class="session4_bordeFirma"></td>
    <td width="97">&nbsp;</td>
    <td width="191" ></td>
    <td width="319">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">RR-HH</td>
    <td>&nbsp;</td>
    <td align="center">C.I.:........................</td>
    <td>&nbsp;</td>
    <td align="center"></td>
    <td>&nbsp;</td>
  </tr>
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
	$mpdf = new mPDF('utf-8','Letter'); 
	$content = ob_get_clean();
	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit; 
?>