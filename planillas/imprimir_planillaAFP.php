<?php
	session_start();
	if (!isset($_SESSION['softLogeoadmin']) || !isset($_GET['meses']) || !isset($_GET['anio'])) {
		header("Location: ../cerrar.php");	
	}
	ob_start();
	include("../MPDF53/mpdf.php");
	include("../conexion.php");
	include('../reportes/literal.php');
	$db = new MySQL();

	$mes = $_GET['meses'];
	$anio = $_GET['anio'];
	$sql = "select imagen,left(nombrecomercial,18)as 'nombrecomercial',nit
	,left(reprepropietario,20)as 'reprepropietario',cipropietario,numafiliado from empresa;";
	$datoGeneral = $db->arrayConsulta($sql);

	function filainicialAporteL ($num, $dato, $total, $descuento)
	{
	  $total[0] = $total[0] + ($descuento['segurovejez'] * $dato['totalganado']) ;
	  $total[1] = $total[1] + ($descuento['riesgocomun'] * $dato['totalganado']);
	  $total[2] = $total[2] + ($descuento['comisionafp'] * $dato['totalganado']);
	  $total[3] = $total[3] + ($descuento['aportesolidario'] * $dato['totalganado']);
	  $totalAfp = $dato['totalganado'] * $descuento['segurovejez'] + $dato['totalganado'] * $descuento['riesgocomun'] +
	   $dato['totalganado'] * $descuento['comisionafp'] + $dato['totalganado'] * $descuento['aportesolidario'];
	  $total[4] = $total[4] + $totalAfp;   
	  $total[5] = $total[5] + $dato['totalganado'];      
			
	  echo "  <tr>
		<td class='session2_datos1'>$num</td>
		<td class='session2_datos1_1' align='left'>$dato[trabajador]</td>
		<td class='session2_datos1_1'>$dato[sexo]</td>
		<td class='session2_datos1_1'>$dato[carnetidentidad]</td>
		<td class='session2_datos1_1'>$dato[diastrabajados]</td>
		<td class='session2_datos1_1'>".number_format($dato['totalganado'], 2)."</td>
		<td class='session2_datos1_1'>".number_format(($descuento['segurovejez'] * $dato['totalganado']),2)."</td>
		<td class='session2_datos1_1'>".number_format(($descuento['riesgocomun'] * $dato['totalganado']),2)."</td>
		<td class='session2_datos1_1'>".number_format(($descuento['comisionafp'] * $dato['totalganado']),2)."</td>
		<td class='session2_datos1_1'>".number_format(($descuento['aportesolidario'] * $dato['totalganado']),2)."</td>
		<td class='session2_datos1_2'>".number_format($totalAfp,2)."</td>
	  </tr>";	
	  return $total;
	}

	function totalAL ($total)
	{
	  echo "  <tr>
		<td class='session3_contornoSuperior'>&nbsp;</td>
		<td class='session3_contornoSuperior'>&nbsp;</td>
		<td class='session3_contornoSuperior'>&nbsp;</td>
		<td class='session3_contornoSuperior'>&nbsp;</td>
		<td class='session2_textoTotal' align='right'>TOTALES</td>
		<td class='session2_total1'>".number_format($total[5],2)."</td>
		<td class='session2_total1'>".number_format($total[0],2)."</td>
		<td class='session2_total1'>".number_format($total[1],2)."</td>
		<td class='session2_total1'>".number_format($total[2],2)."</td>
		<td class='session2_total1'>".number_format($total[3],2)."</td>
		<td class='session2_total1'>".number_format($total[4],2)."</td>
	  </tr>";	
	}

	function filaAporteP ($num, $dato, $total, $descuento)
	{
	  $total[0] = $total[0] + ($descuento['seguroprofesional'] * $dato['totalganado']) ;
	  $total[1] = $total[1] + ($descuento['provivienda'] * $dato['totalganado']);
	  $total[2] = $total[2] + ($descuento['aportepatronal'] * $dato['totalganado']);
	  $totalAfp = $dato['totalganado'] * $descuento['seguroprofesional'] 
	    + $dato['totalganado'] * $descuento['provivienda'] +
		$dato['totalganado'] * $descuento['aportepatronal'];
	  $total[3] = $total[3] + $totalAfp;  
	  $total[4] = $total[4] + $dato['totalganado'];     
			
	  echo "  <tr>
		<td class='session2_datos2'>$num</td>
		<td class='session2_datos2_1' align='left'>$dato[trabajador]</td>
		<td class='session2_datos2_1'>$dato[sexo]</td>
		<td class='session2_datos2_1'>$dato[carnetidentidad]</td>
		<td class='session2_datos2_1'>$dato[diastrabajados]</td>
		<td class='session2_datos2_1'>".number_format($dato['totalganado'],2)."</td>
		<td class='session2_datos2_1'>".number_format(($descuento['seguroprofesional'] * $dato['totalganado']),2)."</td>
		<td class='session2_datos2_1'>".number_format(($descuento['provivienda'] * $dato['totalganado']),2)."</td>
		<td class='session2_datos2_1'>".number_format(($descuento['aportepatronal'] * $dato['totalganado']),2)."</td>
		<td class='session2_datos2_2'>".number_format($totalAfp,2)."</td>
		<td>&nbsp;</td>
	  </tr>";	
	  return $total;
	}

	function totalAP ($total)
	{
	  echo "<tr>
		<td class='session3_contornoSuperior'>&nbsp;</td>
		<td class='session3_contornoSuperior'>&nbsp;</td>
		<td class='session3_contornoSuperior'>&nbsp;</td>
		<td class='session3_contornoSuperior'>&nbsp;</td>
		<td class='session2_textoTotal' align='right'>TOTALES</td>
		<td class='session2_total1'>".number_format($total[4],2)."</td>
		<td class='session2_total1'>".number_format($total[0],2)."</td>
		<td class='session2_total1'>".number_format($total[1],2)."</td>
		<td class='session2_total1'>".number_format($total[2],2)."</td>
		<td class='session2_total1'>".number_format($total[3],2)."</td>
		<td >&nbsp;</td>
	  </tr>";	
	}

	$sqlAporteLaboral = "select left(concat(t.nombre,' ',t.apellido),25)as 'trabajador'
	,t.sexo,t.carnetidentidad,p.diastrabajados,
	p.totalganado  
	from planilla p,trabajador t
	where p.idtrabajador=t.idtrabajador
	and month(p.fecha)=$mes
	and year(p.fecha)=$anio
	and p.estado=1;";
	
	$sql = "select *from datosplanilla;";
	$descuentoL = $db->arrayConsulta($sql);
	$numFila = 0;
	$numFilaAP = 0;
	$totalAL = array(0,0,0,0,0,0);
	$totalAP = array(0,0,0,0,0);
	$Max = 38;
	$sql = "select *from datosplanilla;";
	$datosPlanilla = $db->arrayConsulta($sql);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planilla AFP</title>
