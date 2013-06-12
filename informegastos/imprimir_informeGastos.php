<?php
	session_start();
	if (!isset($_SESSION['softLogeoadmin'])) {
	    header("Location: ../index.php");	
	}
	ob_start();
	include("../MPDF53/mpdf.php");
	include('../conexion.php');
	include('../aumentaComa.php');
	$db = new MySQL();
	$logo = $_GET['logo'];  
	$idinforme = $_GET['idinforme'];
	$sql = "select idinformegasto,montorendicion,nrodocumentos,fecha,privado,comentario
	,left(concat(t.nombre,' ',t.apellido),25)as 'usuario' 
	from informegasto i,usuario u,trabajador t 
	where idinformegasto=$idinforme and i.idusuario=u.idusuario 
	and u.idtrabajador=t.idtrabajador;";
	$maestro = $db->consulta($sql);
	$maestro = mysql_fetch_array($maestro);
	
	$sql ="select left(concat(t.nombre,' ',t.apellido),25)as 'nombre',c.cargo,i.comentario 
		  from informegasto i,usuario u,trabajador t,cargo c
		  where i.idusuario=u.idusuario and u.idtrabajador=t.idtrabajador and
		  t.idcargo=c.idcargo and i.idinformegasto=$idinforme;";
	$datoUsuario = $db->consulta($sql);
	$datoUsuario = mysql_fetch_array($datoUsuario);
	
	$sql = "select * from empresa ";
	$empresa = $db->consulta($sql);
	$empresa = mysql_fetch_array($empresa);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Informe de Gastos</title>
<link rel="stylesheet" type="text/css" href="informe.css"/>

</head>

<body>

<div class="margenPrincipal"></div>
<div class="session1_numTransaccion">
 <table width="100%" border="0">
      <tr><td align="center" height="3"></td></tr> 
     <tr><td class="session1_titulo_num"><?php echo "Nº ".$idinforme; ?></td></tr> 
    </table>
</div>
<div class="session1_logotipo">
<?php if ($logo == 'true'){ echo "<img src='../$empresa[imagen]' width='200' height='70'/>";} ?></div>
<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1">INFORME DE GASTOS</td></tr> 
    </table>
</div>



<table width="90%" border="0" align="center">
  <tr>
    <td >&nbsp;</td>
    <td colspan="3" >&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulo" style="font-size:12px;"><u>Nombre:</u></td>
    <td colspan="3" style="font-size:13px;"><?php echo $datoUsuario['nombre']?></td>
  </tr>
  <tr>
    <td class="subtitulo" style="font-size:12px;"><u>Cargo:</u></td>
    <td colspan="3" style="font-size:13px;"><?php echo $datoUsuario['cargo']?></td>
  </tr>
  <tr>
    <td width="21%" class="subtitulo" style="font-size:12px;"><u>Monto Rendición Bs</u>:</td>
    <td width="32%" style="font-size:13px;"><?php echo $maestro['montorendicion']?></td>
    <td width="24%" class="subtitulo" style="font-size:12px;">N° Recibo de Rendición:</td>
    <td width="23%" style="font-size:13px;"><?php echo $maestro['nrodocumentos']?></td>
  </tr>
</table>
<br />
<table width="98%"  align="center"  cellspacing="0">
  <tr class="tablainicio">
    <td class="tablainiciotitulo" style="font-size:11px;">N°</td>
    <td width="15%" class="tablainiciotitulo" style="font-size:11px;">Fecha</td>
    <td width="48%" class="tablainiciotitulo" style="font-size:11px;">Detalle</td>
    <td width="16%" class="tablainiciotitulo" style="font-size:11px;">Recibo/Factura</td>
    <td class="tablainiciotitulo" style="border-right:1px solid #000;font-size:11px;">Bs.</td>
  </tr>
  
  <?php 
  $sql = "select fecha,detalle,documento,round(importe,2)as importe from detalleinforme 
  where idinformegasto=$idinforme order by iddetalleinforme asc;";
  $detalle = $db->consulta($sql);
  $i = 0;
  $totalT = 0;
    while ($dato = mysql_fetch_array($detalle)) {
      $i++; 
	  $totalT = $totalT + $dato['importe'];
	  echo "  
	  <tr>
		<td class='tablacontenidoLateral' align='center' style='font-size:11px;'>$i</td>
		<td class='tablacontenido_1' align='center' style='font-size:11px;'>".$db->fechaReporte($dato['fecha'])."</td>
		<td class='tablacontenido_1' style='font-size:11px;'>&nbsp;&nbsp;$dato[detalle]</td>
		<td class='tablacontenido_1' align='center' style='font-size:11px;'>$dato[documento]</td>
		<td class='tablacontenido_1' style='border-right:1px solid #000;font-size:11px;' align='center'>"
		.convertir($dato['importe'])."</td>
	  </tr>  
	  ";
	}
	
	
	for ($j = $i; $j <= 24; $j++) {		
		echo "  
    	  <tr>
	    	<td class='session_detalle_lizq'>&nbsp;</td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		    <td class='session_detalle_lder'>&nbsp;</td>
	      </tr>  
	      ";	
	}	
  ?>
  
  

  <tr>
    <td width="9%" class="session_detalle_top">&nbsp;</td>
    <td class="session_detalle_top">&nbsp;</td>
    <td class="session_detalle_top">&nbsp;</td>
    <td align="right" class="session_detalle_textoTotal">Total:</td>
    <td width="12%" class="tablainiciototal" align='center'><?php echo number_format($totalT,2);?></td>
  </tr>
</table>
<table width="100%" border="0">
  <tr>
    <td style="font-size:11px;"><strong>Comentario:</strong>&nbsp;<?php echo $datoUsuario['comentario'];?></td>
  </tr>
</table>


<br />
<br />
<br />
<br />
<table width="90%" border="0" align="center" cellspacing="0">
  <tr>
    <td width="60%" >&nbsp;</td>
    <td width="25%" class="punteadas" style="font-size:11px;">Encargado de Gastos</td>
    <td width="15%" class="punteadas" style="border-left:1px solid #000;border-left-style:dotted;font-size:11px;"> Contabilidad</td>
  </tr>
</table>

<div class="sessionDatosFinales"> 
<table width="100%" border="0" align="center">
  <tr>
    <td width="6%">&nbsp;</td>
    <td width="46%" class="contenidoDatos"><?php echo $empresa['direccion']?></td>
    <td width="23%" class="contenidoDatos"><?php echo $empresa['telefono']?></td>
    <td width="25%" class="contenidoDatos"><?php echo $empresa['website']?></td>
  </tr>
</table>
</div>

<div class="sessionFinal_Ciudad">
<table width="100%" border="0">
  <tr><td class="letra_sessionFinal" align="center"><?php echo $empresa['ciudad'];?> - Bolivia</td></tr>
</table>
</div>


<div class="session_pie"> 
<table width="93%" border="0" align="center">
<tr>
  <td width="120" align="right">Elaborado por:</td>
  <td width="324"><?php echo $maestro['usuario'];?></td>
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