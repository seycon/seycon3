<?php
session_start();
if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: ../index.php");	
}
ob_start();
include("../MPDF53/mpdf.php");
include("../conexion.php");
$db = new MySQL();

/*$anio = 2012;
$sucursal = 2 ;*/
$anio = $_POST['anio'];
$sucursal = $_POST['sucursal'];
$sql = "select imagen,left(nombrecomercial,18)as 'nombrecomercial',nit,left(reprepropietario,20)as 'reprepropietario',cipropietario,numafiliado from empresa;";
$datoGeneral = $db->arrayConsulta($sql);
$sql = "select * from sucursal where idsucursal = $sucursal";
$datoSucursal= $db->arrayConsulta($sql);


function insertarFilaInicial($dato,$totalPlanilla,$num){
  $baseCalculo = ($dato['sept'] + $dato['oct'] + $dato['nov']) / 3;
  $meses = $dato['meses'];
  $dias = $dato['dias'];
  $op1 = ($meses == 0) ? 0 : (($baseCalculo /12) * ($meses));
  $op2 = ($dias == 0) ? 0 : (($baseCalculo /360) * ($dias));
  $liquido = $op1 + $op2;
  $totalPlanilla = $totalPlanilla + $liquido;	
  echo "
  <tr>
    <td width='3%' class='session3_datos2'>$num</td>
    <td width='16%' height='15' class='session3_datos2' align='left'>$dato[nombre]</td>
    <td width='4%'  height='15' class='session3_datos2'>$dato[sexo]</td>
    <td width='10%' height='15' class='session3_datos2'>$dato[cargo]</td>
    <td width='6%' height='15' class='session3_datos2'>$dato[fechaingreso]</td>
    <td width='7%' height='15' class='session3_datos2'>".number_format($dato['sept'],2)."</td>
    <td width='7%' height='15' class='session3_datos2'>".number_format($dato['oct'],2)."</td>
    <td width='6%' height='15' class='session3_datos2'>".number_format($dato['nov'],2)."</td>
    <td width='9%' height='15' class='session3_datos2'>".number_format($baseCalculo,2)."</td>
    <td width='6%' height='15' class='session3_datos2'>$meses</td>
    <td width='6%' height='15' class='session3_datos2'>$dias</td>
    <td width='7%' height='15' class='session3_datos2'>".number_format($liquido,2)."</td>
    <td width='13%' height='15' class='session3_datos1'>&nbsp;</td>
  </tr>";
  return $totalPlanilla;
}


function insertarFila($dato,$totalPlanilla,$num){
  $baseCalculo = ($dato['sept'] + $dato['oct'] + $dato['nov']) / 3;
  $meses = $dato['meses'];
  $dias = $dato['dias'];
  $op1 = ($meses == 0) ? 0 : (($baseCalculo /12) *($meses));
  $op2 = ($dias == 0) ? 0 : (($baseCalculo /360) *($dias));
  $liquido = $op1 + $op2;
  $totalPlanilla = $totalPlanilla + $liquido;	
  echo "
  <tr>
    <td class='session3_datos3'>$num</td>
    <td height='5' class='session3_datos3' align='left'>$dato[nombre]</td>
    <td   class='session3_datos3'>$dato[sexo]</td>
    <td  class='session3_datos3'>$dato[cargo]</td>
    <td   class='session3_datos3'>$dato[fechaingreso]</td>
    <td   class='session3_datos3'>".number_format($dato['sept'],2)."</td>
    <td  class='session3_datos3'>".number_format($dato['oct'],2)."</td>
    <td  class='session3_datos3'>".number_format($dato['nov'],2)."</td>
    <td  class='session3_datos3'>".number_format($baseCalculo,2)."</td>
    <td   class='session3_datos3'>$meses</td>
    <td   class='session3_datos3'>$dias</td>
    <td class='session3_datos3'>".number_format($liquido,2)."</td>
    <td class='session3_datos4'>&nbsp;</td>
  </tr>";
	return $totalPlanilla;
}

function iniciarTabla(){
  echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>";	
}

function cerrarTabla(){
  echo "</table>";	
}

function getMeses($dias){
 return (int) ($dias/30);	
}

function getDias($meses,$dias){
  return ($dias - ($meses * 30));  	
}

function insertarTotal($total){
 echo "
   <table width='100%' border='0' cellpadding='0' cellspacing='0'>
     <tr>
    <td width='19%' height='3' ></td>
    <td width='4%'  ></td>
    <td width='10%' ></td>
    <td width='6%' ></td>
    <td width='7%' ></td>
    <td width='7%' ></td>
    <td width='6%' ></td>
    <td width='9%' ></td>
    <td width='6%' ></td>
    <td width='6%'></td>
    <td width='7%'></td>
    <td width='13%'></td>
  </tr>
  <tr>
    <td width='19%' >&nbsp;</td>
    <td width='4%'  >&nbsp;</td>
    <td width='10%' >&nbsp;</td>
    <td width='6%' >&nbsp;</td>
    <td width='7%' >&nbsp;</td>
    <td width='7%' >&nbsp;</td>
    <td width='6%' >&nbsp;</td>
    <td width='9%' >&nbsp;</td>
    <td width='6%' >&nbsp;</td>
    <td width='6%' class='session3_total2'>TOTAL</td>
    <td width='7%' class='session3_total1'>".number_format($total,2)."</td>
    <td width='13%'>&nbsp;</td>
  </tr>
 </table>  
 ";		
}

