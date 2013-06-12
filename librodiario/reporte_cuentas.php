<?php
	ob_start();
	session_start();
	date_default_timezone_set("America/La_Paz");
	include('../MPDF53/mpdf.php');
	include('../conexion.php');
	$db = new MySQL();
	
	if (!isset($_GET['codigo']) || !isset($_GET['desde']) || !isset($_GET['hasta'])) {		
		header("Location: ../index.php");
	}	
	
	$tituloGeneral = "EXTRACTO DE CUENTA";		
	$codigo = $_GET['codigo'];
	
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
			<td width='13%' class='session1_cabecera1'>Nº Libro</td>
			<td width='13%' class='session1_cabecera1'>Fecha</td>
			<td width='22%' class='session1_cabecera1'>Sucursal</td>
			<td width='28%' class='session1_cabecera1'>Transacción</td>
			<td width='12%' class='session1_cabecera1'>Debe</td>
			<td width='12%' class='session1_cabecera2'>Haber</td>
		</tr>";
	}
		
	
	function setDato($num, $tipo, $numero, $fecha, $sucursal, $descripcion, $debe, $haber)
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
	   <td  class='session3_datosF1_1' style='$clase1' align='center'>&nbsp;$numero</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='center'>&nbsp;$fecha</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$sucursal</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$descripcion</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;".number_format($debe, 2)."</td>
	   <td  class='session3_datosF1_3' style='$clase1' align='left'>&nbsp;".number_format($haber, 2)."</td>	  
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
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet"  type="text/css" href="style_cuentas.css"/>
<title>Reporte Extracto Cuentas</title>
</head>

<body>
<?php
   $sql = "select left(cuenta,25)as 'cuenta' 
   from plandecuenta where codigo='$codigo'";
   $datoCuenta = $db->arrayConsulta($sql);

	$consulta = "
	 select l.numero,l.fecha,left(s.nombrecomercial,25)as 'sucursal'
	, left(l.transaccion,30)as 'descripcion',dl.debe,dl.haber 
	  from detallelibrodiario dl,librodiario l,
	  sucursal s
	 where dl.idlibro=l.idlibrodiario and l.estado=1 and 
	 s.idsucursal=l.idsucursal and 
	 idcuenta='$codigo' and l.fecha>='$desde' and l.fecha<='$hasta' order by l.idlibrodiario;";
	
	$tope = 49;
	$cant = $db->getnumrow($consulta);
	$numero = 0;
	$row = $db->consulta($consulta);
	$totalGeneral = array (0, 0, 0, 0);
	while ($numero < $cant) {
?>


<div style=" position : absolute;left:5%; top:20px;"></div>
<div class='margenprincipal'></div>


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
	  $tipoFila = "normal";
	  $fecha  = $db->GetFormatofecha($data['fecha'], "-");
	  if ($numero == $cant || $i == $tope) {
		$tipoFila = "final";  
	  }
	  setDato($i, $tipoFila, $data['numero'], $fecha, $data['sucursal'], $data['descripcion'], $data['debe'], $data['haber']);
	  
	  if ($i == $tope) 
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
	<table align='right' width='12%' >  
	  <tr><td heigth='10'></td></tr>
	  <tr><td align='center' style='border:1px solid;font-size:11px;' bgcolor='#E6E6E6' >{PAGENO}/{nb}</td></tr>
	</table>";
	$mpdf = new mPDF('utf-8','Letter'); 
	$content = ob_get_clean();
	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit; 
?>