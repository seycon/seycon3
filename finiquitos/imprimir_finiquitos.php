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

$idtrabajador = $_GET['trabajador'];
$sql = "select left(nombrecomercial,30)as 'nombrecomercial',rubro from empresa;";
$empresa = $db->arrayConsulta($sql);

$sql = "
select f.idfiniquitos,left(concat(t.nombre,' ',t.apellido),30)as 'trabajador',t.estadocivil,
CASE
   WHEN (MONTH(t.fechanacimiento) < MONTH(current_date)) THEN YEAR(current_date) - YEAR(t.fechanacimiento)
   WHEN ((MONTH(t.fechanacimiento) = MONTH(current_date)) AND (DAY(t.fechanacimiento) <= DAY(current_date))) 
   THEN YEAR(current_date) - YEAR(t.fechanacimiento)
   ELSE (YEAR(current_date) - YEAR(t.fechanacimiento))-1
END AS 'edad',t.direccion,t.profesion,t.carnetidentidad,date_format(t.fechaingreso,'%d/%m/%Y')as 'fechaingreso',
date_format(f.fecha,'%d/%m/%Y')as 'fecharetiro',f.motivo,t.sueldobasico,f.mesesvacaciones,f.diasvacaciones,f.mesesprima,
f.diasprima,f.descripcionotros,f.totalotros,
(SELECT TIMESTAMPDIFF(YEAR,t.fechaingreso,f.fecha))  AS 'anios',
(SELECT (TIMESTAMPDIFF(MONTH,t.fechaingreso,f.fecha)) - (TIMESTAMPDIFF(YEAR,t.fechaingreso,f.fecha) * 12)) AS 'meses',
(SELECT DATEDIFF(f.fecha,DATE_ADD(DATE_ADD(t.fechaingreso, INTERVAL TIMESTAMPDIFF(YEAR,t.fechaingreso,f.fecha) YEAR), 
INTERVAL (TIMESTAMPDIFF(MONTH,t.fechaingreso,f.fecha)) - (TIMESTAMPDIFF(YEAR,t.fechaingreso,f.fecha) * 12) MONTH))) AS 'dias',
(SELECT (TIMESTAMPDIFF(MONTH,CONCAT(YEAR(current_date),'-01-01'),current_date)) - (TIMESTAMPDIFF(YEAR,CONCAT(YEAR(current_date),'-01-01'),current_date) * 12)) AS 'mesesAguinaldo', 
(SELECT DATEDIFF(current_date,DATE_ADD(DATE_ADD(CONCAT(YEAR(current_date),'-01-01'), INTERVAL TIMESTAMPDIFF(YEAR,CONCAT(YEAR(current_date),'-01-01'),current_date) YEAR), 
INTERVAL (TIMESTAMPDIFF(MONTH,CONCAT(YEAR(current_date),'-01-01'),current_date)) - (TIMESTAMPDIFF(YEAR,CONCAT(YEAR(current_date),'-01-01'),current_date) * 12) MONTH))) AS 'diasAguinaldo'  
from finiquitos f,trabajador t 
where t.idtrabajador=f.idtrabajador
and t.idtrabajador=$idtrabajador;";
$datoI = $db->arrayConsulta($sql);


$sql = "select descripcion,monto from descuentofiniquitos where idfiniquitos=$datoI[idfiniquitos];";
$datoDescuentos = $db->consulta($sql);


$sql = "select max(year(fecha))as 'anio' from planilla where idtrabajador=$idtrabajador;";
$anio = $db->arrayConsulta($sql);
$anio = $anio['anio'];
$sql = "select max(month(fecha))as 'mes' from planilla where idtrabajador=$idtrabajador and year(fecha)=$anio;";
$mes1 = $db->arrayConsulta($sql);
$mes1 = $mes1['mes'];
$sql = "select (totalganado-totaldescuento)as 'total' from planilla where idtrabajador=1 and month(fecha)=$mes1 and year(fecha)=$anio;";
$liquido3 = $db->arrayConsulta($sql);
$mes2 = $mes1 - 1 ;
$anio2 = $anio;
 if ($mes2 == 0) {
	$anio2--;
	$mes2 = 12; 
 }
