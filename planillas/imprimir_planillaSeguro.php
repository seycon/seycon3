<?php
	session_start();
	if (!isset($_SESSION['softLogeoadmin']) || !isset($_GET['meses']) || !isset($_GET['anio'])) {
		header("Location: ../index.php");	
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
	
	$sqlSeguro = "select left(concat(t.nombre,' ',t.apellido),25)as 'trabajador'
	,t.sexo,t.carnetidentidad,p.diastrabajados,
	p.totalganado,t.seguromedico 
	from planilla p,trabajador t
	where p.idtrabajador=t.idtrabajador
	and month(p.fecha)=$mes
	and year(p.fecha)=$anio 
	and p.estado=1;";
	
	
	//$boleta = $db->arrayConsulta($sql);
	function insertarFilaInicial($num, $dato, $seguro)
	{
	  $total = $dato['totalganado'] * $seguro; 	
	 echo "  <tr>
		<td class='session2_datos1'>$num</td>
		<td class='session2_datos1_1' align='left'>$dato[trabajador]</td>
		<td class='session2_datos1_1'>$dato[sexo]</td>
		<td class='session2_datos1_1'>$dato[carnetidentidad]</td>
		<td class='session2_datos1_1'>$dato[diastrabajados]</td>
		<td class='session2_datos1_1'>".number_format($dato['totalganado'],2)."</td>
		<td class='session2_datos1_1'>$dato[seguromedico]</td>
		<td class='session2_datos1_2'>".number_format($total,2)."</td>
	  </tr>"; 	
	  return $total;
	}
	
	function insertarFila($num, $dato, $seguro)
	{ 
	   $total = $dato['totalganado'] * $seguro; 	
	   echo "  <tr>
		<td class='session2_datos2'>$num</td>
		<td class='session2_datos2_1' align='left'>$dato[trabajador]</td>
		<td class='session2_datos2_1'>$dato[sexo]</td>
		<td class='session2_datos2_1'>$dato[carnetidentidad]</td>
		<td class='session2_datos2_1'>$dato[diastrabajados]</td>
		<td class='session2_datos2_1'>".number_format($dato['totalganado'],2)."</td>
		<td class='session2_datos2_1'>$dato[seguromedico]</td>
		<td class='session2_datos2_2'>".number_format($total,2)."</td>
	  </tr>"; 	
	  return $total;
	}
	
	function insertarTotal($total)
	{
	  echo " 
	   <tr>
		<td class='session3_contornoSuperior'>&nbsp;</td>
		<td class='session3_contornoSuperior'>&nbsp;</td>
		<td class='session3_contornoSuperior'>&nbsp;</td>
		<td class='session3_contornoSuperior'>&nbsp;</td>
		<td class='session3_contornoSuperior'>&nbsp;</td>
		<td class='session3_contornoSuperior'>&nbsp;</td>
		<td class='session2_TextoTotal'>TOTAL</td>
		<td class='session2_totalDato'>".number_format($total,2)."</td>
	   </tr>"; 
	}
	
	function nextPage()
	{
		for ($i = 1; $i <= 48; $i++) {
		    echo "<br />";	
		}
	}
	
	$sql = "select *from datosplanilla;";
	$datosPlanilla = $db->arrayConsulta($sql);
	$porcentajeSeguro = $datosPlanilla['seguromedico'];	
	$numFila = 0;
	$totalSeguro = 0;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planilla de Seguro</title>
<link rel="stylesheet" href="seguro.css" type="text/css" />
</head>

<body>
<?php
	$header = '';		
	$seguros = $db->consulta($sqlSeguro);
	$totalItem = $db->getnumRow($sqlSeguro);
	
	while ($numFila < $totalItem ) {
?>
<div style=" position : absolute;left:5%; top:20px;"></div>
<div class="session1_datoempresa"><?php echo strtoupper($datoGeneral['nombrecomercial']);  ?></div>
<div class="session1_datoempresa_nit"><?php echo "NIT: $datoGeneral[nit]"; ?></div>
<div class="session1_logotipo"><?php echo "<img src='../$datoGeneral[imagen]' width='200' height='70'/>"; ?></div>


 <div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1">PLANILLA DE SEGURO MEDICO</td></tr>
     <tr><td align="center">CORRESPONDIENTE AL MES DE <?php echo $db->mes($mes); ?> DE <?php echo $anio; ?></td></tr>
     <tr><td align="center">(Expresado en Bolivianos)</td></tr>
     <tr><td align="center">&nbsp;</td></tr>
    </table>
 </div>
 
 <div class="session2_datosPersonales">
 <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="6%" class="session2_titulosCabecera2">Nº</td>
    <td width="22%" class="session2_titulosCabecera">Nombre</td>
    <td width="7%" class="session2_titulosCabecera">Sexo</td>
    <td width="12%" class="session2_titulosCabecera">C.I.</td>
    <td width="11%" class="session2_titulosCabecera">Dias Trabajador</td>
    <td width="11%" class="session2_titulosCabecera">Total Ganado</td>
    <td width="16%" class="session2_titulosCabecera">Nº de Seguro del Trabajador</td>
    <td width="15%" class="session2_titulosCabecera">Seguro Medico 10%</td>
  </tr>
  <?php
     $i = 0;
     while ($dato = mysql_fetch_array($seguros)) {
	   $numFila++;
	   $i++;
	   $totalSeguro = $totalSeguro + insertarFilaInicial($numFila,$dato,$porcentajeSeguro); 
	   if ($i == 40)
		 break;
	 }  
	 insertarTotal($totalSeguro);
  ?>  
 </table>
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
	       
	   if ($numFila < $totalItem ) {
           nextPage();
	   }
    }
   ?>
</body>
</html>
<?php
	$mpdf=new mPDF('utf-8','Letter'); 
	$content = ob_get_clean();
	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit;
?>