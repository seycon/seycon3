<?php
	ob_start();
	session_start();
	date_default_timezone_set("America/La_Paz");
	include('../MPDF53/mpdf.php');
	include('../conexion.php');
	$db = new MySQL();
	
	if (!isset($_SESSION['softLogeoadmin']) || !isset($_GET['identrega'])) {
		   header("Location: ../index.php");	
	}	

	$tituloGeneral = "SALIDA DE PRODUCTOS DEL ALMACEN";
	$identrega = $_GET['identrega'];
	$logo = $_GET['logo'];
	$sql = "select left(concat(t.nombre,' ',t.apellido),30)as 'nombre',re.fecha 
	 from rutaentrega re,detallerutaentrega de,trabajador t
	 where re.idrutaentrega=de.idrutaentrega 
	 and t.idtrabajador=de.idtrabajador 
	 and re.idrutaentrega=$identrega  
	 limit 1;";
   	$datoGeneralEntrega = $db->arrayConsulta($sql);
	
	$fechaFinal = explode("-", $datoGeneralEntrega['fecha']);
    $sql = "select * from empresa";
	$datoGeneral = $db->arrayConsulta($sql);
	
	
	function setcabecera()
	{
	   echo "<tr >
		  <td width='6%' class='session1_cabecera1'>Nº</td>
		  <td width='10%' class='session1_cabecera1'>Nº Nota</td>
		  <td width='20%' class='session1_cabecera1'>Cliente</td>
		  <td width='19%' class='session1_cabecera1'>Ruta</td>
		  <td width='12%' class='session1_cabecera1'>F/Entrega</td>
		  <td width='28%' class='session1_cabecera1'>Glosa</td>
          <td width='5%' class='session1_cabecera2'>Dev.</td>
       </tr>";
	}
		
	
	function setDato($num, $tipo, $nroNota, $cliente, $ruta, $fecha, $glosa)
	{
	  $clase1 = "";	
	  if ($tipo == "final") {
	      $clase1 = "border-bottom:1.5px solid";		
	  }	
	  if ($tipo == "cerrar") {
	      $clase1 = "border-top:1.5px solid";		
	  }
	  $clase2 = "";
	  if ($num % 2 == 0) {
		  $clase2 = "cebra";
	  }	 
	 
	  echo "<tr class='$clase2'>
	   <td  class='session3_datosF1_1' style='$clase1' align='center'>&nbsp;$num</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='center'>&nbsp;$nroNota</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$cliente</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$ruta</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='center'>$fecha</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$glosa</td>	 
	   <td  class='session3_datosF1_3' style='$clase1' align='left'></td>	 
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


	function setTotalF($total)
	{
		$clase1 = "border-top:1.5px solid";
	    echo "
		<tr >
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
		  <td align='right' class='titulo_1'>Total Saldo:</td>
          <td class='session3_datosF2_Total' align='center'>".number_format($total, 2)."</td>
		  <td >&nbsp;</td>
		</tr>";	
		
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet"  type="text/css" href="style_rutaentrega.css"/>
<title>Reporte de Ruta de Entrega</title>
</head>

<body>
<?php
	$consulta = "
	  select n.idnotaventa,n.fechaentrega,n.glosa,left(r.nombre,15)as 'ruta',
	   left(c.nombre,20)as 'cliente'  
	   from rutaentrega re,detallerutaentrega de,cliente c,notaventa n,ruta r 
	   where re.idrutaentrega=de.idrutaentrega 
	   and n.idnotaventa=de.idnotaventa 
	   and r.idruta=de.idruta 
	   and n.idcliente=c.idcliente 
	   and re.idrutaentrega=$identrega;
	  ";
	
	$tope = 49;
	$cant = $db->getnumrow($consulta);
	$numero = 0;
	$row = $db->consulta($consulta);
	$totalGeneral = 0;
	$saldoSession = 0;
	$codigoTransaccion = "";
	while($numero < $cant) {
?>


<div style=" position : absolute;left:5%; top:20px;"></div>
<div class='margenprincipal'></div>
<div class='session1_logotipo'><?php 
if ($logo == 'true') {
    echo  "<img src='../$datoGeneral[imagen]' width='200' height='70'/>";
}
  ?></div>

<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1"><?php echo $tituloGeneral;?></td></tr>  
     <tr><td align="center" class="session1_titulo2">
	 <?php echo "Del $fechaFinal[2] de ".$db->mes($fechaFinal[1])." del $fechaFinal[0]";?></td></tr>    
     <tr><td align="center" class="session1_titulo2"></td></tr>  
  </table>
</div>


<div class="session3_datos">

<table width="100%" border="0">
  <tr>
    <td width="13%" align="right" class="session1_subtitulo">Trabajador:</td>
    <td width="61%" class="session1_subtitulo1"><?php echo $datoGeneralEntrega['nombre'];?></td>
    <td width="3%" align="right" class="session1_subtitulo1">&nbsp;</td>
    <td width="23%" class="session1_subtitulo1"></td>
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
	  $fecha = $db->GetFormatofecha($data['fechaentrega'], "-");  
	  $transaccion = "NV-".$data['idnotaventa'];
	  $tipoFila = "normal";
	  if ($numero == $cant || $i == $tope) {
		$tipoFila = "final";  
	  }
	  
      setDato($i, $tipoFila, $transaccion, $data['cliente'], $data['ruta'], $fecha, $data['glosa']);
	  if ($i == $tope) 
	      break;
	  	  
  }
?>

</table>

   <?php  
        if ((($i + 8) <= $tope) && ($numero == $cant || $i == 0)) {
   ?>

  <table width="100%" border="0">
    <tr>
      <td width="58%" ></td>
      <td width="42%">&nbsp;</td>
    </tr>
    <tr>
      <td class="session1_subtitulo2">Productos al Salir del Almacén:</td>
      <td>&nbsp;</td>
    </tr>
  </table>

 <div class="cuadroFirmas">   
     <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="10%">&nbsp;</td>
        <td width="19%" class="line_under">&nbsp;</td>
        <td width="40%">&nbsp;</td>
        <td width="20%" class="line_under">&nbsp;</td>
        <td width="11%">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="center" class="session1_subtitulo2">Almacén</td>
        <td>&nbsp;</td>
        <td align="center" class="session1_subtitulo2">Entregador</td>
        <td>&nbsp;</td>
      </tr>
     </table>
 </div>

  <table width="100%" border="0">
    <tr>
      <td width="58%" ></td>
      <td width="42%">&nbsp;</td>
    </tr>
    <tr>
      <td class="session1_subtitulo2">Devolución de productos a Almacén:</td>
      <td>&nbsp;</td>
    </tr>
  </table>

 <div class="cuadroFirmas">   
 <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="10%">&nbsp;</td>
    <td width="18%" class="line_under">&nbsp;</td>
    <td width="8%">&nbsp;</td>
    <td width="10%" align="right" class="session1_subtitulo2">Fecha:</td>
    <td width="17%">________________</td>
    <td width="7%">&nbsp;</td>
    <td width="19%" class="line_under">&nbsp;</td>
    <td width="11%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center" class="session1_subtitulo2">Almacén</td>
    <td>&nbsp;</td>
    <td align="right" class="session1_subtitulo2">Hora:</td>
    <td >________________</td>
    <td>&nbsp;</td>
    <td align="center" class="session1_subtitulo2">Entregador</td>
    <td>&nbsp;</td>
  </tr>
</table>

 </div>

<?php 
$numero++;
} 

if ((($i + 8) > $tope) && $numero == $cant && $i != 0) {
    $numero--;	
}
?>


</div>
<?php
  pie();
       if($numero < $cant) {
	       nextPage();
	   }

	}
?>

<br />

  
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