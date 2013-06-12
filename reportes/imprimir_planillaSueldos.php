<?php
session_start();
if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: ../index.php");	
}
ob_start();
include("../MPDF53/mpdf.php");
include("../conexion.php");
$db = new MySQL();


$sql = "select imagen,left(nombrecomercial,25)as 'nombrecomercial',nit from empresa;";
$logo = $db->arrayConsulta($sql);

$mesPlanilla = 1;
$anioPlanilla = 2010;
$sucursalPlanilla = 2 ;
//$mesPlanilla = $_GET['meses'];
//$anioPlanilla = $_GET['anio'];
//$sucursalPlanilla = $_GET['sucursal']; 


$consulta = "select t.carnetidentidad,t.nombre,t.nacionalidad,t.fechanacimiento,(CASE t.sexo WHEN t.sexo='Masculino' THEN 'M' ELSE 'F' END)as sexo,
             c.cargo,t.fechaingreso, p.diastrabajados,t.horaspordia, ((t.sueldobasico/30)*p.diastrabajados)as 'HaberBasico'  ,t.bonodeantiguedad,
			 p.horasextras,(t.costoporhoraextra*p.horasextras)as 'Importe' ,p.bonodesempeno, p.otrosbonos,
			 (((t.sueldobasico/30)*p.diastrabajados)+(t.bonodeantiguedad)+(t.costoporhoraextra*p.horasextras)+(p.bonodesempeno)+(p.otrosbonos))as 'TotalGanado',
			 ROUND((dp.afp*t.sueldobasico),2)as 'AFP',IF(t.sueldobasico>=(dp.salariominimo*4),ROUND((dp.rciva*t.sueldobasico),2),0) as 'RCIVA',p.anticipo,
			 ROUND((dp.afp*t.sueldobasico+IF(t.sueldobasico>=(dp.salariominimo*4),ROUND((dp.rciva*t.sueldobasico),2),0)+p.anticipo),2)as 'TotalGanados',
			 ROUND((((t.sueldobasico/30)*p.diastrabajados)+(t.bonodeantiguedad)+(t.costoporhoraextra*p.horasextras)+(p.bonodesempeno)+(p.otrosbonos))
			 - (dp.afp*t.sueldobasico+IF(t.sueldobasico>=(dp.salariominimo*4),ROUND((dp.rciva*t.sueldobasico),2),0) +p.anticipo),2)as 'LiquidoPagable',																														             t.seccion  from trabajador t,cargo c,planilla p,datosplanilla dp where c.idcargo=t.idcargo  and p.iddatosplanilla=dp.iddatosplanilla
			 and p.idtrabajador=t.idtrabajador  and month(p.fecha)=".$mesPlanilla." and year(p.fecha)=".$anioPlanilla." 
			  and p.idsucursal=$sucursalPlanilla group by t.seccion,t.nombre,t.nacionalidad,t.fechanacimiento,t.sexo,
			  c.cargo,t.fechaingreso,t.sueldobasico,t.bonodeantiguedad,t.otrosbonos;";
			 
			 

$consultaSubtotal = "select sum((t.sueldobasico/30)*p.diastrabajados)as 'HaberBasico'  ,sum(t.bonodeantiguedad)as 'bonodeantiguedad', 
sum(p.horasextras)as 'horasextras',sum(t.costoporhoraextra*p.horasextras)as 'Importe' ,sum(p.bonodesempeno)as 'bonodesempeno', sum(p.otrosbonos)as 'otrosbonos',
sum(((t.sueldobasico/30)*p.diastrabajados)+(t.bonodeantiguedad)+(t.costoporhoraextra*p.horasextras)+(p.bonodesempeno)+(p.otrosbonos))as 'TotalGanado',
sum(ROUND((dp.afp*t.sueldobasico),2))as 'AFP',sum(IF(t.sueldobasico>=(dp.salariominimo*4),ROUND((dp.rciva*t.sueldobasico),2),0))as 'RCIVA',sum(p.anticipo)as 'anticipo',
sum(ROUND((dp.afp*t.sueldobasico+IF(t.sueldobasico>=(dp.salariominimo*4),ROUND((dp.rciva*t.sueldobasico),2),0)+p.anticipo),2))as 'TotalGanados',
sum(ROUND((((t.sueldobasico/30)*p.diastrabajados)+(t.bonodeantiguedad)+(t.costoporhoraextra*p.horasextras)+(p.bonodesempeno)+(p.otrosbonos))
- (dp.afp*t.sueldobasico+IF(t.sueldobasico>=(dp.salariominimo*4),ROUND((dp.rciva*t.sueldobasico),2),0)+p.anticipo),2))as 'LiquidoPagable',
t.seccion  from trabajador t,planilla p,datosplanilla dp where p.iddatosplanilla=dp.iddatosplanilla 
and p.idtrabajador=t.idtrabajador and month(p.fecha)=".$mesPlanilla." and year(p.fecha)=".$anioPlanilla." and p.idsucursal=$sucursalPlanilla and " ;