$sql = "select (totalganado-totaldescuento)as 'total' from planilla where idtrabajador=1 and month(fecha)=$mes2 and year(fecha)=$anio2;";
$liquido2 = $db->arrayConsulta($sql);
 $mes3 = $mes2 - 1;
 $anio3 = $anio2; 
  if ($mes3 == 0) {
	$anio3--;
	$mes3 = 12; 
  }
$sql = "select (totalganado-totaldescuento)as 'total' from planilla where idtrabajador=1 and month(fecha)=$mes3 and year(fecha)=$anio3;";
$liquido1 = $db->arrayConsulta($sql); 
$totalGeneral = $liquido1['total'] + $liquido2['total'] + $liquido3['total'];

$baseCalculo = $totalGeneral/3;

function datosSeccionI($empresa, $dato) {

echo "<table width='95%' border='0' cellpadding='0' cellspacing='0' align='center'>
    <tr>
    <td width='4%' height='5px'></td>
    <td width='9%'></td>
    <td width='7%'></td>
    <td width='6%'></td>
    <td width='6%'></td>
    <td width='7%'></td>
    <td width='7%'></td>
    <td width='3%'></td>
    <td width='10%'></td>
    <td width='6%'></td>
    <td width='6%'></td>
    <td width='12%'></td>
    <td width='8%'></td>
    <td width='4%'></td>
    <td width='5%'></td>
  </tr>
  <tr>
    <td colspan='6' class='sesion1_titulo_t1'>RAZON SOCIAL O NOMBRE DE LA EMPRESA</td>
    <td colspan='7' class='sesion1_titulo_t2_datos'>".strtoupper($empresa['nombrecomercial'])."</td>
    <td class='sesion1_titulo_t2_datos'></td>
    <td class='sesion1_titulo_t3_datos'></td>
  </tr>
  <tr>
    <td colspan='5' class='session1_titulo_t10'>RAMA DE ACTIVIDAD ECONOMICA</td>
    <td colspan='2' class='session1_titulo_t11_datos'>$empresa[rubro]</td>
    <td colspan='2' class='session1_titulo_t11'></td>
    <td colspan='6' class='session1_titulo_t12_datos'></td>
    </tr>
  <tr>
    <td colspan='6' class='sesion1_titulo_t1'>NOMBRE DEL TRABAJADOR</td>
    <td colspan='7' class='sesion1_titulo_t2_datos'>".strtoupper($dato['trabajador'])."</td>
    <td class='sesion1_titulo_t2_datos'>&nbsp;</td>
    <td class='sesion1_titulo_t3_datos'>&nbsp;</td>
  </tr>
  <tr>
    <td colspan='2' class='session1_titulo_t10'>ESTADO CIVIL</td>
    <td colspan='4' class='session1_titulo_t11_datos'>$dato[estadocivil]</td>
    <td class='session1_titulo_t11'>EDAD</td>
    <td class='session1_titulo_t11_datos'>$dato[edad]</td>
    <td class='session1_titulo_t11_datos'>Años</td>
    <td colspan='2' class='session1_titulo_t11'>DOMICILIO</td>
    <td colspan='4' class='session1_titulo_t12_datos'>$dato[direccion]</td>
    </tr>
  <tr>
    <td colspan='4' class='session1_titulo_t7'>PROFESION U OCUPACION</td>
    <td colspan='9' class='session1_titulo_t8'>$dato[profesion]</td>
    <td class='session1_titulo_t8'>&nbsp;</td>
    <td class='session1_titulo_t9'>&nbsp;</td>
  </tr>
  <tr>
    <td class='session1_titulo_t10'>CI</td>
    <td colspan='3' class='session1_titulo_t11_datos'>$dato[carnetidentidad]</td>
    <td colspan='3' class='session1_titulo_t11'>FECHA DE INGRESO</td>
    <td colspan='2' class='session1_titulo_t11_datos'>$dato[fechaingreso]</td>
    <td colspan='3' class='session1_titulo_t11'>FECHA DE RETIRO</td>
    <td colspan='3' class='session1_titulo_t12_datos'>$dato[fecharetiro]</td>
    </tr>
  <tr>
    <td colspan='3' class='sesion1_titulo_t1'>MOTIVO DE RETIRO</td>
    <td colspan='4' class='sesion1_titulo_t2_datos'>$dato[motivo]</td>
    <td colspan='5' class='sesion1_titulo_t2'>REMUNERACION MENSUAL</td>
    <td colspan='2' class='sesion1_titulo_t2_datos'>".number_format($dato['sueldobasico'],2)."</td>
    <td class='sesion1_titulo_t3_datos'>&nbsp;</td>
  </tr>
  <tr>
    <td colspan='3' class='session1_titulo_t4'>TIEMPO DE SERVICIO</td>
    <td colspan='2' class='session1_titulo_t5_datos'>$dato[anios]</td>
    <td class='session1_titulo_t5'>AÑOS</td>
    <td class='session1_titulo_t5_datos'>$dato[meses]</td>
    <td colspan='2' class='session1_titulo_t5'>MESES</td>
    <td class='session1_titulo_t5_datos'>$dato[dias]</td>
    <td colspan='2' class='session1_titulo_t5'>DIAS</td>
    <td colspan='3' class='session1_titulo_t6'>&nbsp;</td>
    </tr>
      <tr>
    <td height='5px'></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>";
	
}



