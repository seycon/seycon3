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

$idcuenta = $_GET['idcuenta'];

$sql = "SELECT idporcobrar,c.numerocuenta, DATE_FORMAT( fecha,  '%d/%m/%Y' ) AS  'fecha', tipodeudor, tipocambio, left(s.nombrecomercial,18)as 'nombrecomercial', c.moneda, iddeudor, p.cuenta, glosa, left(concat(t.nombre,' ',t.apellido),40)as 'usuario', ROUND( monto, 2 ) AS  'monto',left(c.documento,20)as 'documento' 
FROM cuentaporcobrar c, sucursal s, plandecuenta p, trabajador t,usuario u   
WHERE c.idsucursal = s.idsucursal 
AND c.idusuario = u.idusuario 
AND u.idtrabajador = t.idtrabajador  
AND p.codigo=c.cuenta  
and p.estado=1 
AND idporcobrar =$idcuenta;";

$datosCuenta = $db->arrayConsulta($sql);

switch($datosCuenta['tipodeudor']){
 case 'cliente':
  $sql = "select left(nombre,40)as 'nombre' from cliente where idcliente=$datosCuenta[iddeudor]";
 break;
 case 'proveedor':
  $sql = "select left(nombre,40)as 'nombre' from proveedor where idproveedor=$datosCuenta[iddeudor]";
 break;
 case 'trabajador':
  $sql = "select left(concat(nombre,' ',apellido),40)as 'nombre' from trabajador where idtrabajador=$datosCuenta[iddeudor]";
 break;	
}

$datoDeudor = $db->arrayConsulta($sql);

$numNota = 0;	
$totalNota = 0;	

function datosGenerales($dato,$deudor){
  echo "<table width='100%' border='0'>
  <tr>
    <td width='24%' class='session2_titulos'>Fecha:</td>
    <td width='50%' class='session2_titulosDatos'>$dato[fecha]</td>
    <td width='2%' class='session2_titulos'>&nbsp;</td>
    <td width='1%' class='session2_titulosDatos'>&nbsp;</td>
    <td width='8%' class='session2_titulos'>Moneda:</td>
    <td width='15%' class='session2_titulosDatos'>$dato[moneda]</td>
  </tr>
  <tr>
    <td class='session2_titulos'>Deudor:</td>
    <td class='session2_titulosDatos'>$deudor</td>
    <td class='session2_titulos'>&nbsp;</td>
    <td class='session2_titulosDatos'>&nbsp;</td>
    <td class='session2_titulos'>Doc.:</td>
    <td class='session2_titulosDatos'>$dato[documento]</td>
  </tr>
  <tr>
    <td class='session2_titulos'>Cuenta Contable:</td>
    <td class='session2_titulosDatos'>$dato[cuenta]</td>
    <td class='session2_titulos'>&nbsp;</td>
    <td class='session2_titulosDatos'>&nbsp;</td>
    <td class='session2_titulos'>T.C.:</td>
    <td class='session2_titulosDatos'>$dato[tipocambio]</td>
  </tr>
  <tr>
    <td class='session2_titulos'>&nbsp;</td>
    <td class='session2_titulosDatos'>&nbsp;</td>
    <td class='session2_titulosDatos'>&nbsp;</td>
    <td class='session2_titulosDatos'>&nbsp;</td>
    <td class='session2_titulos'>&nbsp;</td>
    <td class='session2_titulosDatos'>&nbsp;</td>
  </tr>
</table>";
}

function insertarFila($num,$dato,$total){
  echo " <tr>
    <td class='session3_datos1' height='60'>$num</td>
    <td class='session3_datos1_1' align='left' colspan='2'>$dato</td>
    <td class='session3_datos1_2'>".number_format($total,2)."</td>
  </tr>";	
}

function insertarFilaBasia($num){
  echo " <tr>
    <td class='session3_datos1'>$num</td>
	<td class='session3_datos1_1' colspan='2'>&nbsp;</td>
    <td class='session3_datos1_2' align='left'>&nbsp;</td>
   </tr>";
}

function insertarTotal($total,$moneda){
  echo "<tr>
    <td class='session3_contornoSuperior'>Son:</td>
    <td class='session3_contornoSuperior' align='left'>".NumerosALetras($total,$moneda)."</td>
    <td class='session3_textoTotal2'>Total:</td>
    <td class='session3_subtotal_dato2' align='center'>".number_format($total,2)."</td>
  </tr>";	
}



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="cuentacobrar.css" type="text/css" />
<title>Reporte de Nota Venta Productos</title>
</head>

<body>


<div class="borde"></div>
<div class="session1_numTransaccion">
 <table width="100%" border="0">
      <tr><td align="center" height="3"></td></tr> 
     <tr><td class="session1_titulo_num"><?php echo "Nº ".$datosCuenta['idporcobrar']; ?></td></tr> 
    </table>
</div>
<div class="session1_logotipo"><?php if ($logo == 'true'){ echo "<img src='../$datoGeneral[imagen]' width='200' height='70'/>";} ?></div>
<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1">CUENTA POR COBRAR</td></tr> 
    </table>
</div>
<div class="session1_sucursal">
    <table width="100%" border="0">
      <tr><td align="center" class="session1_tituloSucursal"><?php echo strtoupper($datosCuenta['nombrecomercial']); ?></td></tr>
    </table>
</div>

<div class="session2_datosPersonales">
<?php datosGenerales($datosCuenta,$datoDeudor['nombre']); ?>


</div>

<div class="session3_datos">
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center"> 
  <tr>
    <td width="5%" class="session3_titulosCabecera2">Nº</td>
    <td class="session3_titulosCabecera" colspan="2">Descripción</td>
    <td width="12%" class="session3_titulosCabecera">Total</td>
  </tr>
 
 <?php  

	   $numNota++;	   
	   
	   if ($datosCuenta['moneda'] == "Dolares"){
		  $monto = round(($datosCuenta['monto'] / $datosCuenta['tipocambio']),2);
	   }else{
		  $monto = round($datosCuenta['monto'],2);  
	   }
	   
	   insertarFila($numNota,$datosCuenta['glosa'],$monto);	   
       insertarTotal($monto,$datosCuenta['moneda']); 
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
    <td align="center" style="font-weight:bold">Cliente</td>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold">Contabilidad</td>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold">Caja</td>
    <td>&nbsp;</td>
  </tr>
</table> 
 </div>
 
<div class="session4_pie"> 
  <table width="93%" border="0" align="center">
  <tr>
    <td width="120" align="right">Elaborado por:</td>
    <td width="324"><?php echo $datosCuenta['usuario'];?></td>
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