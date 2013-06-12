<?php
	session_start();
	if (!isset($_SESSION['softLogeoadmin'])) {
		   header("Location: ../index.php");	
	}
	ob_start();
	include("../MPDF53/mpdf.php");
	include("../conexion.php");
	include('../reportes/literal.php');
	$db = new MySQL();
	$logo = $_GET['logo'];
	$sql = "select imagen,left(nombrecomercial,13)as 'nombrecomercial',nit from empresa;";
	$datoGeneral = $db->arrayConsulta($sql);	
	$idtransaccion = $_GET['idlibrodiario'];
	$sql = "select numero,moneda,year(fecha)as 'anio',month(fecha)as 'mes',day(fecha)as 'dia',l.tipotransaccion,
	left(glosa,40)as 'glosa',tipocambio,left(s.nombrecomercial,25)as 'nombrecomercial'
	,left(concat(t.nombre,' ',t.apellido),25)as 'usuario' 
	from librodiario l,sucursal s,usuario u,trabajador t
	where l.idsucursal=s.idsucursal
	and  l.idusuario=u.idusuario
	and u.idtrabajador=t.idtrabajador
	and l.idlibrodiario=$idtransaccion;";	
	$datosGenerales = $db->arrayConsulta($sql);
	
	$sql = "select left(p.cuenta,25)as 'cuenta',d.idcuenta,left(d.descripcion,90)as 'descripcion',d.debe,d.haber,d.documento  
	 from detallelibrodiario d,plandecuenta p where d.idcuenta=p.codigo and idlibro=$idtransaccion and p.estado=1
	  order by iddetallelibro;";
	$datoDetalle = $db->consulta($sql);

	$numNota = 0;	
	$totalTransaccion = array();	
	
	function datosGenerales($dato, $db)
	{		
	  echo "<table width='100%' border='0'>
	  <tr>
		<td width='14%' class='session2_titulos'>Fecha:</td>
		<td width='35%' class='session2_titulosDatos'>$dato[dia] de ".strtolower($db->mes($dato['mes']))." de $dato[anio]</td>
		<td width='2%' class='session2_titulos'>&nbsp;</td>
		<td width='16%' class='session2_titulos'>T.C:</td>
		<td width='15%' class='session2_titulosDatos'>$dato[tipocambio]</td>
		<td width='18%' class='session2_titulos'></td>
	  </tr>
	  <tr>
		<td class='session2_titulos'>Glosa:</td>
		<td class='session2_titulosDatos'>$dato[glosa]</td>
		<td class='session2_titulos'>&nbsp;</td>
		<td class='session2_titulos'>Moneda:</td>
		<td class='session2_titulosDatos'>$dato[moneda]</td>
		<td class='session2_titulos'></td>
	  </tr>
	</table>";
	}
	
	function insertarFila($num, $dato, $total)
	{
		$total[0] = $total[0] + $dato['debe'];
		$total[1] = $total[1] + $dato['haber'];
	  echo " <tr>
		<td class='session3_datos1'>$num</td>
		<td class='session3_datos1_1' align='left' colspan='2'><strong>$dato[cuenta]</strong><br/> $dato[idcuenta]</td>
		<td class='session3_datos1_1' align='left'>".$dato['descripcion']."</td>
		<td class='session3_datos1_1'>".$dato['documento']."</td>
		<td class='session3_datos1_1'>".number_format($dato['debe'],2)."</td>
		<td class='session3_datos1_2'>".number_format($dato['haber'],2)."</td>
	  </tr>";	
	  return $total;
	}
	
	function insertarFilaBasia($num)
	{
	  echo " <tr>
		<td class='session3_datos1'></td>
		<td class='session3_datos1_1' colspan='2' >&nbsp;</td>
		<td class='session3_datos1_1' >&nbsp;</td>
		<td class='session3_datos1_1' >&nbsp;</td>
		<td class='session3_datos1_1' >&nbsp;</td>
		<td class='session3_datos1_2' >&nbsp;</td>
	   </tr>";
	}
	
	function insertarTotal($total)
	{
	  echo "<tr>
		<td class='session3_contornoSuperior'></td>
		<td class='session3_contornoSuperior' colspan='3' align='left'></td>
		<td class='session3_textoTotal2'>Total:</td>
		<td class='session3_subtotal_dato2'>".number_format($total[0],2)."</td>
		<td class='session3_subtotal_dato2'>".number_format($total[1],2)."</td>
	  </tr>
	  <tr>
		<td class='session3_aLiteral'>Son:</td>
		<td colspan='4' class='session3_aLiteral2' align='left'>".NumerosALetras($total[0])."</td>
		<td ></td>
		<td ></td>
	  </tr>  
	  ";	
	}



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="libro.css" type="text/css" />
<title>Reporte de Libro Diario</title>
</head>

