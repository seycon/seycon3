<?php
	session_start();
	if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: ../index.php");	
	}
	ob_start();
	include("../MPDF53/mpdf.php");
	include("../conexion.php");
	include("../aumentaComa.php");
	$db = new MySQL();
	$mes = $_POST['meses'];
	$anio = $_POST['anio'];
	$sucursal = $_POST['sucursal'] ;
	$sql = "select imagen,left(nombrecomercial,18)as 'nombrecomercial',nit
	,left(reprepropietario,20)as 'reprepropietario',cipropietario,numafiliado from empresa;";
	$datoGeneral = $db->arrayConsulta($sql);
	$sql = "select * from sucursal where idsucursal = $sucursal";
	$datoSucursal= $db->arrayConsulta($sql);
	$sumatoria = array();
	$sumatoria[0] = 0.00;
	$sumatoria[1] = 0.00;
	$sumatoria[2] = 0.00;
	$sumatoria[3] = 0.00;
	$sumatoria[4] = 0.00;
	$sumatoria[5] = 0.00;
	$sumatoria[6] = 0.00;
	
	function insertarFilaInicial($dato, $num, $sumatoria)
	{
		for ($j = 0; $j <= 6; $j++)
		 $sumatoria[$j] =  $sumatoria[$j] + $dato[$j+3] ;
	
		  echo " <tr>
			  <td width='3%' class='session3_tabla1'>$num</td>
			  <td width='22%' align='left' class='session3_tabla1_1'>$dato[0]</td>
			  <td width='13%' class='session3_tabla1_1'>$dato[1]</td>
			  <td width='9%' class='session3_tabla1_1'>$dato[2]</td>
			  <td width='8%' class='session3_tabla1_1'>".convertir($dato[3])."</td>
			  <td width='8%' class='session3_tabla1_1'>".convertir($dato[4])."</td>
			  <td width='5%' class='session3_tabla1_1'>$dato[5]</td>
			  <td width='8%' class='session3_tabla1_1'>".convertir($dato[6])."</td>
			  <td width='8%' class='session3_tabla1_1'>".convertir($dato[7])."</td>
			  <td width='8%' class='session3_tabla1_1'>".convertir($dato[8])."</td>
			  <td width='8%' class='session3_tabla1_1'>".convertir($dato[9])."</td>
			</tr>";	
	  return $sumatoria;
	}
	
	function insertarFila($dato, $num, $sumatoria) 
	{
		for ($j = 0; $j <= 6; $j++)
		 $sumatoria[$j] = $sumatoria[$j] + $dato[$j+3] ;
		echo "  <tr>
		  <td class='session3_tabla2'>$num</td>
		  <td class='session3_tabla2_1' align='left'>$dato[0]</td>
		  <td class='session3_tabla2_1'>$dato[1]</td>
		  <td class='session3_tabla2_1'>$dato[2]</td>
		  <td class='session3_tabla2_1'>".convertir($dato[3])."</td>
		  <td class='session3_tabla2_1'>".convertir($dato[4])."</td>
		  <td class='session3_tabla2_1'>$dato[5]</td>
		  <td class='session3_tabla2_1'>".convertir($dato[6])."</td>
		  <td class='session3_tabla2_1'>".convertir($dato[7])."</td>
		  <td class='session3_tabla2_1'>".convertir($dato[8])."</td>
		  <td class='session3_tabla2_1'>".convertir($dato[9])."</td>
		</tr>";	
		return $sumatoria;
	}
	
	function totales($sumatoria)
	{
	   echo " <table width='100%' border='0' cellpadding='0' cellspacing='0'>
		 <tr>
		<td width='3%' height='3'></td>
		<td width='22%'></td>
		<td width='13%'></td>
		<td width='9%'></td>
		<td width='8%'></td>
		<td width='8%'></td>
		<td width='5%'></td>
		<td width='8%'></td>
		<td width='8%'></td>
		<td width='8%'></td>
		<td width='8%'></td>
	  </tr>
	  <tr>
		<td width='3%'>&nbsp;</td>
		<td width='22%'>&nbsp;</td>
		<td width='13%'>&nbsp;</td>
		<td width='9%' align='right'><strong>TOTAL</strong></td>
		<td width='8%' class='session3_remarcado1'>".convertir(number_format($sumatoria[0],2))."</td>
		<td width='8%' class='session3_remarcado1'>".convertir(number_format($sumatoria[1],2))."</td>
		<td width='5%' class='session3_remarcado1'>".convertir(number_format($sumatoria[2],2))."</td>
		<td width='8%' class='session3_remarcado1'>".convertir(number_format($sumatoria[3],2))."</td>
		<td width='8%' class='session3_remarcado1'>".convertir(number_format($sumatoria[4],2))."</td>
		<td width='8%' class='session3_remarcado1'>".convertir(number_format($sumatoria[5],2))."</td>
		<td width='8%' class='session3_remarcado2'>".convertir(number_format($sumatoria[6],2))."</td>
	  </tr>
	</table>";	
	}
	
	function nextPage() 
	{
	    for ($i = 1; $i <= 49; $i++) {
			  echo "<br />";
		}	
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planilla de Bonos</title>
<link rel="stylesheet" href="bonos.css" type="text/css" />
</head>

<body>
<?php
	  $sqlBono = "select left(concat(t.nombre,' ',t.apellido),25)as 'trabajador',left(c.cargo,18)as 'cargo',
        date_format(t.fechaingreso,'%d/%m/%Y')as 'fechaingreso',round(t.sueldobasico,2)as 'sueldobasico'
		,round(b.bonoproduccion,2)as 'bonoproduccion',b.horasextras,
		round(b.transporte,2) as 'transporte',round(b.puntualidad,2)as 'puntualidad',
        round(b.comisiones,2)as 'comisiones',round(b.asistencia,2) as 'asistencia'  from bono b
		,trabajador t,cargo c where b.idtrabajador=t.idtrabajador and b.estado=1 
        and t.idcargo=c.idcargo and month(b.fecha)='$mes' and year(b.fecha)='$anio' and t.idsucursal = $sucursal;"; 
		
		$result = $db->consulta($sqlBono);
        $totalItem = $db->getnumRow($sqlBono);
        $numFila = 0;
      while ($numFila < $totalItem ) {
?>

<div style=" position : absolute;left:5%; top:20px;"></div>
<div class="session1_datoempresa"><?php echo strtoupper($datoGeneral['nombrecomercial']);  ?></div>
<div class="session1_datoempresa_nit"><?php echo "NIT: $datoGeneral[nit]"; ?></div>
<div class="session1_logotipo">
   <?php echo "<img src='../$datoGeneral[imagen]' width='200' height='70'/>"; ?>
  </div>
 
<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1">PLANILLA DE BONOS</td></tr>
     <tr><td align="center">CORRESPONDIENTE AL MES DE <?php echo $db->mes($mes); ?> DE <?php echo $anio; ?></td></tr>
     <tr><td align="center">(Expresado en Bolivianos)</td></tr>
     <tr><td align="center">&nbsp;</td></tr>
     <tr><td align="left"><strong>SUCURSAL:</strong>&nbsp;&nbsp;<?php echo $datoSucursal['nombrecomercial'];?></td></tr>
    </table>
 </div>
 

  <div class="session2_cabecera">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="session2_tabla">
    <tr>
      <td width="3%" rowspan="2">Nº</td>
      <td width="22%" rowspan="2">Nombre</td>
      <td width="13%" rowspan="2">Cargo</td>
      <td width="9%" rowspan="2">Fecha de Ingreso</td>
      <td width="8%" rowspan="2">Sueldo Básico</td>
      <td width="8%" rowspan="2">Bono de Prod.</td>
      <td width="5%" rowspan="2">Horas Extras</td>
      <td colspan="4">OTROS BONOS</td>
      </tr>
    <tr>
      <td width="8%">Transporte</td>
      <td width="8%">Puntualidad</td>
      <td width="8%">Comisiones</td>
      <td width="8%">Asistencia</td>
    </tr>
  </table>
  </div>
 
 <div class="session3_datos">
 <table width="100%" border="0" cellpadding="0" cellspacing="0" >
 <?php   
   $i = 1;
   while($dato = mysql_fetch_array($result)) {
	   $numFila++;
	   if ($i == 1)
	    $sumatoria = insertarFilaInicial($dato,$numFila,$sumatoria);
	   else 
	    $sumatoria = insertarFila($dato,$numFila,$sumatoria);
	   $i++;	
	   if ($i == 23)
	   break; 
   } 
 ?>   
</table>
<?php totales($sumatoria);?>

 </div>
 
 <div class="session4_datos"> 
 <table width="100%" border="0">
   <tr>
    <td width="3%">&nbsp;</td>
    <td width="3%">&nbsp;</td>
    <td width="10%">&nbsp;</td>
    <td width="29%" class="session4_textos_1"><?php echo $datoGeneral['reprepropietario'];?></td>
    <td width="6%">&nbsp;</td>
    <td width="13%" class="session4_textos_1"><?php echo $datoGeneral['cipropietario'];?></td>
    <td width="7%">&nbsp;</td>
    <td width="14%" ></td>
    <td width="15%">&nbsp;</td>
  </tr>
  <tr>
    <td width="3%">&nbsp;</td>
    <td width="3%">&nbsp;</td>
    <td width="10%">&nbsp;</td>
    <td width="29%" class="session4_textos">Nombre Empleador o Representante</td>
    <td width="6%">&nbsp;</td>
    <td width="13%" class="session4_textos">Nº de C.I.</td>
    <td width="7%">&nbsp;</td>
    <td width="14%" class="session4_textos">Firma</td>
    <td width="15%">&nbsp;</td>
  </tr>
 </table>
 </div>


<div class="session5_pie"> 
  <table width="93%" border="0" align="center">
  <tr>
    <td width="130" align="right">Realizado por.</td>
    <td width="244" ><?php echo $_SESSION['nombre_usuario'];?></td>
    <td width="93">&nbsp;</td>
    <td width="233" ></td>
    <td width="197">&nbsp;</td>
    <td width="180" >Impreso: <?php echo date("d/m/Y");?></td>
    <td width="150">Hora: <?php echo date("H:i:s");?></td>
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
	$mpdf = new mPDF('utf-8','Letter-L'); 
	$content = ob_get_clean();
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit;
?>
