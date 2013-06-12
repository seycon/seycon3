<?php
session_start();
if (!isset($_SESSION['softLogeoadmin'])){
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

$idnota = $_GET['idnotaventa'];

$sql = "
select left(c.nombrenit,15)as 'nombrenit',left(c.nombre,15)as 'nombre',n.numfactura,c.direccionoficina,c.telefono,c.nit,s.nombrecomercial,
left(r.nombre,20)as 'vendedor',n.tipoprecio,n.diascredito,n.moneda,
n.tipocambio,date_format(n.fechacredito,'%d/%m/%Y')as 'fechacredito',n.descuento,n.recargo 
,left(n.glosa,80)as 'glosa',date_format(n.fecha,'%d/%m/%Y')as 'fecha',left(concat(tu.nombre,' ',tu.apellido),40)as 'usuario'  
from notaventa n,cliente c,sucursal s,ruta r,trabajador tu,
usuario u  
where n.idcliente=c.idcliente
and n.idsucursal=s.idsucursal
and r.idruta=c.ruta 
and u.idusuario=n.idusuario
and u.idtrabajador=tu.idtrabajador
and n.idnotaventa=$idnota;";
$notaventa = $db->arrayConsulta($sql);

$sql = "select p.idproducto,p.nombre,round(ds.precio,2)as 'precio',date_format(ds.fechavencimiento,'%d/%m/%Y') 
	    as 'fecha',ds.cantidad,round(ds.total,2)as 'total',ds.lote,ds.unidadmedida from detallenotaventa ds,producto p,notaventa s
        where ds.idnotaventa=s.idnotaventa and ds.idproducto=p.idproducto and s.idnotaventa=$idnota;";
$detalleNota = $db->consulta($sql);

$numeroDetalle = $db->getnumRow($sql);

 if ($numeroDetalle<=12){
   $claseBorde = "borde2";
   $clasePie = "session4_pie2";
   $claseSubPie = "session3_subPie2";
  }else{
   $claseBorde = "borde";   
   $clasePie = "session4_pie";
   $claseSubPie = "session3_subPie";
  }

$numNota = 0;	
$totalNota = 0;	

function datosGenerales($dato){
  echo "<table width='100%' border='0'>
  <tr>
    <td width='12%' class='session2_titulos'>Cliente:</td>
    <td width='17%' class='session2_titulosDatos'>$dato[nombre]</td>
    <td width='14%' class='session2_titulos'>Ruta:</td>
    <td width='24%' class='session2_titulosDatos'>$dato[vendedor]</td>
    <td width='20%' class='session2_titulos'>Fecha:</td>
    <td width='13%' class='session2_titulosDatos'>$dato[fecha]</td>
  </tr>
  <tr>
    <td class='session2_titulos'>NIT:</td>
    <td class='session2_titulosDatos'>$dato[nit]</td>
    <td class='session2_titulos'>Nombre Nit:</td>
    <td class='session2_titulosDatos'>$dato[nombrenit]</td>
    <td class='session2_titulos'>Días Cred.:</td>
    <td class='session2_titulosDatos'>$dato[diascredito] - $dato[fechacredito]</td>
  </tr>
  <tr>
    <td class='session2_titulos'>Dirección:</td>
    <td class='session2_titulosDatos'>$dato[direccionoficina]</td>
    <td class='session2_titulos'>Sucursal:</td>
    <td class='session2_titulosDatos'>$dato[nombrecomercial]</td>
    <td class='session2_titulos'>Moneda:</td>
    <td class='session2_titulosDatos'>$dato[moneda]</td>
  </tr>
  <tr>
    <td class='session2_titulos'>Teléfono:</td>
    <td class='session2_titulosDatos'>$dato[telefono]</td>
    <td class='session2_titulos'>Factura:</td>
    <td class='session2_titulosDatos'>$dato[numfactura]</td>
    <td class='session2_titulos'>T.C.:</td>
    <td class='session2_titulosDatos'>$dato[tipocambio]</td>
  </tr>
 
</table>";
}

function insertarFila($num,$dato,$total,$moneda,$tc){
	
	$aux = 1;	
  if ($moneda == "Dolares"){
	 $aux = $tc; 
  }
  $monto = round(($dato['total']/$aux),4);
  $precio = round(($dato['precio']/$aux),4);
	
 $total = $total + $monto;	
  echo " <tr>
    <td class='session3_datos1'>$num</td>
	<td class='session3_datos1_1'>$dato[lote]</td>
    <td class='session3_datos1_1' align='left'>$dato[nombre]</td>
	<td class='session3_datos1_1'>$dato[fecha]</td>
    <td class='session3_datos1_1'>$dato[cantidad] - $dato[unidadmedida]</td>
    <td class='session3_datos1_1'>".number_format($precio,4)."</td>
    <td class='session3_datos1_2'>".number_format($monto ,4)."</td>
  </tr>";	
  return $total;
}

function insertarFilaBasia($num){
	 echo " <tr>
    <td class='session3_datos1'>$num</td>
	<td class='session3_datos1_1'>&nbsp;</td>
    <td class='session3_datos1_1' align='left'>&nbsp;</td>
	<td class='session3_datos1_1'>&nbsp;</td>
    <td class='session3_datos1_1'>&nbsp;</td>
    <td class='session3_datos1_1'>&nbsp;</td>
    <td class='session3_datos1_2'>&nbsp;</td>
  </tr>";
}

function insertarTotal($total,$descuento,$recargo,$glosa,$moneda){
  $liquido = $total - (($descuento/100)*$total) + (($recargo/100)*$total);	
  echo "<tr>
    <td class='session3_contornoSuperior'>Son:</td>
    <td class='session3_contornoSuperior' colspan='4' align='left'>".NumerosALetras(round($liquido,2),$moneda)."</td>
    <td class='session3_textoTotal1'>Sub Total:</td>
    <td class='session3_subtotal' align='center'>".number_format($total,2)."</td>
  </tr>
  <tr>
    <td colspan='5' class='session3_glosa'><strong>Glosa: </strong>$glosa</td>
    <td class='session3_textoTotal2'>Descuento:</td>
    <td class='session3_subtotal_dato' align='center'>".number_format((($descuento/100)*$total),2)."</td>
  </tr>
  <tr>
    <td colspan='5' ></td>
    <td class='session3_textoTotal2'>Recargo:</td>
    <td class='session3_subtotal_dato' align='center'>".number_format((($recargo/100)*$total),2)."</td>
  </tr>  
  <tr>
    <td colspan='5'></td>
    <td class='session3_textoTotal2'>TOTAL:</td>
    <td class='session3_subtotal_dato2' align='center'>".number_format($liquido,2)."</td>
  </tr>";	
}



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="notaventa.css" type="text/css" />
<title>Reporte de Nota Venta Productos</title>
</head>

<body>


<div class="<?php echo $claseBorde; ?>"></div>
<div class="session1_numTransaccion">
 <table width="100%" border="0">
      <tr><td align="center" height="3"></td></tr> 
     <tr><td class="session1_titulo_num"><?php echo "Nº ".$idnota; ?></td></tr> 
    </table>
</div>
<div class="session1_logotipo"><?php if ($logo == 'true'){ echo "<img src='../$datoGeneral[imagen]' width='200' height='70'/>";} ?></div>
<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1">NOTA DE VENTA</td></tr> 
    </table>
</div>

<div class="session2_datosPersonales">
<?php datosGenerales($notaventa); ?>
</div>

<div class="session3_datos">
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center"> 
  <tr>
    <td width="5%" class="session3_titulosCabecera2">Nº</td>
    <td width="15%" class="session3_titulosCabecera">Lote</td>
    <td width="33%" class="session3_titulosCabecera">Descripción</td>
    <td width="12%" class="session3_titulosCabecera">Fecha Venc.</td>
    <td width="12%" class="session3_titulosCabecera">Cantidad</td>
    <td width="11%" class="session3_titulosCabecera">P/U</td>
    <td width="12%" class="session3_titulosCabecera">Total</td>
  </tr>
 
 <?php     
   
   while($dato = mysql_fetch_array($detalleNota)){
	   $numNota++;
	   $totalNota = insertarFila($numNota,$dato,$totalNota,$notaventa['moneda'],$notaventa['tipocambio']);	   
   }
      
   insertarTotal($totalNota,$notaventa['descuento'],$notaventa['recargo'],$notaventa['glosa'],$notaventa['moneda']); 
 ?>  
</table>



</div>


<div class="<?php echo $claseSubPie; ?>"> 
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
    <td align="center" style="font-weight:bold">Entreguado Conforme</td>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold">Recibí Conforme</td>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold">Contabilidad</td>
    <td>&nbsp;</td>
  </tr>
</table> 
 </div>
 
<div class="<?php echo $clasePie; ?>"> 
  <table width="93%" border="0" align="center">
  <tr>
    <td width="120" align="right">Elaborado por:</td>
    <td width="324"><?php echo $notaventa['usuario'];?></td>
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