function datosSeccionII($mes1,$anio1,$total1,$mes2,$anio2,$total2,$mes3,$anio3,$total3,$db,$total){
echo "<table width='95%' border='0' cellpadding='0' cellspacing='0' align='center'>
    <tr>
    <td width='28%' height='5px'></td>
    <td width='4%'></td>
    <td width='15%'></td>
    <td width='4%'></td>
    <td width='15%'></td>
    <td width='4%'></td>
    <td width='14%'></td>
    <td width='4%'></td>
    <td width='12%'></td>
  </tr>
  
  <tr>
    <td class='session2_titulo_t1'>A) MESES</td>
    <td colspan='2' class='session2_titulo_t2'>".$db->mes($mes1)."/$anio1"."</td>
    <td colspan='2' class='session2_titulo_t2'>".$db->mes($mes2)."/$anio2"."</td>
    <td colspan='2' class='session2_titulo_t2'>".$db->mes($mes3)."/$anio3"."</td>
    <td colspan='2' class='session2_titulo_t3'>TOTALES</td>
    </tr>
  <tr>
    <td class='session2_titulo_t7'>REMUNERACION MENSUAL</td>
    <td class='session2_titulo_t10'>Bs</td>
    <td class='session2_titulo_t8' align='center'>".number_format($total1,2)."</td>
    <td class='session2_titulo_t10'>Bs</td>
    <td class='session2_titulo_t8' align='center'>".number_format($total2,2)."</td>
    <td class='session2_titulo_t10'>Bs</td>
    <td class='session2_titulo_t8' align='center'>".number_format($total3,2)."</td>
    <td class='session2_titulo_t10'>Bs</td>
    <td class='session2_titulo_t9' align='center'>".number_format($total,2)."</td>
  </tr>
  <tr>
    <td class='session2_titulo_t7'>B) OTROS CONCEPTOS PERCIBIDOS EN EL MES</td>
    <td class='session2_titulo_t8'>&nbsp;</td>
    <td class='session2_titulo_t8'>&nbsp;</td>
    <td class='session2_titulo_t8'>&nbsp;</td>
    <td class='session2_titulo_t8'>&nbsp;</td>
    <td class='session2_titulo_t8'>&nbsp;</td>
    <td class='session2_titulo_t8'>&nbsp;</td>
    <td class='session2_titulo_t8'>&nbsp;</td>
    <td class='session2_titulo_t9'>&nbsp;</td>
  </tr>
  <tr>
    <td class='session2_titulo_t7'>&nbsp;</td>
    <td class='session2_titulo_t8'>Bs</td>
    <td class='session2_titulo_t8'>&nbsp;</td>
    <td class='session2_titulo_t8'>Bs</td>
    <td class='session2_titulo_t8'>&nbsp;</td>
    <td class='session2_titulo_t8'>Bs</td>
    <td class='session2_titulo_t8'>&nbsp;</td>
    <td class='session2_titulo_t8'>Bs</td>
    <td class='session2_titulo_t9'>&nbsp;</td>
  </tr>
  <tr>
    <td class='session2_titulo_t7'>&nbsp;</td>
    <td class='session2_titulo_t8'>Bs</td>
    <td class='session2_titulo_t8'>&nbsp;</td>
    <td class='session2_titulo_t8'>Bs</td>
    <td class='session2_titulo_t8'>&nbsp;</td>
    <td class='session2_titulo_t8'>Bs</td>
    <td class='session2_titulo_t8'>&nbsp;</td>
    <td class='session2_titulo_t8'>Bs</td>
    <td class='session2_titulo_t9'>&nbsp;</td>
  </tr>
  <tr>
    <td class='session2_titulo_t7'>&nbsp;</td>
    <td class='session2_titulo_t8'>Bs</td>
    <td class='session2_titulo_t8'>&nbsp;</td>
    <td class='session2_titulo_t8'>Bs</td>
    <td class='session2_titulo_t8'>&nbsp;</td>
    <td class='session2_titulo_t8'>Bs</td>
    <td class='session2_titulo_t8'>&nbsp;</td>
    <td class='session2_titulo_t8'>Bs</td>
    <td class='session2_titulo_t9'>&nbsp;</td>
  </tr>
  <tr>
    <td class='session2_titulo_t7'>&nbsp;</td>
    <td class='session2_titulo_t8'>Bs</td>
    <td class='session2_titulo_t8'>&nbsp;</td>
    <td class='session2_titulo_t8'>Bs</td>
    <td class='session2_titulo_t8'>&nbsp;</td>
    <td class='session2_titulo_t8'>Bs</td>
    <td class='session2_titulo_t8'>&nbsp;</td>
    <td class='session2_titulo_t8'>Bs</td>
    <td class='session2_titulo_t9'>&nbsp;</td>
  </tr>
  <tr>
    <td class='session2_titulo_t4'>TOTAL</td>
    <td class='session2_titulo_t5'>&nbsp;</td>
    <td class='session2_titulo_t5'>&nbsp;</td>
    <td class='session2_titulo_t5'>&nbsp;</td>
    <td class='session2_titulo_t5'>&nbsp;</td>
    <td class='session2_titulo_t5'>&nbsp;</td>
    <td class='session2_titulo_t5'>&nbsp;</td>
    <td class='session2_titulo_t5'>&nbsp;</td>
    <td class='session2_titulo_t6'>".number_format($total,2)."</td>
  </tr>
    <tr>
    <td height='5px'></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>";	
}

