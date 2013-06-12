<?php
	ob_start();
	session_start();
	date_default_timezone_set("America/La_Paz");
	include('../MPDF53/mpdf.php');
	include('../conexion.php');
	$db = new MySQL();

	$tituloGeneral = "BALANCE DE SUMAS Y SALDOS";
	
 	$desde = $db->GetFormatofecha($_GET['desde'], "/");
	$hasta = $db->GetFormatofecha($_GET['hasta'], "/");
	
	$fechaInicio = explode("/", $_GET['desde']);
	$fechaFinal = explode("/", $_GET['hasta']);	
	
    $sql = "select * from empresa";
	$datoGeneral = $db->arrayConsulta($sql);
	
	
	function setcabecera()
	{
	   echo "
		<tr >
		  <td width='14%' rowspan='2' class='session1_cabecera1'>Código</td>
		  <td width='39%' rowspan='2' class='session1_cabecera1'>Descripción</td>
		  <td colspan='2' class='session1_cabecera1'>Sumas</td>
		  <td colspan='2' class='session1_cabecera2'>Saldos</td>
		  </tr>
		<tr >
		  <td width='12%' class='session1_cabecera1_1'>Debe</td>
		  <td width='12%' class='session1_cabecera1_1'>Haber</td>
		  <td width='12%' class='session1_cabecera1_1'>Deudor</td>
		  <td width='12%' class='session1_cabecera2_1'>Acreedor</td>
		</tr>";
	}
		
	
	function setDato($num, $tipo, $codigo, $descripcion, $debe, $haber, $deudor, $acreedor)
	{
	  $clase1 = "";	
	  if ($tipo == "final") {
	      $clase1 = "border-bottom:1.5px solid";		
	  }	
	  $clase2 = "";
	  if ($num % 2 == 0) {
		  $clase2 = "cebra";
	  }	 
	 
	  echo "<tr class='$clase2'>
	   <td  class='session3_datosF1_1' style='$clase1' align='center'>&nbsp;$codigo</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$descripcion</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;".number_format($debe, 2)."</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;".number_format($haber, 2)."</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;".number_format($deudor, 2)."</td>
	   <td  class='session3_datosF1_3' style='$clase1' align='left'>&nbsp;".number_format($acreedor, 2)."</td>	  
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
	    echo "
		<tr >
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
		  <td class='session3_datosF2_Total_1' align='center'>".number_format($total[0], 2)."</td>
		  <td class='session3_datosF2_Total_1' align='center'>".number_format($total[1], 2)."</td>
          <td class='session3_datosF2_Total_1' align='center'>".number_format($total[2], 2)."</td>
		  <td class='session3_datosF2_Total' align='center'>".number_format($total[3], 2)."</td>
		</tr>";			
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet"  type="text/css" href="style_sumasSaldos.css"/>
<title>Reporte Sumas y Saldos</title>
</head>

<body>
<?php
	$consulta = "SELECT  SUM( dl.debe ) as 'debe' , SUM(dl.haber)as 'haber',pc.codigo, left(pc.cuenta,35)as 'cuenta',
        left(pc.codigo,1)as 'numero' 
	FROM detallelibrodiario dl, librodiario l,plandecuenta pc 
	where l.idlibrodiario = dl.idlibro
        and pc.codigo=dl.idcuenta 
	AND l.fecha <=  '$hasta'
        AND l.fecha >=  '$desde'
	AND l.estado=1 
        and pc.nivel=5 
        and pc.estado=1  
	GROUP BY pc.codigo order by pc.codigo;";
	
	$tope = 49;
	$cant = $db->getnumrow($consulta);
	$numero = 0;
	$row = $db->consulta($consulta);
	$totalGeneral = array (0, 0, 0, 0);
	while ($numero < $cant) {
?>


<div style=" position : absolute;left:5%; top:20px;"></div>
<div class='margenprincipal'></div>
<div class="session1_numTransaccion">
    <table width="100%" border="0">
     <tr><td align="right" class="tituloCebecera"><?php echo $datoGeneral['nombrecomercial']; ?></td></tr> 
     <tr><td align="right" class="tituloCebecera"><?php echo "Nit: ".$datoGeneral['nit']; ?></td></tr>
    </table>
</div>

<div class='session1_logotipo'><?php echo  "<img src='../$datoGeneral[imagen]' width='200' height='70'/>"; ?></div>

<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1"><?php echo $tituloGeneral;?></td></tr>  
     <tr><td align="center" class="session1_titulo2">
	 <?php echo "Del $fechaInicio[0] de ".$db->mes($fechaInicio[1])." del "
	 ." $fechaInicio[2] al $fechaFinal[0] de ".$db->mes($fechaFinal[1])." del $fechaFinal[2]"    ;?></td></tr>    
     <tr><td align="center" class="session1_titulo2"><?php echo "(Expresado en Bolivianos)";?></td></tr>  
  </table>
</div>


<div class="session3_datos">
<table width='100%' border='0' cellspacing='0' cellpadding='0' align='center'>
<?php
 setcabecera();
 $nota = "";
  $i = 0;

  while ($data = mysql_fetch_array($row)) {
	  $numero++;
	  $i++;	
	  $deudor = 0;
	  $acreedor = 0;
	  if ( $data['numero'] == 1 || $data['numero'] == 3 || $data['numero'] == 5 || $data['numero'] == 6 ) {
		$deudor = $data['debe'] - $data['haber'];  
	  }
	  
 	  if ( $data['numero'] == 2 || $data['numero'] == 4 ) {
		$acreedor = $data['haber'] - $data['debe'];    
	  }

	  $totalGeneral[0] = $totalGeneral[0] + $data['debe'];
	  $totalGeneral[1] = $totalGeneral[1] + $data['haber'];
	  $totalGeneral[2] = $totalGeneral[2] + $deudor;
	  $totalGeneral[3] = $totalGeneral[3] + $acreedor;
	  $tipoFila = "normal";
	  if ($numero == $cant || $i == $tope) {
		$tipoFila = "final";  
	  }
	  setDato($i, $tipoFila, $data['codigo'], $data['cuenta'], $data['debe'], $data['haber'], $deudor, $acreedor);
	  
	  if ($i == $tope) 
	      break;
	  	  
  }
  setTotalF($totalGeneral);  
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
	$header = "";
	$mpdf = new mPDF('utf-8','Letter'); 
	$content = ob_get_clean();
	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit; 
?>