$consultaTotales="select sum((t.sueldobasico/30)*p.diastrabajados)as 'HaberBasico'  ,sum(t.bonodeantiguedad)as 'bonodeantiguedad', 
sum(p.horasextras)as 'horasextras',sum(t.costoporhoraextra*p.horasextras)as 'Importe' ,sum(p.bonodesempeno)as 'bonodesempeno', sum(p.otrosbonos)as 'otrosbonos',
sum(((t.sueldobasico/30)*p.diastrabajados)+(t.bonodeantiguedad)+(t.costoporhoraextra*p.horasextras)+(p.bonodesempeno)+(p.otrosbonos))as 'TotalGanado',
sum(ROUND((dp.afp*t.sueldobasico),2))as 'AFP',sum(IF(t.sueldobasico>=(dp.salariominimo*4),ROUND((dp.rciva*t.sueldobasico),2),0))as 'RCIVA',sum(p.anticipo)as 'anticipo',
sum(ROUND((dp.afp*t.sueldobasico+dp.rciva*t.sueldobasico+p.anticipo),2))as 'TotalGanados',
sum(ROUND((((t.sueldobasico/30)*p.diastrabajados)+(t.bonodeantiguedad)+(t.costoporhoraextra*p.horasextras)+(p.bonodesempeno)+(p.otrosbonos))
- (dp.afp*t.sueldobasico+IF(t.sueldobasico>=(dp.salariominimo*4),ROUND((dp.rciva*t.sueldobasico),2),0)+p.anticipo),2))as 'LiquidoPagable',
t.seccion  from trabajador t,planilla p,datosplanilla dp where p.iddatosplanilla=dp.iddatosplanilla  
and p.idtrabajador=t.idtrabajador and year(p.fecha)=".$anioPlanilla."  and month(p.fecha)=".$mesPlanilla." and p.idsucursal=$sucursalPlanilla  group by month(p.fecha)";

$pregunta = mysql_query($consulta);			 

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="estilos/estilo_planillasueldos.css" type="text/css"  />

<title>Sistema Empresarial y Contable – Seycon 2011</title>
<style type="text/css">
<!--
.nuevo {
	font-weight: bold;
}

.celda{
border-left:none;
border-right:none;

font-family:Arial, Helvetica, sans-serif;


}

.celda1{
border-right:none;
font-family:Arial, Helvetica, sans-serif;
}
-->
</style>

</head>

<body>









   <div class="session1_datoempresa">
   <?php
   echo "<h3>".strtoupper($logo['nombrecomercial'])."</h3>";  
   ?>
   </div>    
   <div class="session1_datoempresa_nit">
   <?php
   echo "<h3>$logo[nit]</h3>";
   ?>
   </div> 
   <div class="session1_logotipo">
   <?php
   echo "<img src='../$logo[imagen]' width='200' height='70'/>"; 
   ?>
   </div>
<div style="position:relative; margin:0 auto; width:1000px;text-align:center;">
       <h1>PLANILLA DE SUELDOS Y SALARIOS</h1>
       <H3>CORRESPONDIENTE AL MES DE ABRIL DE 2011</H3>
       <H3> (EXPRESADA EN BOLIVIANOS)</H3>
  </div>