function datosSessionIII($datosI,$base,$total){
	if ($datosI['motivo']!="Despido")
	$total = 0;
	
	$indAnios = $base * $datosI['anios'];
	$indMes =  ($datosI['meses']*$base)/12;
	$indDias = ($datosI['dias']*$base)/360;
	$totalVacaciones = (($datosI['mesesvacaciones']*$base)/12) + (($datosI['diasvacaciones']*$base)/360); 
	$totalprima = (($datosI['mesesprima']*$base)/12) + (($datosI['diasprima']*$base)/360); 
    $totalAguinaldo = (($datosI['mesesAguinaldo']*$base)/12) + (($datosI['diasAguinaldo']*$base)/360); 
	
	$totalIV = $total + $indAnios + $indMes + $indDias + $totalVacaciones + $totalprima + $totalAguinaldo + $datosI['totalotros']; 
	
echo "<table width='95%' border='0' cellpadding='0' cellspacing='0' align='center'>
  <tr>
    <td width='9%' height='5px'></td>
    <td width='9%'></td>
    <td width='10%'></td>
    <td width='9%'></td>
    <td width='6%'></td>
    <td width='10%'></td>
    <td width='8%'></td>
    <td width='5%'></td>
    <td width='4%'></td>
    <td width='7%'></td>
    <td width='8%'></td>
    <td width='4%'></td>
    <td width='11%'></td>
  </tr>
  <tr>
    <td colspan='11' class='session3_titulo_t1'>C) DESAUHUCIO TRES MESES (EN CASO DE RETIRO FORZOSO)</td>
    <td class='session3_titulo_t2'>Bs</td>
    <td class='session3_titulo_t2'>".number_format($total,2)."</td>
  </tr>
  <tr>
    <td colspan='4' class='session3_titulo_t3'>D)INDEMNIZACIÓN POR TIEMPO DE TRABAJO</td>
    <td class='session3_titulo_t6'>DE</td>
    <td class='session3_titulo_t5' align='center'>$datosI[anios]</td>
    <td colspan='2' class='session3_titulo_t6'>AÑOS</td>
    <td class='session3_titulo_t6'>Bs</td>
    <td colspan='2' class='session3_titulo_t5' align='center'>".number_format($indAnios,2)."</td>
    <td class='session3_titulo_t5'>Bs</td>
    <td class='session3_titulo_t5'>".number_format($indAnios,2)."</td>
  </tr>
  <tr>
    <td colspan='4' class='session3_titulo_t4'>&nbsp;</td>
    <td class='session3_titulo_t6'>DE</td>
    <td class='session3_titulo_t5' align='center'>$datosI[meses]</td>
    <td colspan='2' class='session3_titulo_t6'>MESES</td>
    <td class='session3_titulo_t6'>Bs</td>
    <td colspan='2' class='session3_titulo_t5' align='center'>".number_format($indMes,2)."</td>
    <td class='session3_titulo_t5'>&nbsp;</td>
    <td class='session3_titulo_t5'>".number_format($indMes,2)."</td>
  </tr>
  <tr>
    <td colspan='4' class='session3_titulo_t4'>&nbsp;</td>
    <td class='session3_titulo_t6'>DE</td>
    <td class='session3_titulo_t5' align='center'>$datosI[dias]</td>
    <td colspan='2' class='session3_titulo_t6'>DIAS</td>
    <td class='session3_titulo_t6'>Bs</td>
    <td colspan='2' class='session3_titulo_t5' align='center'>".number_format($indDias,2)."</td>
    <td class='session3_titulo_t5'>&nbsp;</td>
    <td class='session3_titulo_t5'>".number_format($indDias,2)."</td>
  </tr>
  <tr>
    <td colspan='4' class='session3_titulo_t3'>AGUINALDO DE NAVIDAD</td>
    <td class='session3_titulo_t6'>DE</td>
    <td class='session3_titulo_t5' align='center'>$datosI[mesesAguinaldo]</td>
    <td colspan='2' class='session3_titulo_t6'>MESES Y</td>
    <td colspan='2' class='session3_titulo_t5' align='center'>$datosI[diasAguinaldo]</td>
    <td class='session3_titulo_t5'>DIAS</td>
    <td class='session3_titulo_t5'>Bs</td>
    <td class='session3_titulo_t5'>".number_format($totalAguinaldo,2)."</td>
  </tr>
  <tr>
    <td colspan='4' class='session3_titulo_t3'>VACACIÓN</td>
    <td class='session3_titulo_t6'>DE</td>
    <td class='session3_titulo_t5' align='center'>$datosI[mesesvacaciones]</td>
    <td colspan='2' class='session3_titulo_t6'>MESES Y</td>
    <td colspan='2' class='session3_titulo_t5' align='center'>$datosI[diasvacaciones]</td>
    <td class='session3_titulo_t5'>DIAS</td>
    <td class='session3_titulo_t5'>Bs</td>
    <td class='session3_titulo_t5'>".number_format($totalVacaciones,2)."</td>
  </tr>
  <tr>
    <td colspan='4' class='session3_titulo_t3'>PRIMA LEGAL(SI CORRESPONDE)</td>
    <td class='session3_titulo_t6'>DE</td>
    <td class='session3_titulo_t5' align='center'>$datosI[mesesprima]</td>
    <td colspan='2' class='session3_titulo_t6'>MESES Y</td>
    <td colspan='2' class='session3_titulo_t5' align='center'>$datosI[diasprima]</td>
    <td class='session3_titulo_t5'>DIAS</td>
    <td class='session3_titulo_t5'>Bs</td>
    <td class='session3_titulo_t5'>".number_format($totalprima,2)."</td>
  </tr>
  <tr>
    <td class='session3_titulo_t3'>OTROS</td>
    <td colspan='10' class='session3_titulo_t5'>$datosI[descripcionotros]</td>
    <td class='session3_titulo_t5'>Bs</td>
    <td class='session3_titulo_t5'>".number_format($datosI['totalotros'],2)."</td>
  </tr>
  <tr>
    <td colspan='2' class='session3_titulo_t4'>&nbsp;</td>
    <td class='session3_titulo_t6'>GESTION</td>
    <td colspan='4' class='session3_titulo_t5'>&nbsp;</td>
    <td class='session3_titulo_t6'>DE</td>
    <td colspan='2' class='session3_titulo_t5'>&nbsp;</td>
    <td class='session3_titulo_t6'>DIAS</td>
    <td class='session3_titulo_t5'>Bs</td>
    <td class='session3_titulo_t5'>&nbsp;</td>
  </tr>
  <tr>
    <td height='5px'></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>

    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>";	
