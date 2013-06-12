<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
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
$sql = "select imagen,left(nombrecomercial,25)as 'nombrecomercial',nit from empresa;";
$datoGeneral = $db->arrayConsulta($sql);




function getEspacios($nivel){
  $cadena = "";	
  $nivel = ($nivel>3) ? ($nivel-2)*3 : $nivel;
  for ($i=1;$i<=$nivel;$i++){
	 $cadena = $cadena."&nbsp;"; 
  }
  return $cadena;	
}

 function getVacio(){
 return "<tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
	<td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>";	
}

 function getNivelCuenta($nivel,$codigo,$cuenta,$moneda){
	$espacio = getEspacios($nivel); 
	return "
	<tr>
	<td class='datosplan2'>$codigo</td>
    <td class='cuentaNivel3'>$espacio $cuenta</td>
	<td class='datosplan1'>$moneda</td>
    </tr>";	 
 }
 
 function getNivelDato($nivel,$codigo,$cuenta,$moneda){
	$espacio = getEspacios($nivel); 
	return "
	<tr>
	<td class='datosplan2'>$codigo</td>
    <td class='cuentaNivel5'>$espacio $cuenta</td>
	<td class='datosplan1'>$moneda</td>
    </tr>";	 
 }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="plandecuentas.css" type="text/css" />
<title>Reporte de Plan de Cuentas</title>
</head>

<body>

<?php
 $totalIngreso = 0;
 $sql = "select codigo,cuenta,nivel,moneda from plandecuenta where estado=1 order by codigo;";  
 $cuentas = $db->consulta($sql);
 $num = 0;
 $totalItem = $db->getnumRow($sql);
 while ($num < $totalItem ){
?>

<div style=" position : absolute;left:5%; top:20px;"></div>
<div class="borde"></div>
<div class="session1_numTransaccion">
    <table width="100%" border="0">
     <tr><td align="right"><?php echo $datoGeneral['nombrecomercial']; ?></td></tr> 
     <tr><td align="right"><?php echo "Nit: ".$datoGeneral['nit']; ?></td></tr>
     <tr><td align="right"><?php echo "Santa Cruz-Bolivia"; ?></td></tr>
    </table>
</div>
<div class="session1_logotipo"><?php if ($logo == 'true'){ echo  "<img src='../$datoGeneral[imagen]' width='200' height='70'/>";} ?></div>
<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1">PLAN DE CUENTAS</td></tr> 
    </table>
</div>
<div class="session3_datos">
<table width="90%" border="0" align="center">
 <tr>
 <td width="20%" align="center" class="session1_titulo1_1">Codigo</td>
 <td width="63%" align="center" class="session1_titulo1_1">Cuenta</td>
 <td width="17%" align="center" class="session1_titulo1_1">Moneda</td>
 </tr> 

<?php
$cantidad = 0;
  while ($cuenta = mysql_fetch_array($cuentas)){
	if ($cuenta['nivel'] >= 5) {
	  echo getNivelDato($cuenta['nivel'],$cuenta['codigo'],$cuenta['cuenta'],$cuenta['moneda']);	
	}else{
	  echo getNivelCuenta($cuenta['nivel'],$cuenta['codigo'],$cuenta['cuenta'],$cuenta['moneda']);
	}
	$cantidad++;
	if ($cantidad == 43)
	break;
	
  }
  $num = $num + $cantidad;   
?>  
</table>

</div>
 
  <div class="session4_pie"> 
  <table width="93%" border="0" align="center">
  <tr>
    <td width="120" align="right"></td>
    <td width="324"></td>
    <td width="93">&nbsp;</td>
    <td width="189">&nbsp;</td>
    <td width="201">&nbsp;</td>
    <td width="170" >Impreso: <?php echo date("d/m/Y");?></td>
    <td width="130">Hora: <?php echo date("H:i:s");?></td>
  </tr>
  </table>
 </div> 

<?php
 if ($num < $totalItem){
	 for ($m=1;$m<55;$m++){
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