<body>


<div class="borde"></div>
<div class="session1_numTransaccion">
 <table width="100%" border="0">
      <tr><td align="center" height="3"></td></tr> 
     <tr><td class="session1_titulo_num"><?php echo "Nº ".$datosGenerales['numero']; ?></td></tr> 
    </table>
</div>
<div class="session1_logotipo"><?php if ($logo == 'true'){ echo "<img src='../$datoGeneral[imagen]' width='200' height='70'/>";} ?></div>
<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1">COMPROBANTE DE <?php echo strtoupper($datosGenerales['tipotransaccion']);?></td></tr> 
    </table>
</div>
<div class="session1_sucursal">
    <table width="100%" border="0">
      <tr><td align="center" class="session1_tituloSucursal"><?php echo strtoupper($datosGenerales['nombrecomercial']); ?></td></tr>
    </table>
</div>

<div class="session2_datosPersonales">
<?php datosGenerales($datosGenerales,$db); ?>
</div>

<div class="session3_datos">
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center"> 
  <tr>
    <td width="5%" class="session3_titulosCabecera2">Nº</td>
    <td  width="25%" class="session3_titulosCabecera" colspan="2">Cuenta </td>
    <td width="40%" class="session3_titulosCabecera">Descripción de la Transacción</td>
    <td width="10%" class="session3_titulosCabecera">Doc.</td>
     <td width="10%" class="session3_titulosCabecera">Debe</td>
    <td width="10%" class="session3_titulosCabecera">Haber</td>
  </tr> 
 <?php   
	while ($dato = mysql_fetch_array($datoDetalle)) {
		$numNota++;
		$totalTransaccion = insertarFila($numNota, $dato, $totalTransaccion);
	} 
	
	insertarTotal($totalTransaccion); 
 ?>  
</table>
</div>

<div class="session4_pieFirma"> 
 <table width="93%" border="0" align="center">
  <tr>
    <td width="100">&nbsp;</td>
    <td width="231" class="session4_bordeFirma"></td>
    <td width="97">&nbsp;</td>
    <td width="193" class="session4_bordeFirma"></td>
    <td width="97">&nbsp;</td>
    <td width="191" class="session4_bordeFirma"></td>
    <td width="319">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold">Elaborado por</td>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold">Vº Bº</td>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold">Contabilidad</td>
    <td>&nbsp;</td>
  </tr>
</table> 
</div>
 
<div class="session4_pie"> 
  <table width="93%" border="0" align="center">
  <tr>
    <td width="120" align="right">Elaborado por:</td>
    <td width="324"><?php echo $datosGenerales['usuario'];?></td>
    <td width="93">&nbsp;</td>
    <td width="189">&nbsp;</td>
    <td width="201">&nbsp;</td>
    <td width="170" >Impreso: <?php echo date("d/m/Y");?></td>
    <td width="130">Hora: <?php echo date("H:i:s");?></td>
  </tr>
  </table>
</div>

</body>
</html>
<?php
	$mpdf=new mPDF('utf-8','Letter'); 
	$content = ob_get_clean();
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit;
?>