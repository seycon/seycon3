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

//$idtransaccion = 1;
$idtransaccion = $_GET['idegreso'];
$sql = "select idegreso as 'numero',cheque,nombrepersona,date_format(fecha,'%d/%m/%Y')as 'fecha',recibo,left(p.cuenta,25)as 'cuenta',
left(s.nombrecomercial,25)as 'nombrecomercial',i.idpersona,i.tipopersona,left(concat(t.nombre,' ',t.apellido),25)as 'usuario',i.tipocambio,
i.glosa     
from egreso i,sucursal s,plandecuenta p,usuario u,trabajador t
 where i.idsucursal=s.idsucursal 
 and i.cuenta=p.codigo 
 and u.idusuario=i.idusuario 
 and u.idtrabajador=t.idtrabajador 
 and idegreso=$idtransaccion;";

$datosGenerales = $db->arrayConsulta($sql);

switch($datosGenerales['tipopersona']){
 case 'cliente':
  $sql = "select left(nombre,40)as 'nombre' from cliente where idcliente=$datosGenerales[idpersona]";
 break;
 case 'proveedor':
  $sql = "select left(nombre,40)as 'nombre' from proveedor where idproveedor=$datosGenerales[idpersona]";
 break;
 case 'trabajador':
  $sql = "select left(concat(nombre,' ',apellido),40)as 'nombre' from trabajador where idtrabajador=$datosGenerales[idpersona]";
 break;	
}

$datoPersona = $db->arrayConsulta($sql);

$sql = "select left(p.cuenta,25)as 'cuenta',left(d.descripcion,55)as 'descripcion',d.montobolivianos,d.montodolares 
 from detalleegreso d,plandecuenta p where d.idcuenta=p.codigo and p.estado=1 and idegreso=$idtransaccion;";
$datoDetalle = $db->consulta($sql);

$cantDetalle = $db->getnumRow($sql);
 if ($cantDetalle<=10){
   $claseBorde = "borde2";
   $clasePie = "session4_pie2";
   $claseSubPie = "session3_subPie2";
  }else{
   $claseBorde = "borde";   
   $clasePie = "session4_pie";
   $claseSubPie = "session3_subPie";
  }

$numNota = 0;	
$totalTransaccion = array();	

function datosGenerales($dato,$persona){
	
	if ($dato['tipopersona'] == "otros"){
		$persona = $dato['nombrepersona'];
	}
	else{
		$persona = $persona;
	}
	
  echo "<table width='100%' border='0'>
  <tr>
    <td width='14%' class='session2_titulos'>Cuenta:</td>
    <td width='35%' class='session2_titulosDatos'>$dato[cuenta]</td>
    <td width='2%' class='session2_titulos'>&nbsp;</td>
    <td width='16%' class='session2_titulos'>Fecha:</td>
    <td width='15%' class='session2_titulosDatos'>$dato[fecha]</td>
    <td width='18%' class='session2_titulos'></td>
  </tr>
  <tr>
    <td class='session2_titulos'>".ucfirst(($dato['tipopersona'])).":</td>
    <td class='session2_titulosDatos'>$persona</td>
    <td class='session2_titulos'>&nbsp;</td>
    <td class='session2_titulos'>Doc.:</td>
    <td class='session2_titulosDatos'>$dato[recibo]</td>
    <td class='session2_titulos'></td>
  </tr>
  <tr>
    <td class='session2_titulos'>T.C.:</td>
    <td class='session2_titulosDatos'>$dato[tipocambio]</td>
    <td class='session2_titulos'>&nbsp;</td>
    <td class='session2_titulos'>Cheque:</td>
    <td class='session2_titulosDatos'>$dato[cheque]</td>
    <td class='session2_titulos'>&nbsp;</td>
  </tr>
</table>";
}

function insertarFila($num,$dato,$total,$tc){
	$total[0] = $total[0] + $dato['montobolivianos'];
	$tipoCambio = round(($dato['montodolares'] / $tc),4);
	$total[1] = $total[1] + $tipoCambio;
  echo " <tr>
    <td class='session3_datos1'>$num</td>
    <td class='session3_datos1_1' align='left' colspan='2'>$dato[cuenta]</td>
    <td class='session3_datos1_1' align='left'>".$dato['descripcion']."</td>
	<td class='session3_datos1_1'>".number_format($dato['montobolivianos'],2)."</td>
	<td class='session3_datos1_2'>".number_format($tipoCambio,2)."</td>
  </tr>";	
  return $total;
}

