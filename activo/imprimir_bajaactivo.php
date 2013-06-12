<?php
	session_start();
	if (!isset($_SESSION['softLogeoadmin'])) {
		   header("Location: ../index.php");	
	}
	ob_start();
	include("../MPDF53/mpdf.php");
	include('../conexion.php');
	$db = new MySQL();
	$codbajaactivo = $_GET['idactivo'];
	//$codbajaactivo = 1;
	$sql = "select b.idbajaactivo,b.fechabaja,left(b.motivobaja,20)as 'motivobaja'
	    ,left(a.nombre,20)as 'nombre',p.cuenta,a.precio,b.cantidad,
	    left(a.ubicacion,20)as 'ubicacion', left(concat(tb.nombre,' ',tb.apellido),25)as 'trabajador',
		left(concat(tu.nombre,' ',tu.apellido),30)as 'usuario',s.nombrecomercial,a.detalle  
		from bajaactivo b,activo a,plandecuenta p ,trabajador tb,usuario u,trabajador tu,sucursal s  
		where b.idactivo=a.idactivo
		and b.referencia=p.codigo 
		and a.idtrabajador=tb.idtrabajador 
		and u.idusuario=b.idusuario 
		and u.idtrabajador=tu.idtrabajador 
		and b.idsucursal=s.idsucursal   
		and b.idbajaactivo=$codbajaactivo;";	 
	$datosGenerales = $db->arrayConsulta($sql);
	$sql = "select * from empresa";
	$empresa = $db->arrayConsulta($sql);
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Anticipo</title>
<link rel="stylesheet" href="bajaactivo.css" type="text/css" />
</head>

<body>
<div class="margen"></div>

 

<div class="session1_numTransaccion">
 <table width="100%" border="0">
      <tr><td align="center" height="3"></td></tr> 
     <tr><td class="session1_titulo_num"><?php echo "Nº ".$datosGenerales['idbajaactivo']; ?></td></tr> 
    </table>
</div>
<div class="session1_logotipo"><?php echo "<img src='../$empresa[imagen]' width='200' height='70'/>"; ?></div>
<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1">BAJA DE ACTIVO FIJO</td></tr> 
     <tr> <td align="center" class="titulosNegros"></td></tr>
    </table>
</div>

<div class="session1_sucursal">
    <table width="100%" border="0">
      <tr><td align="center" class="session1_tituloSucursal"><?php echo strtoupper($datosGenerales['nombrecomercial']); ?></td></tr>
    </table>
</div>
 
 <div class="session2_datos">
 <table width="100%" border="0" align="center">
  <tr>
    <td width="23%" class="session2_subtitulos">Fecha:</td>
    <td width="25%" class="session2_contenido">
	 <?php 
	    $fecha = explode("-",$datosGenerales['fechabaja']);
	?>
	<?php echo $fecha[2];?> de <?php echo $db->mes($fecha[1]);?> de 
    <?php echo $fecha[0];?></td>
    <td width="21%" class="session2_subtitulos">Nombre Activo:</td>
    <td  width="22%" class="session2_contenido"><?php echo $datosGenerales['nombre'];?></td>
    <td width="9%">&nbsp;</td>
  </tr>
  <tr>
    <td class="session2_subtitulos">Responsable:</td>
    <td class="session2_contenido"><?php echo $datosGenerales['trabajador'];?></td>
    <td class="session2_subtitulos">Cuenta Contable:</td>
    <td width="22%" class="session2_contenido"><?php echo $datosGenerales['cuenta'];?></td>
    <td width="9%">&nbsp;</td>
  </tr>
  <tr>
    <td class="session2_subtitulos">Motivo de Baja:</td>
    <td class="session2_contenido"><?php echo $datosGenerales['motivobaja'];?></td>
    <td class="session2_subtitulos">Ubicación:</td>
    <td width="22%" class="session2_contenido"><?php echo $datosGenerales['ubicacion'];?></td>
    <td width="9%">&nbsp;</td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td class="session2_subtitulos1">&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
  <td colspan="5">
        
    
   
    </td>
    </tr>
</table>

<table width="85%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr >
		<td width='51%'  class='session1_cabecera1' style="text-align:left;">&nbsp;&nbsp; Detalle </td>
		<td width='17%' class='session1_cabecera1'>Cantidad</td>
		<td width="15%" class='session1_cabecera1'>P/U</td>
		<td width="17%"  class='session1_cabecera2'>P/Total</td>

	  </tr>
      <tr >
		<td  class='session3_datosF1_1' height="70" style="text-align:left;">&nbsp;<?php echo $datosGenerales['detalle'];?> </td>
		<td  class='session3_datosF1_2'><?php echo number_format($datosGenerales['cantidad'],4);?></td>
		<td  class='session3_datosF1_2'><?php echo number_format($datosGenerales['precio'],4);?></td>
		<td  class='session3_datosF1_3'>
		<?php 
		    $cantidad = $datosGenerales['cantidad'];
			$precio = $datosGenerales['precio'];
			echo  number_format(($cantidad * $precio),4);
		?></td>
	  </tr>
      
   </table>    
      

 </div>


 <div class="session5_firmas"> 
 <table width="100%" border="0" align="center">
  <tr>
    <td width="124">&nbsp;</td>
    <td width="211" class="titulosNegros" align="center">...........................................................</td>
    <td width="39">&nbsp;</td>
    <td width="210" class="titulosNegros" align="center">..........................................................</td>
    <td width="39">&nbsp;</td>
    <td width="171" class="titulosNegros" align="center">.........................................................</td>
    <td width="293">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center" class="titulosNegros2">Contabilidad</td>
    <td>&nbsp;</td>
    <td align="center" class="titulosNegros2">Responsable</td>
    <td>&nbsp;</td>
    <td align="center" class="titulosNegros2">VºBº</td>
    <td>&nbsp;</td>
  </tr>
</table> 
 </div>

<div class="session5_pie">   
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
$mpdf->SetHTMLHeader($header);
$mpdf->WriteHTML($content);
$mpdf->Output();
exit;
?>