return $totalIV;
}


function datosSessionFinal($dato,$total){
echo "<table width='95%' border='0' cellpadding='0' cellspacing='0' align='center'>
  <tr>
    <td colspan='2'>&nbsp;</td>
    <td width='1%'>&nbsp;</td>
    <td width='16%'>&nbsp;</td>
    <td width='1%'>&nbsp;</td>
    <td width='53%'>&nbsp;</td>
  </tr>
    <tr>
    <td width='6%' class='session7_titulo_t1'>YO:</td>
    <td colspan='5' class='session7_titulo_t3'>$dato[trabajador]</td>
    </tr>
  <tr>
    <td colspan='2' class='session7_titulo_t1'>MAYOR DE EDAD, CON C.I. Nº</td>
    <td>&nbsp;</td>
    <td class='session8_titulo_t1_2' align='center'>$dato[carnetidentidad]</td>
    <td>&nbsp;</td>
    <td class='session7_titulo_t1'>DECLARO QUE EN FECHA RECIBO A MI ENTERA</td>
  </tr>
  <tr>
    <td colspan='2' class='session7_titulo_t1'>SATISFACCIÓN, EL IMPORTE DE</td>
    <td>&nbsp;</td>
    <td class='session8_titulo_t1' align='center'>Bs. ".number_format($total,2)."</td>
    <td>&nbsp;</td>
    <td class='session7_titulo_t1'>POR CONCEPTO DE LA LIQUIDACION DE MIS BENE-</td>
  </tr>
  <tr>
    <td colspan='6' class='session7_titulo_t1'>FICIOS SOCIALES, DE CONFORMIDAD CON LA LEY GENERAL DEL TRABAJO, SU DECRETO REGLAMEN-</td>
    </tr>
  <tr>
    <td colspan='6' class='session7_titulo_t1'>TARIO Y DISPOSICIONES CONEXAS.</td>
    </tr>
  <tr>
    <td colspan='2'>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

<table width='95%' border='0' cellpadding='0' cellspacing='0' align='center'>
  <tr>
    <td width='16%'>&nbsp;</td>
    <td width='1%'>&nbsp;</td>
    <td width='22%'>&nbsp;</td>
    <td width='3%'>&nbsp;</td>
    <td width='5%'>&nbsp;</td>
    <td width='4%'>&nbsp;</td>
    <td colspan='2'>&nbsp;</td>
    <td width='23%'>&nbsp;</td>
  </tr>
  <tr>
    <td class='session7_titulo_t1'>LUGAR Y FECHA:</td>
    <td>&nbsp;</td>
    <td class='session8_titulo2'>SANTA CRUZ</td>
    <td>,</td>
    <td class='session8_titulo2'>&nbsp;</td>
    <td class='session8_titulo3'>DE</td>
    <td width='18%' class='session8_titulo2'>&nbsp;</td>
    <td width='8%' class='session8_titulo3'>DE</td>
    <td class='session8_titulo2'>&nbsp;</td>
  </tr>
</table>

<table width='95%' border='0' cellspacing='0' cellpadding='0' align='center'>
  <tr>
    <td width='12%'>&nbsp;</td>
    <td width='25%'>&nbsp;</td>
    <td width='14%'>&nbsp;</td>
    <td width='29%'>&nbsp;</td>
    <td width='20%'>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class='session8_titulo2'>&nbsp;</td>
    <td>&nbsp;</td>
    <td class='session8_titulo2'>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class='session8_titulo3'>INTERESADO</td>
    <td>&nbsp;</td>
    <td class='session8_titulo3'>FRANCISCO XAVIER SAENZ CABEZAS</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class='session8_titulo2'>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class='session8_titulo3'>Vo. Bo. MINISTERIO DE TRABAJO</td>
    <td>&nbsp;</td>
    <td class='session8_titulo3'>SELLO</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>";	
}