function insertarFilaBasia($num){
  echo " <tr>
    <td class='session3_datos1'></td>
	<td class='session3_datos1_1' colspan='2' >&nbsp;</td>
	<td class='session3_datos1_1' >&nbsp;</td>
	<td class='session3_datos1_1' >&nbsp;</td>
    <td class='session3_datos1_2' >&nbsp;</td>
   </tr>";
}

function insertarTotal($total,$tc){
	
	$totalG = $total[0] + round(($total[1] * $tc),2);
  echo "<tr>
    <td class='session3_contornoSuperior'></td>
    <td class='session3_contornoSuperior' colspan='2' align='left'></td>
    <td class='session3_textoTotal2'>Total:</td>
	<td class='session3_subtotal_dato2'>".number_format($total[0],2)."</td>
    <td class='session3_subtotal_dato2'>".number_format($total[1],2)."</td>
  </tr>
  <tr>
    <td class='session3_aLiteral'>Son:</td>
    <td colspan='4' class='session3_aLiteral' align='left'>".NumerosALetras($totalG)."</td>
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
<link rel="stylesheet" href="egresos.css" type="text/css" />
<title>Reporte de Nota Venta Productos</title>
</head>

<body>


<div class="<?php echo $claseBorde; ?>"></div>
<div class="session1_numTransaccion">
 <table width="100%" border="0">
      <tr><td align="center" height="3"></td></tr> 
     <tr><td class="session1_titulo_num"><?php echo "Nº ".$datosGenerales['numero']; ?></td></tr> 
    </table>
</div>
<div class="session1_logotipo"><?php 
if ($logo == 'true'){ echo "<img src='../$datoGeneral[imagen]' width='200' height='70'/>"; }?></div>
<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1">EGRESO DE DINERO</td></tr> 
    </table>
</div>
<div class="session1_sucursal">
    <table width="100%" border="0">
      <tr><td align="center" class="session1_tituloSucursal"><?php echo strtoupper($datosGenerales['nombrecomercial']); ?></td></tr>
    </table>
</div>

<div class="session2_datosPersonales">
<?php datosGenerales($datosGenerales,$datoPersona['nombre']); ?>


</div>

<div class="session3_datos">
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center"> 
  <tr>
    <td width="5%" class="session3_titulosCabecera2">Nº</td>
    <td  width="20%" class="session3_titulosCabecera" colspan="2">Cuenta Contable</td>
    <td width="55%" class="session3_titulosCabecera">Descripción</td>
    <td width="10%" class="session3_titulosCabecera">Bs.</td>
    <td width="10%" class="session3_titulosCabecera">$us.</td>
  </tr>
 
 <?php
   
      while ($dato = mysql_fetch_array($datoDetalle)){
	    $numNota++;
	    $totalTransaccion = insertarFila($numNota,$dato,$totalTransaccion,$datosGenerales['tipocambio']);
	  }
	
     insertarTotal($totalTransaccion,$datosGenerales['tipocambio']);
 
 ?>  
</table>
<table width="100%" border="0" align="center">
  <tr>
    <td colspan="2" class="textoGlosa" align="left"><?php echo "<span class='session2_titulos'>Glosa:</span>
	<span class='glosa'>".$datosGenerales['glosa']."</span>";?></td>
  </tr>
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
    <td align="center" style="font-weight:bold">Entregue Conforme</td>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold">Vº Bº</td>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold">Contabilidad</td>
    <td>&nbsp;</td>
  </tr>
</table> 
 </div>
 
<div class="<?php echo $clasePie;?>"> 
  <table width="93%" border="0" align="center">
  <tr>
    <td width="120" align="right">Elaborado por:</td>
    <td width="324"><?php echo $datosGenerales['usuario'];?></td>
    <td width="93">&nbsp;</td>
    <td width="189">&nbsp;</td>
    <td width="201">&nbsp;</td>
    <td width="170"><?php //echo "Impreso: ".date("d/m/Y");?></td>
    <td width="130"><?php //echo "Hora: ".date("H:i:s");?></td>
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