$sqlPlanilla ="
select t.idtrabajador,concat(t.nombre,' ',t.apellido)as 'nombre',t.sexo,c.cargo,date_format(t.fechaingreso,'%d/%m/%Y')as 'fechaingreso',
	 (ps.totalganado-ps.totaldescuento)as 'sept',
     (po.totalganado-po.totaldescuento)as 'oct',(pn.totalganado-pn.totaldescuento)as 'nov',pt.meses,pt.dias from
     trabajador t,planilla ps,planilla po,planilla pn,cargo c,periodotrabajo pt
     where t.idtrabajador=ps.idtrabajador
	 and pt.idtrabajador=t.idtrabajador 
	 and pt.gestion=$anio 
	 and t.idcargo=c.idcargo 
     and t.idtrabajador=po.idtrabajador
     and t.idtrabajador=pn.idtrabajador
     and month(ps.fecha)=09
     and month(po.fecha)=10
     and month(pn.fecha)=11  
     and t.idsucursal=$sucursal
     and year(ps.fecha)=$anio
     and year(po.fecha)=$anio
     and year(pn.fecha)=$anio;
";
$totalPlanilla = 0;
$numFila = 0;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planilla de Aguinaldos</title>
<link rel="stylesheet" href="planillas.css" type="text/css" />

</head>

<body>
<?php


 $header = '';



$planilla = $db->consulta($sqlPlanilla);
$totalItem = $db->getnumRow($sqlPlanilla);

while ($numFila < $totalItem ){
?>

<div style=" position : absolute;left:5%; top:20px;"></div>
<div class="session1_datoempresa"><?php echo strtoupper($datoGeneral['nombrecomercial']);  ?></div>
<div class="session1_datoempresa_nit"><?php echo "NIT: $datoGeneral[nit]"; ?></div>
<div class="session1_logotipo"><?php echo "<img src='../$datoGeneral[imagen]' width='200' height='70'/>"; ?></div>
 
<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1">PLANILLA DE AGUINALDO</td></tr>
     <tr><td align="center">CORRESPONDIENTE A LA GESTION <?php echo $anio; ?></td></tr>
     <tr><td align="center">(Expresado en Bolivianos)</td></tr>
     <tr><td align="center">&nbsp;</td></tr>
     <tr><td align="left"><strong>SUCURSAL:</strong>&nbsp;&nbsp;<?php echo $datoSucursal['nombrecomercial'];?></td></tr>
    </table>
 </div>
 <div class="session2_cabecera">
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="session2_tabla">
  <tr>
    <td width="3%" rowspan="2">Nº</td>
    <td width="16%" rowspan="2">Nombre</td>
    <td width="4%" rowspan="2">Sexo</td>
    <td width="10%" rowspan="2">Cargo</td>
    <td width="6%" rowspan="2">Fecha de Ingreso</td>
    <td colspan="3">SUELDO ULTIMOS TRES MESES</td>
    <td width="9%" rowspan="2">Base para el Calculo</td>
    <td colspan="2">TIEMPO TRABAJO</td>
    <td width="7%" rowspan="2">Liquido Pagable</td>
    <td width="13%" rowspan="2">Firma</td>
  </tr>
  <tr>
    <td width="7%">Septiembre</td>
    <td width="7%">Octubre</td>
    <td width="6%">Noviembre</td>
    <td width="6%">Mes</td>
    <td width="6%">Dias</td>
    </tr>
 </table>
</div>

<div class="session3_datos">

<?php

$i = 0;
 while ($dato = mysql_fetch_array($planilla)){
	$i++; 
	$numFila++; 
	if ($i == 1){
	  iniciarTabla();
	  $totalPlanilla = insertarFilaInicial($dato,$totalPlanilla,$numFila);	
	}
	else{ 	
	  $totalPlanilla = insertarFila($dato,$totalPlanilla,$numFila);
	}
	if ($i == 30)
	 break;
	
 }

	cerrarTabla();
	insertarTotal($totalPlanilla); 


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
       <td width="139">Realizado por.</td>
       <td width="174"><?php echo $_SESSION['nombre_usuario'];?></td>
       <td width="85">&nbsp;</td>
       <td width="176">&nbsp;</td>
       <td width="349">&nbsp;</td>
       <td width="123">Impreso: <?php echo date("d/m/Y");?></td>
       <td width="90">Hora: <?php echo date("H:i:s");?></td>
      </tr>
    </table>
 </div>
 
    <?php
	  
	       
	   if ($numFila < $totalItem ){
	
	  echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>";
	  echo "<br>";
      echo "<br>"; 
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