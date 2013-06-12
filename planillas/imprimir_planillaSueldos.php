<?php
	session_start();
	if (!isset($_SESSION['softLogeoadmin']) || !isset($_GET['meses'])
	     || !isset($_GET['anio']) || !isset($_GET['sucursal'])) {
		header("Location: ../index.php");	
	}
	ob_start();
	include("../MPDF53/mpdf.php");
	include("../conexion.php");
	$db = new MySQL();
	$mes = $_GET['meses'];
	$anio = $_GET['anio'];
	$sucursal = $_GET['sucursal'];
	if ($_GET['sucursal'] == "") {
	   $sucursal = 0;	
	}
	
	
	$sql = "select imagen,left(nombrecomercial,18)as 'nombrecomercial',nit
	,left(reprepropietario,20)as 'reprepropietario',cipropietario,numafiliado from empresa;";
	$datoGeneral = $db->arrayConsulta($sql);
	$sql = "select * from sucursal where idsucursal = $sucursal";
	$datoSucursal= $db->arrayConsulta($sql);
	$subtotal = array(0,0,0,0,0,0,0,0,0,0,0,0);
	$totales = array(0,0,0,0,0,0,0,0,0,0,0,0);

	function iniciarSession($nombreSession)
	{
		echo "<div class='diferencia'></div>
		<table width='1251' border='0' cellpadding='0' cellspacing='0'>
		<tr>
		  <td width='21' class='session3_textos_1'>Nº</td>
		   <td colspan='14' class='session3_border3_titulo'>
		   <strong>DEPARTAMENTO:</strong>&nbsp;".strtoupper($nombreSession)
		."</td>
		</tr>";	
	}


	function limpiarSubTotal()
	{
	  return array(0,0,0,0,0,0,0,0,0,0,0,0);	
	}

	function aumentarTotales($subTotal, $total)
	{
		for ($i = 0 ;$i <= 11 ;$i++) {
		  $total[$i] = $total[$i] + $subTotal[$i];	
		}
		return $total;
	}
	
	function cerrarSession()
	{
	  echo " <tr>
		<td height='5'></td>
		<td colspan='14' class='session3_border5'></td>
	  </tr>
	</table>";	
	}
	
	function setHeader()
	{
	  echo "
	    <table width='1249' border='0' cellpadding='0' cellspacing='0' >
		<tr>
		  <td width='88' rowspan='2' >&nbsp;</td>
		  <td width='72' rowspan='2' class='session2_tabla_1_titulo'>Dias Pag./Mes</td>
		  <td width='82' rowspan='2' class='session2_tabla_1_titulo'>Haber Basico</td>
		  <td width='77' rowspan='2' class='session2_tabla_1_titulo'>Bono de Antiguedad</td>
		  <td colspan='2' class='session2_tabla_1_titulo'>Horas Extras</td>
		  <td colspan='2' class='session2_tabla_1_titulo'>Bonos</td>
		  <td width='65' rowspan='2' class='session2_tabla_1_titulo'>Total Ganado</td>
		  <td colspan='3' class='session2_tabla_1_titulo'>Descuentos</td>
		  <td width='79' rowspan='2' class='session2_tabla_1_titulo'>Total Descuento</td>
		  <td width='124' rowspan='2' class='session2_tabla_1_titulo'>Liquido Pagable</td>
		  <td width='240' rowspan='2' class='session2_tabla_3_titulo'>Firma</td>
		</tr>
		<tr>
		  <td width='37' class='session2_tabla_2'>Nº</td>
		  <td width='80' class='session2_tabla_2'>Importe</td>
		  <td width='73' class='session2_tabla_2'>Prod.</td>
		  <td width='67' class='session2_tabla_2'>Otros</td>
		  <td width='53' class='session2_tabla_2'>A.F.P</td>
		  <td width='54' class='session2_tabla_2'>RC-IVA</td>
		  <td width='56' class='session2_tabla_2'>Anticipos</td>
		 </tr>
	   </table>  
     ";	
	}
	
	function insertarTrabajador($dato, $num, $subTotales)
	{
		echo "<tr>
		<td>$num</td>
		<td width='74' class='session3_border4_L'>Nombre:</td>
		<td width='165' class='textos'>$dato[nombre]</td>
		<td width='28' class='session3_textos'>C.I.:</td>
		<td width='101' class='textos'>$dato[carnetidentidad]</td>
		<td width='84' class='session3_textos'>Nacionalidad:</td>
		<td width='126' class='textos'>$dato[nacionalidad]</td>
		<td width='60' class='session3_textos'>Cargo:</td>
		<td width='110' class='textos'>$dato[cargo]</td>
		<td width='98' class='session3_textos'>Fecha de Nac.:</td>
		<td width='97' class='textos'>$dato[fechanacimiento]</td>
		<td width='94' class='session3_textos'>Fecha de Ing.:</td>
		<td width='98' class='textos'>$dato[fechaingreso]</td>
		<td width='39' class='session3_textos'>Sexo:</td>
		<td width='56' class='session3_border4_R'>$dato[sexo]</td>
	  </tr>";
	  $subTotales = insertarMontosTrabajador($dato,$subTotales);
	  return $subTotales;
	}

	function insertarMontosTrabajador($dato, $subTotales)
	{
		$otrosBonos = $dato['transporte'] +  $dato['puntualidad'] + $dato['comisiones'] 
		+ $dato['asistencia'] ;
		$liquido = $dato['totalganado'] - $dato['totaldescuento'];
		
		$suma = array($dato['sueldobasico'],0,$dato['horasextras'],$dato['importehorasextras']
		,$dato['bonoproduccion'],$otrosBonos,
		$dato['totalganado'],$dato['afp'],0,$dato['anticipo'],$dato['totaldescuento'],$liquido);
		for ($j = 0 ; $j <= 12 ;$j++) {
		  $subTotales[$j] = $subTotales[$j] + $suma[$j];
		}
		
		echo "<tr>
		<td>&nbsp;</td>
		<td colspan='14' class='session3_border4'>
		<table width='1230' border='0' cellpadding='0' cellspacing='0'>
		  <tr>
			<td width='59'>&nbsp;</td>
			<td width='82' class='session3_borde1'>30</td>
			<td width='79' class='session3_borde1'>".number_format($dato['sueldobasico'],2)."</td>
			<td width='79' class='session3_borde1'>".number_format($dato['bonoantiguedad'],2)."</td>
			<td width='37' class='session3_borde1'>".number_format($dato['horasextras'],2)."</td>
			<td width='80' class='session3_borde1'>".number_format($dato['importehorasextras'],2)."</td>
			<td width='70' class='session3_borde1'>".number_format($dato['bonoproduccion'],2)."</td>
			<td width='69' class='session3_borde1'>".number_format($otrosBonos,2)."</td>
			<td width='63' class='session3_borde1'>".number_format($dato['totalganado'],2)."</td>
			<td width='55' class='session3_borde1'>".number_format($dato['afp'],2)."</td>
			<td width='55' class='session3_borde1'>0</td>
			<td width='60' class='session3_borde1'>".number_format($dato['anticipo'],2)."</td>
			<td width='80' class='session3_borde1'>".number_format($dato['totaldescuento'],2)."</td>
			<td width='123' class='session3_borde1'>".number_format($liquido,2)."</td>
			<td width='239' class='session3_borde1'></td>
		  </tr>      
		</table>
		</td>    
		</tr>";
		return $subTotales;
	}

	function insertarFilaBasia()
	{
		echo "  <tr>
				  <td>&nbsp;</td>
				  <td colspan='14' class='session3_border4'>&nbsp;</td>
				</tr>";
	}
	
	function nextPage() 
	{
	    for ($i = 1; $i <= 70; $i++) {
			  echo "<br />";
		}	
	}

	function insertarSubTotal($subtotal)
	{
	  echo "      
	  <tr>
		<td>&nbsp;</td>
		<td colspan='14' class='session3_border4'>
		  <table width='1230' border='0' cellpadding='0' cellspacing='0'>
		   <tr>
			<td width='59'>&nbsp;</td>
			<td width='82' class='session4_textos3'>SUB TOTAL</td>
			<td width='79' class='session3_borde1'>".number_format($subtotal[0],2)."</td>
			<td width='79' class='session3_borde1'>".number_format($subtotal[1],2)."</td>
			<td width='37' class='session3_borde1'>".number_format($subtotal[2],2)."</td>
			<td width='80' class='session3_borde1'>".number_format($subtotal[3],2)."</td>
			<td width='70' class='session3_borde1'>".number_format($subtotal[4],2)."</td>
			<td width='69' class='session3_borde1'>".number_format($subtotal[5],2)."</td>
			<td width='63' class='session3_borde1'>".number_format($subtotal[6],2)."</td>
			<td width='55' class='session3_borde1'>".number_format($subtotal[7],2)."</td>
			<td width='55' class='session3_borde1'>".number_format($subtotal[8],2)."</td>
			<td width='60' class='session3_borde1'>".number_format($subtotal[9],2)."</td>
			<td width='80' class='session3_borde1'>".number_format($subtotal[10],2)."</td>
			<td width='123' class='session3_borde1'>".number_format($subtotal[11],2)."</td>
			<td width='239' class='session3_borde1_1'>&nbsp;</td>
		   </tr>      
		  </table>
		</td>
	  </tr>";
	}

	function insertarTotales($totales)
	{
	  echo " <table width='1249' border='0' cellpadding='0' cellspacing='0' >
	  <tr>
		<td width='74' height='5'></td>
		<td width='87' ></td>
		<td width='81' ></td>
		<td width='78' ></td>
		<td width='38' ></td>
		<td width='79' ></td>
		<td width='70' ></td>
		<td width='67' ></td>
		<td width='66' ></td>
		<td width='54' ></td>
		<td width='54' ></td>
		<td width='61' ></td>
		<td width='81' ></td>
		<td width='127'></td>
		<td width='232' ></td>
	  </tr>
	  <tr>
		<td width='74' >&nbsp;</td>
		<td width='87' class='session4_totales'>TOTALES</td>
		<td width='81' class='session2_tabla_1'>".number_format($totales[0],2)."</td>
		<td width='78' class='session2_tabla_1'>".number_format($totales[1],2)."</td>
		<td width='38' class='session2_tabla_1'>".number_format($totales[2],2)."</td>
		<td width='79' class='session2_tabla_1'>".number_format($totales[3],2)."</td>
		<td width='70' class='session2_tabla_1'>".number_format($totales[4],2)."</td>
		<td width='67' class='session2_tabla_1'>".number_format($totales[5],2)."</td>
		<td width='66' class='session2_tabla_1'>".number_format($totales[6],2)."</td>
		<td width='54' class='session2_tabla_1'>".number_format($totales[7],2)."</td>
		<td width='54' class='session2_tabla_1'>".number_format($totales[8],2)."</td>
		<td width='61' class='session2_tabla_1'>".number_format($totales[9],2)."</td>
		<td width='81' class='session2_tabla_1'>".number_format($totales[10],2)."</td>
		<td width='127' class='session2_tabla_3'>".number_format($totales[11],2)."</td>
		<td width='232' >&nbsp;</td>
	  </tr>
	</table> ";	
	}
	$Num = 0;
	$sqlPlanilla = "
	SELECT d.nombre as 'departamento',p.idplanilla
	,left(concat(t.nombre,' ', t.apellido),25)as 'nombre', t.sexo, t.carnetidentidad,
	t.nacionalidad,date_format(t.fechanacimiento,'%d/%m/%Y')as 'fechanacimiento'
	,p.importehorasextras, 
	c.cargo,date_format(t.fechaingreso,'%d/%m/%Y')as 'fechaingreso'
	, ROUND( t.sueldobasico, 2 ) AS  'sueldobasico'
	, ROUND( b.bonoproduccion, 2 ) AS  'bonoproduccion',
	 b.horasextras, b.transporte, b.puntualidad, b.comisiones
	 , b.asistencia, ROUND( p.anticipo, 2 ) AS  'anticipo', 
	ROUND( p.totalganado, 2 ) AS  'totalganado', 
	ROUND( p.totaldescuento, 2 ) AS  'totaldescuento', 
	ROUND( p.afp, 2 ) AS  'afp',p.bonoantiguedad 
	FROM planilla p, trabajador t, cargo c, bono b,departamento d
	WHERE p.idtrabajador = t.idtrabajador
	AND t.idcargo = c.idcargo
	AND t.seccion = d.iddepartamento 
	AND b.idbono = p.idbono
	AND MONTH( p.fecha ) =$mes
	AND YEAR( p.fecha ) =$anio
	AND p.estado=1 
	AND t.idsucursal =$sucursal order by d.nombre;";
	$resultado = $db->consulta($sqlPlanilla);
	$seccionActual = "";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planilla Sueldos</title>