function seccionDatosV($consulta){
echo "<table width='95%' border='0' cellpadding='0' cellspacing='0' align='center'>
   <tr>
    <td width='19%' height='5px'></td>
    <td width='23%'></td>
    <td width='7%'></td>
    <td width='21%'></td>
    <td width='13%'></td>
    <td width='17%'></td>
  </tr> 
  <tr>
    <td class='session5_tabla_t1'>E) EDUCACIONES:</td>
    <td class='session5_tabla_t2'>&nbsp;</td>
    <td class='session5_tabla_t2'>Bs</td>
    <td class='session5_tabla_t2'>&nbsp;</td>
    <td class='session5_tabla_t2'>&nbsp;</td>
    <td class='session5_tabla_t2'>&nbsp;</td>
  </tr>";
  $i = 0;
  $total = 0;
  while($dato = mysql_fetch_array($consulta)){
  $i++;
  $total = $total + $dato['monto'];
  echo "<tr>
    <td class='session5_tabla_t3'>&nbsp;</td>
    <td class='session5_tabla_t4'>$dato[descripcion]</td>
    <td class='session5_tabla_t4'>Bs</td>
    <td class='session5_tabla_t4' align='center'>".number_format($dato['monto'],2)."</td>
    <td class='session5_tabla_t4'>&nbsp;</td>
    <td class='session5_tabla_t4' align='center'>".number_format($dato['monto'],2)."</td>
  </tr>";
  }
  for ($j=$i;$j<=3;$j++){
  echo "<tr>
    <td class='session5_tabla_t3'>&nbsp;</td>
    <td class='session5_tabla_t4'>&nbsp;</td>
    <td class='session5_tabla_t4'>Bs</td>
    <td class='session5_tabla_t4'>&nbsp;</td>
    <td class='session5_tabla_t4'>&nbsp;</td>
    <td class='session5_tabla_t4'>&nbsp;</td>
  </tr>";
  }

