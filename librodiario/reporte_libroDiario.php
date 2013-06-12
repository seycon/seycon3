<?php
	ob_start();
	session_start();
	date_default_timezone_set("America/La_Paz");
	include('../MPDF53/mpdf.php');
	include('../conexion.php');
	$db = new MySQL();

	$tituloGeneral = "LIBRO DE DIARIO";
	
 	$desde = $db->GetFormatofecha($_GET['desde'], "/");
	$hasta = $db->GetFormatofecha($_GET['hasta'], "/");
	
	$fechaInicio = explode("/", $_GET['desde']);
	$fechaFinal = explode("/", $_GET['hasta']);	
	
    $sql = "select * from empresa";
	$datoGeneral = $db->arrayConsulta($sql);
	
	
	function setcabecera()
	{
	   echo "<tr>
		  <td width='14%' class='session1_cabecera1'>Código</td>
		  <td width='29%' class='session1_cabecera1'>Cuenta</td>
		  <td width='29%' class='session1_cabecera1'>Descripción</td>
		  <td width='14%' class='session1_cabecera1'>Debe</td>
		  <td width='14%' class='session1_cabecera2'>Haber</td>
		</tr>";
	}
		
	
	function setDato($num, $tipo, $codigo, $cuenta, $descripcion, $debe, $haber)
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
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$cuenta</td>
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

	function setSubTotal($debe, $haber)
	{
	   echo "<tr>
		  <td class='session1_cabecera1_1' ></td>
		  <td class='session1_cabecera2_1'></td>
		  <td class='session1_cabecera2_1' align='right'>Subtotal Comprobante:</td>
		  <td class='session1_cabecera2_1'>".number_format($debe, 2)."</td>
		  <td class='session1_cabecera2_2'>".number_format($haber, 2)."</td>
		</tr>";
	}    


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet"  type="text/css" href="style_libro.css"/>
<title>Reporte Sumas y Saldos</title>
</head>

<body>
<?php
	$consulta = "
	SELECT  l.idlibrodiario,pc.codigo, left(pc.cuenta,35)as 'cuenta',left(dl.descripcion, 30)as 'descripcion'
        ,dl.debe,dl.haber 
	    FROM detallelibrodiario dl, librodiario l,plandecuenta pc 
	    where l.idlibrodiario = dl.idlibro 
        and pc.codigo=dl.idcuenta 
	    AND l.fecha <=  '$hasta' 
        AND l.fecha >=  '$desde' 
	    AND l.estado=1 
        and pc.estado=1  
	    order by l.idlibrodiario; ";
	
	$tope = 49;
	$cant = $db->getnumrow($consulta);
	$numero = 0;
	$row = $db->consulta($consulta);
	$totalDebe = 0;
	$totalHaber  = 0;
	$idlibro = -1;
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
	  
	  if ($i == 1) {
		 $idlibro = $data['idlibrodiario']; 
	  }
	  
	  if ($data['idlibrodiario'] != $idlibro) {
		  setSubTotal($totalDebe, $totalHaber);
		  $totalDebe = 0;
		  $totalHaber = 0;
		  $idlibro = $data['idlibrodiario']; 
		  $i++;
	  }
	  
	  $tipoFila = "normal";
	  if ($numero == $cant || $i == $tope) {
		$tipoFila = "normal";  
	  }
	  setDato($i, $tipoFila, $data['codigo'], $data['cuenta'], $data['descripcion'], $data['debe'], $data['haber']);
  	  $totalDebe = $totalDebe + $data['debe'];
	  $totalHaber = $totalHaber + $data['haber'];
	  
	  
	  if ($i == $tope) 
	      break;
	  	  
  }
   setSubTotal($totalDebe, $totalHaber);  
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