<table width="100%" border="0">
  <tr height="15" bgcolor="#EAEAEA">
    <td width="2%" height="52" class="nuevo">Nº</td>
    <td width="5%"  class="nuevo">C.I.</td>
    <td width="8%" class="nuevo">Nombre</td>
    <td width="5%" class="nuevo">Nacion.</td>
    <td width="6%" class="titulos">Fecha de Nacim.</td>
    <td width="3%" class="nuevo">Sexo</td>
    <td width="4%" class="nuevo">Cargo</td>
    <td width="5%" class="titulos">Fecha de Ingreso</td>
    <td width="5%" class="titulos">Dias Pag/Mes</td>
    <td width="4%" class="titulos">Haber Básico</td>
    <td width="5%" class="titulos">Bono de Antigu.</td>
    <td width="8%" class="nuevo"><table width="100%" border="0">
      <tr>
        <td colspan="2" class="session1_cabecera_horas">Horas Extra</td>
        </tr>
      <tr>
        <td width="50%" class="titulos">Num</td>
        <td width="50%" class="nuevo">Importe</td>
      </tr>
    </table></td>
    <td width="7%"><table width="97%" border="0">
      <tr>
        <td colspan="2" class="session1_cabecera_horas">Bonos</td>
        </tr>
      <tr>
        <td class="nuevo">Prod.</td>
        <td class="nuevo">Otros</td>
      </tr>
    </table></td>
    <td width="4%" class="titulos">Total Ganado</td>
    <td width="11%" class="nuevo"><table width="100%" border="0">
      <tr>
        <td colspan="3" class="session1_cabecera_horas">Descuento</td>
      </tr>
      <tr>
        <td width="24%" class="nuevo">A.F.P</td>
        <td width="39%" class="nuevo">RC-IVA</td>
        <td width="37%" class="nuevo">Anticipos</td>
      </tr>
    </table></td>
    <td width="4%" class="titulos">Total Desc.</td>
    <td width="5%" class="titulos">Líquido Pagable</td>
    <td width="9%" class="titulos">Firma</td>
  </tr>
</table>

<table width="100%" border="1" cellspacing="0" style="font-size:10px">