<link rel="stylesheet" href="afp.css" type="text/css" />
</head>
<body>

<?php
  $consultaAL = $db->consulta($sqlAporteLaboral);  
  $consultaAP = $db->consulta($sqlAporteLaboral);
  $totalItem = $db->getnumRow($sqlAporteLaboral);
  $totalItemAFP = $db->getnumRow($sqlAporteLaboral);
  
  while (($numFila < $totalItem) or ( $numFilaAP < $totalItemAFP) ){
	$cantidadItem = 1;  
?>
<div style=" position : absolute;left:5%; top:20px;"></div>
<div class="session1_datoempresa"><?php echo strtoupper($datoGeneral['nombrecomercial']);  ?></div>
<div class="session1_datoempresa_nit"><?php echo "NIT: $datoGeneral[nit]"; ?></div>
<div class="session1_logotipo">
   <?php echo "<img src='../$datoGeneral[imagen]' width='200' height='70'/>"; ?>
  </div>
<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1">PLANILLA PARA LA AFP</td></tr>
     <tr><td align="center">CORRESPONDIENTE AL MES DE <?php echo $db->mes($mes); ?> DE <?php echo $anio; ?></td></tr>
     <tr><td align="center">(Expresado en Bolivianos)</td></tr>
     <tr><td align="center">&nbsp;</td></tr>
    </table>
  </div>

 <div class="session2_datosPersonales">
   <?php
     if ($numFila < $totalItem) {
   ?>
   <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2" class="session2_textoTitulo">APORTE LABORAL</td>
    <td width="4%">&nbsp;</td>
    <td width="7%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
    <td width="9%">&nbsp;</td>
    <td width="11%">&nbsp;</td>
    <td width="10%">&nbsp;</td>
    <td width="11%">&nbsp;</td>
    <td width="10%">&nbsp;</td>
  </tr>
  <tr>
    <td width="2%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="session2_esquinaderecha">&nbsp;</td>
    <td colspan="4" class="session2_titulosCabecera_arriba">DESCUENTO LABORAL PARA AFP</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="session2_titulosCabecera2">Nº</td>
    <td class="session2_titulosCabecera">Nombre</td>
    <td class="session2_titulosCabecera">Sexo</td>
    <td class="session2_titulosCabecera">C.I.</td>
    <td class="session2_titulosCabecera">Dias Trabajados</td>
    <td class="session2_titulosCabecera">Total Ganado</td>
    <td class="session2_titulosCabecera">Seguro de Vejez <?php echo ($datosPlanilla['segurovejez'] * 100);?>%</td>
    <td class="session2_titulosCabecera">Riesgo Comun <?php echo ($datosPlanilla['riesgocomun'] * 100);?>%</td>
    <td class="session2_titulosCabecera">Comision AFP <?php echo ($datosPlanilla['comisionafp'] * 100);?>%</td>
    <td class="session2_titulosCabecera">Aporte Solidario <?php echo ($datosPlanilla['aportesolidario'] * 100);?>%</td>
    <td class="session2_titulosCabecera">Total AFP <?php echo (($datosPlanilla['segurovejez'] + $datosPlanilla['riesgocomun'] +
	$datosPlanilla['comisionafp'] + $datosPlanilla['aportesolidario']) * 100);?>%</td>
  </tr>
  <?php
   $i = 0;
   while ($dato = mysql_fetch_array($consultaAL)) {
	 $i++;   
	 $numFila++;
	 $cantidadItem++;
  	 $totalAL = filainicialAporteL($numFila,$dato,$totalAL,$datosPlanilla);
	 
	 if ($cantidadItem == $Max)
	  break;	   
   }
   
   totalAL($totalAL);
  ?>
  
  
</table>
<?php
	 }
?>

 <?php
     if (($numFila >= $totalItem) and ( $numFilaAP < $totalItemAFP) and ($cantidadItem < 32)) {
		 
		  
   ?>

 <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2" class="session2_textoTitulo">APORTE PATRONAL</td>
    <td width="4%">&nbsp;</td>
    <td width="7%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
    <td width="9%">&nbsp;</td>
    <td width="11%">&nbsp;</td>
    <td width="10%">&nbsp;</td>
    <td width="11%">&nbsp;</td>
    <td width="10%">&nbsp;</td>
  </tr>
  <tr>
    <td width="2%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="session2_esquinaderecha">&nbsp;</td>
    <td colspan="3" class="session2_titulosCabecera_arriba">APORTE PATRONAL PARA AFP</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="session2_titulosCabecera2">Nº</td>
    <td class="session2_titulosCabecera">Nombre</td>
    <td class="session2_titulosCabecera">Sexo</td>
    <td class="session2_titulosCabecera">C.I.</td>
    <td class="session2_titulosCabecera">Dias Trabajados</td>
    <td class="session2_titulosCabecera">Total Ganado</td>
    <td class="session2_titulosCabecera">Seguro Profesional <?php echo ($datosPlanilla['seguroprofesional'] * 100);?>%</td>
    <td class="session2_titulosCabecera">Provivienda <?php echo ($datosPlanilla['provivienda'] * 100);?>%</td>
    <td class="session2_titulosCabecera">Aporte Patronal Solidario <?php echo ($datosPlanilla['aportepatronal'] * 100);?>%</td>
    <td class="session2_titulosCabecera"><p>Total AFP 
	<?php echo (($datosPlanilla['aportepatronal'] + $datosPlanilla['provivienda'] + $datosPlanilla['seguroprofesional']) * 100);?>%
    </p></td>
    <td >&nbsp;</td>
  </tr>
  
  <?php
   $i = 0;

   while ($dato = mysql_fetch_array($consultaAP)) {
	 $i++;
	 $numFilaAP++;
	 $cantidadItem++;
 	 $totalAP = filaAporteP($numFilaAP, $dato, $totalAP, $datosPlanilla); 	   
	 
	 if ($cantidadItem == ($Max-7))
	  break;
   }
   
	 totalAP($totalAP);  
  ?>

</table>
<?php
	 }
?>

 </div>
 
 
<div class="session4_datos"> 
 <table width="100%" border="0">
  <tr>
    <td colspan="2" class="session4_textos2">CODIGO AFILIADO</td>
    <td width="10%">&nbsp;</td>
    <td width="26%" class="session4_textos_1"><?php echo $datoGeneral['reprepropietario'];?></td>
    <td width="6%">&nbsp;</td>
    <td width="13%" class="session4_textos_1"><?php echo $datoGeneral['cipropietario'];?></td>
    <td width="6%">&nbsp;</td>
    <td width="15%">&nbsp;</td>
    <td width="7%">&nbsp;</td>
  </tr>
  <tr>
    <td width="5%" class="session4_textos2" align="right">Nº</td>
    <td width="12%" class="session4_textos2" align="left"><?php echo $datoGeneral['numafiliado'];?></td>
    <td>&nbsp;</td>
    <td class="session4_textos">Nombre Empleador o Representante</td>
    <td>&nbsp;</td>
    <td class="session4_textos">Nº de C.I.</td>
    <td>&nbsp;</td>
    <td class="session4_textos">Firma</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

 </div>
 
<div class="session5_pie"> 
    <table width="93%" border="0" align="center">
  <tr>
    <td width="130" align="right">Realizado por.</td>
    <td width="224" ><?php echo $_SESSION['nombre_usuario'];?></td>
    <td width="93">&nbsp;</td>
    <td width="183" ></td>
    <td width="191">&nbsp;</td>
    <td width="200" >Impreso: <?php echo date("d/m/Y");?></td>
    <td width="206">Hora: <?php echo date("H:i:s");?></td>
  </tr>
  </table>
</div>

 <?php	       
	   if (($numFila < $totalItem) or ( $numFilaAP < $totalItemAFP) ) {
	     for ($i = 1; $i <= 49; $i++) {
    	     echo "<br>";
		 }

	   }
 }
 ?>

</body>
</html>
<?php
	$mpdf=new mPDF('utf-8','Letter'); 
	$content = ob_get_clean();
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit;
?>