echo "
  <tr>
    <td class='session5_tabla_t3'>&nbsp;</td>
    <td class='session5_tabla_t4'>&nbsp;</td>
    <td class='session5_tabla_t4'>Bs</td>
    <td class='session5_tabla_t4'>&nbsp;</td>
    <td class='session5_tabla_t4'>TOTAL. Bs</td>
    <td class='session5_tabla_t4'>".number_format($total,2)."</td>
  </tr>
    <tr>
    <td height='5px'></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>";	
return $total;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Imprimir Finiquitos</title>
<link rel="stylesheet" href="finiquitos.css" />
</head>

<body>

<?php
for ($i=1; $i<=2; $i++){
?>
<div style=" position : absolute;left:5%; top:20px;"></div>

<?php
if($i == 1){
?>

<div class="session1_contenedorTitulos">
<br />
<br />
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1">FINIQUITO</td></tr> 
    </table>
    
</div>
<div class="session1_imagen1"><img src="../images/escudo.gif" width="100" height="70"/></div>
<div class="session1_imagen2"></div>
<div class="session1_subtitulo_1"> REPÚBLICA DE BOLIVIA</div>
<div class="session1_subtitulo_2"> MINISTERIO DE TRABAJO</div>
<div class="session1_subtitulo_3"> DIRECCIÓN GENERAL DE</div>
<div class="session1_subtitulo_4"> TRABAJO</div>
<div class="session1_datosGenerales">
<table width="100%" border="0">
  <tr><td class="subtitulos">I.- DATOS GENERALES</td>
  </tr>
</table>
</div>

<div class="session1_datos">
<?php
datosSeccionI($empresa,$datoI);
?>
</div>

<div class="session2_datosGenerales">
<table width="100%" border="0">
  <tr><td class="subtitulos">II.- LIQUIDACION DE LA REMUNERACIÓN PROMEDIO INDEMNIZABLE EN BASE A LOS ÚLTIMOS 3 MESES</td>
  </tr>
</table>
</div>

<div class="session2_datos">
<?php
datosSeccionII($mes3,$anio3,$liquido1['total'],$mes2,$anio2,$liquido2['total'],$mes1,$anio,$liquido3['total'],$db,$totalGeneral);
?>
</div>

<div class="session3_datosGenerales">
<table width="100%" border="0">
  <tr><td width="82%" class="subtitulos">III.- TOTAL REMUNERACIÓN PROMEDIO INDEMNIZABLE (A+B) DIVIDIDO ENTRE 3</td>
  <td width="4%" class="session3_bordeLateral">Bs</td>
  <td width="14%"><?php echo number_format($baseCalculo,2) ?></td>
  </tr>
</table>
</div>

<div class="session3_datos">
<?php
$totalIV = datosSessionIII($datoI,$baseCalculo,$totalGeneral);
?>
</div>

<div class="session4_datosGenerales">
<table width="100%" border="0">
  <tr><td width="82%" class="subtitulos">IV.- TOTAL BENEFICIOS SOCIALES: C + D</td>
  <td width="4%" class="session4_bordeLateral">Bs</td>
  <td width="14%"><?php echo number_format($totalIV,2);?></td>
  </tr>
</table>
</div>

<?php
}else{
?>


<div class="session5_datosGenerales">
<?php
$totalV = seccionDatosV($datoDescuentos);
$totalFiniquito = $baseCalculo + $totalIV  - $totalV;
?>
</div>

<div class="session6_datosGenerales">
<table width="100%" border="0">
  <tr><td width="82%" class="subtitulos">V.- IMPORTE LIQUIDO A PAGAR C + D - E =</td>
  <td width="4%" class="session4_bordeLateral">Bs</td>
  <td width="14%"><?php echo number_format($totalFiniquito,2);?></td>
  </tr>
</table>
</div>

<div class="session7_datosGenerales">
<table width="95%" border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td width="43%" height="5px"></td>
    <td width="3%"></td>
    <td width="19%"></td>
    <td width="9%"></td>
    <td width="9%"></td>
    <td width="17%"></td>
  </tr>
  <tr>
    <td width="43%" class="session7_titulo_t1">FORMA DE PAGO: EFECTIVO ( X ) CHEQUE ( X ) Nº</td>
    <td width="3%">&nbsp;</td>
    <td width="19%" class="session7_titulo_t3">&nbsp;</td>
    <td width="9%" class="session7_titulo_t3">C/BANCO</td>
    <td width="9%">&nbsp;</td>
    <td width="17%" class="session7_titulo_t3">&nbsp;</td>
  </tr>
  <tr>
    <td class="session7_titulo_t2">IMPORTE DE LA SUMA CANCELADA:</td>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" class="session7_titulo_t3">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" height="10px"></td>
    <td></td>
  </tr>
</table>

</div>

<div class="session8_datosGenerales">
<?php
datosSessionFinal($datoI,$totalFiniquito);
?>
</div>
<?php
}
if ($i == 1){
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
$mpdf=new mPDF('utf-8','Letter'); 
$content = ob_get_clean();
$mpdf->WriteHTML($content);
$mpdf->Output();
exit;
?>