<link rel="stylesheet" href="sueldos.css" type="text/css" />
</head>

<body>

<?php
	$totalItem = $db->getnumRow($sqlPlanilla);
	while ($Num < $totalItem ) {
?>
  <div style=" position : absolute;left:5%; top:20px;"></div>
  <div class="session1_datoempresa"><?php echo strtoupper($datoGeneral['nombrecomercial']);  ?></div>
  <div class="session1_datoempresa_nit"><?php echo "NIT: $datoGeneral[nit]"; ?></div>
  <div class="session1_logotipo">
   <?php echo "<img src='../$datoGeneral[imagen]' width='200' height='70'/>"; ?>
  </div>
 
<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1">PLANILLA DE SUELDOS Y SALARIOS</td></tr>
     <tr><td align="center">CORRESPONDIENTE AL MES DE <?php echo $db->mes($mes); ?> DE <?php echo $anio; ?></td></tr>
     <tr><td align="center">(Expresado en Bolivianos)</td></tr>
     <tr><td align="left"><strong>SUCURSAL:</strong>&nbsp;&nbsp;<?php echo $datoSucursal['nombrecomercial'];?></td></tr>
    </table>
</div>
 
<div class="session2_cabecera">  
<?php
    setHeader();
?>  
</div>

<div class="session3_cabecera">
<?php
	$i = 0;
	while ($planilla = mysql_fetch_array($resultado)) {
	   $seccion = $planilla['departamento'];	 
	   if ($seccionActual != $seccion || $i == 0) {
		  if ($seccionActual != "" ) {
			  if ($i != 0) {  
				 insertarSubTotal($subtotal);
				 $totales = aumentarTotales($subtotal,$totales);
				 $subtotal = limpiarSubTotal();
				 cerrarSession();		
				 $i = $i + 2; 
			  } else {
				 if ($seccionActual != $seccion) 
				     $subtotal = limpiarSubTotal();	
			  }
		  }
		  $seccionActual = $seccion;
		  iniciarSession($seccionActual);
		  $i = $i + 2;	
	   }
	   $Num++;  
	   $subtotal = insertarTrabajador($planilla, $Num, $subtotal);
	   $i = $i + 2;	
	 
	   if ($i >= 30) {
		   break;
	   }	   
	}
	
	insertarSubTotal($subtotal);
	$totales = aumentarTotales($subtotal, $totales);
	cerrarSession(); 
	if ($Num >= $totalItem) {
	  insertarTotales($totales);
	}
?>
</div>
 

<div class="session4_datos"> 
 <table width="100%" border="0">
  <tr>
    <td width="6%"></td>
    <td width="9%">&nbsp;</td>
    <td width="5%">&nbsp;</td>
    <td width="24%" class="session5_textoPie"><?php echo $datoGeneral['reprepropietario'];?></td>
    <td width="6%">&nbsp;</td>
    <td width="13%" class="session5_textoPie"><?php echo $datoGeneral['cipropietario'];?></td>
    <td width="6%">&nbsp;</td>
    <td width="11%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
  </tr>
  <tr>
    <td class="session4_textos3">&nbsp;</td>
    <td>&nbsp;</td>
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
    <table width="93%" border="0" align="center" class="session5_tabla">
      <tr>
       <td width="139" align="right">Realizado por. </td>
       <td width="174" align="left"><?php echo $_SESSION['nombre_usuario'];?></td>
       <td width="85">&nbsp;</td>
       <td width="176">&nbsp;</td>
       <td width="349">&nbsp;</td>
       <td width="123">Impreso: <?php echo date("d/m/Y");?></td>
       <td width="90">Hora: <?php echo date("H:i:s");?></td>
      </tr>
    </table>
</div>
 
<?php	 	       
	   if ($Num < $totalItem) {
		  nextPage();  
	   }
    }
?>

</body>
</html>

<?php
	$mpdf=new mPDF('utf-8','Letter-L'); 
	$content = ob_get_clean();
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit;
?>