<?php
$numero = 0;
$seccion = "";
while($row = mysql_fetch_array($pregunta)){
$numero = $numero +1;
$secc = $row['seccion'];
  if ($secc!=$seccion){
	  
	  if ($seccion!=""){
		  $sql = $consultaSubtotal." t.seccion='$seccion' group by t.seccion ; ";
		  $resultado = mysql_query($sql);
		  $total = mysql_fetch_array($resultado);
		  
					 
				echo "<tr height='15' bgcolor='#EAEAEA'>";
				echo "<td width='1' colspan='9' height='52' class='session2_titulos'>SUBTOTALES SECCIÓN:</td>";
				echo "<td width='3%' class='celda'>$total[HaberBasico]</td>";
				echo "<td width='5%' class='celda'>$total[bonodeantiguedad]</td>";
				echo "<td width='3%' class='celda'>$total[horasextras]</td>";
				echo "<td width='4%' class='celda'>$total[Importe]</td>";
				echo "<td width='3%' class='celda'>$total[bonodesempeno]</td>";
 				echo "<td width='3%' class='celda'>$total[otrosbonos]</td>";
				echo "<td width='4%' class='celda'>$total[TotalGanado]</td>";
				echo "<td width='3%' class='celda'>$total[AFP]</td>";
				echo "<td width='4%' class='celda'>$total[RCIVA]</td>";
				echo "<td width='5%' class='celda'>$total[anticipo]</td>";
				echo "<td width='5%' class='celda'>$total[TotalGanados]</td>";
				echo "<td width='6%' class='celda'>$total[LiquidoPagable]</td>";
				echo "<td width='10%' class='celda'></td>";
			    echo "</tr>";

		  
		  
	  }
	  
	  $seccion = $secc;
	  echo "<tr height='15'>";
      echo "<td height='1' bgcolor='#EAEAEA' colspan='22' class='negrita'>Sección: ".strtoupper($seccion)."</td>";	  
      echo "</tr>";
  }

   echo "<tr height='15'>";
   echo "<td width='2%' class='celda1'>$numero</td>";
   echo " <td width='5%' class='celda'>$row[carnetidentidad]</td>";
   echo " <td width='6%' class='celda'>$row[nombre]</td>";
   echo "<td width='6%' class='celda'>$row[nacionalidad]</td>";
   echo " <td width='6%' class='celda'><p align='center'>$row[fechanacimiento]</p></td>";
   echo " <td width='2%' class='celda'>$row[sexo]</td>";
   echo " <td width='3%' class='celda'>$row[cargo]</td>";
   echo " <td width='5%' class='celda'>$row[fechaingreso]</td>";
   echo " <td width='4%' class='celda'>$row[diastrabajados]</td>";
   echo " <td width='3%' class='celda'>$row[HaberBasico]</td>";
   echo " <td width='5%' class='celda'>$row[bonodeantiguedad]</td>";
   echo " <td width='3%' class='celda'>$row[horasextras]</td>";
   echo " <td width='4%' class='celda'> $row[Importe]</td>";
   echo " <td width='3%' class='celda'>$row[bonodesempeno]</td>";
   echo "  <td width='3%' class='celda'>$row[otrosbonos]</td>";
   echo " <td width='4%' class='celda'>$row[TotalGanado]</td>";
   echo " <td width='4%' class='celda'>$row[AFP]</td>";
   echo " <td width='4%' class='celda'>$row[RCIVA]</td>";
   echo " <td width='4%' class='celda'>$row[anticipo]</td>";
   echo " <td width='5%' class='celda'>$row[TotalGanados]</td>";
   echo " <td width='6%' class='celda'>$row[LiquidoPagable]</td>";
   echo " <td width='10%' class='celda'></td>";
  echo "</tr>";
  
  }
  
  if ($seccion!=""){
		  
		  $resultado = mysql_query($consultaSubtotal." t.seccion='$seccion' group by t.seccion ; ");
		  $total = mysql_fetch_array($resultado);
		  
					 
				echo "<tr height='15' bgcolor='#EAEAEA'>";
				echo "<td width='1' colspan='9' height='52' class='session2_titulos'>SUBTOTALES SECCIÓN:</td>";
				echo "<td width='3%' class='celda'>$total[HaberBasico]</td>";
				echo "<td width='5%' class='celda'>$total[bonodeantiguedad]</td>";
				echo "<td width='3%' class='celda'>$total[horasextras]</td>";
				echo "<td width='4%' class='celda'>$total[Importe]</td>";
				echo "<td width='3%' class='celda'>$total[bonodesempeno]</td>";
 				echo "<td width='3%' class='celda'>$total[otrosbonos]</td>";
				echo "<td width='4%' class='celda'>$total[TotalGanado]</td>";
				echo "<td width='3%' class='celda'>$total[AFP]</td>";
				echo "<td width='4%' class='celda'>$total[RCIVA]</td>";
				echo "<td width='5%' class='celda'>$total[anticipo]</td>";
				echo "<td width='5%' class='celda'>$total[TotalGanados]</td>";
				echo "<td width='6%' class='celda'>$total[LiquidoPagable]</td>";
				echo "<td width='10%' class='celda'></td>";
			    echo "</tr>";
				
		$resultado = mysql_query($consultaTotales);
		$total = mysql_fetch_array($resultado);		
                echo "<tr height='15' bgcolor='#EAEAEA'>";
				echo "<td width='1' colspan='9' height='52' align='right' class='session2_titulos'>TOTALES:</td>";
				echo "<td width='3%' class='celda'>$total[HaberBasico]</td>";
				echo "<td width='5%' class='celda'>$total[bonodeantiguedad]</td>";
				echo "<td width='3%' class='celda'>$total[horasextras]</td>";
				echo "<td width='4%' class='celda'>$total[Importe]</td>";
				echo "<td width='3%' class='celda'>$total[bonodesempeno]</td>";
 				echo "<td width='3%' class='celda'>$total[otrosbonos]</td>";
				echo "<td width='4%' class='celda'>$total[TotalGanado]</td>";
				echo "<td width='3%' class='celda'>$total[AFP]</td>";
				echo "<td width='4%' class='celda'>$total[RCIVA]</td>";
				echo "<td width='5%' class='celda'>$total[anticipo]</td>";
				echo "<td width='5%' class='celda'>$total[TotalGanados]</td>";
				echo "<td width='6%' class='celda'>$total[LiquidoPagable]</td>";
				echo "<td width='10%' class='celda'></td>";
			    echo "</tr>";
		  
		  
	  }
  
  
?>
</table>





</body>
</html>


<?php
$mpdf=new mPDF('utf-8','Legal-L'); 
$content = ob_get_clean();
$mpdf->WriteHTML($content);
$mpdf->Output();
exit;
?>