<?php
	session_start();
	if (!isset($_SESSION['softLogeoadmin']) || !isset($_GET['meses']) 
	   || !isset($_GET['anio']) || !isset($_GET['trabajador'])) {
		   header("Location: ../cerrar.php");	
	}
	ob_start();
	include("../MPDF53/mpdf.php");
	include("../conexion.php");
	include('../reportes/literal.php');
	$db = new MySQL();
	$mes = $_GET['meses'];
	$anio = $_GET['anio'];
	$idtrabajador = $_GET['trabajador'];
	$sql = "select imagen,left(nombrecomercial,13)as 'nombrecomercial',nit from empresa;";
	$datoGeneral = $db->arrayConsulta($sql);

	$sql = "SELECT left(d.nombre,20) as 'departamento',p.idplanilla
	,left(concat(t.nombre,' ', t.apellido),25)as 'nombre', t.sexo, t.carnetidentidad,
	left(t.nacionalidad,20)as 'nacionalidad'
	,date_format(t.fechanacimiento,'%d/%m/%Y')as 'fechanacimiento',p.importehorasextras, 
	left(c.cargo,20) as 'cargo',date_format(t.fechaingreso,'%d/%m/%Y')as 'fechaingreso',
	 ROUND( p.sueldobasico, 2 ) AS  'sueldobasico',
	 ROUND( b.bonoproduccion, 2 ) AS  'bonoproduccion',
	 b.horasextras, b.transporte, b.puntualidad, b.comisiones,
	  b.asistencia, ROUND( p.anticipo, 2 ) AS  'anticipo', 
	ROUND( p.totalganado, 2 ) AS  'totalganado', 
	ROUND( p.totaldescuento, 2 ) AS  'totaldescuento',left(s.nombrecomercial,16)as 'nombrecomercial', 
	ROUND( p.afp, 2 ) AS  'afp',p.bonoantiguedad ,
	(SELECT TIMESTAMPDIFF(YEAR,t.fechaingreso,current_date))  AS 'anios',
	(SELECT (TIMESTAMPDIFF(MONTH,t.fechaingreso,current_date)) 
	- (TIMESTAMPDIFF(YEAR,t.fechaingreso,current_date) * 12)) AS 'meses',
	(SELECT DATEDIFF(current_date,DATE_ADD(DATE_ADD(t.fechaingreso,
	 INTERVAL TIMESTAMPDIFF(YEAR,t.fechaingreso,current_date) YEAR), 
	INTERVAL (TIMESTAMPDIFF(MONTH,t.fechaingreso,current_date)) 
	- (TIMESTAMPDIFF(YEAR,t.fechaingreso,current_date) * 12) MONTH))) AS 'dias',
	p.diastrabajados,t.sueldobasico as 'sueldooficial' 
	FROM planilla p, trabajador t, cargo c, bono b,departamento d,sucursal s 
	WHERE p.idtrabajador = t.idtrabajador
	AND t.idcargo = c.idcargo
	AND t.seccion = d.iddepartamento 
	AND s.idsucursal = t.idsucursal 
	AND b.idbono = p.idbono
	AND MONTH( p.fecha ) =$mes
	AND YEAR( p.fecha ) =$anio
	AND t.idtrabajador=$idtrabajador ;";
	
	$boleta = $db->arrayConsulta($sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="boleta.css" type="text/css" />
<title>Reporte Boleta de Pago</title>
</head>

<body>

<div class="session1_datoempresa"><?php echo strtoupper($datoGeneral['nombrecomercial']);  ?></div>
<div class="session1_datoempresa_nit"><?php echo "NIT: $datoGeneral[nit]"; ?></div>
<div class="session1_logotipo">
   <?php echo "<img src='../$datoGeneral[imagen]' width='200' height='70'/>"; ?>
  </div>
<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1">BOLETA DE PAGO</td></tr>
     <tr><td align="center">CORRESPONDIENTE AL MES DE <?php echo $db->mes($mes); ?> DE <?php echo $anio; ?></td></tr>
     <tr><td align="center">(Expresado en Bolivianos)</td></tr>
     <tr><td align="center">&nbsp;</td></tr>
    </table>
 </div>
 <div class="session2_datosPersonales">
   <table width="100%" border="0">
  <tr>
    <td colspan="9" class="session2_tituloDP">DATOS PERSONALES</td>
    </tr>
  <tr>
    <td width="15%" class="session2_subTitulos">Nombre:</td>
    <td width="19%" class="session2_contenido"><?php echo $boleta['nombre'];?></td>
    <td width="15%" class="session2_subTitulos">Departamento:</td>
    <td colspan="3" class="session2_contenido"><?php echo $boleta['departamento'];?></td>
    <td width="15%" class="session2_subTitulos">ID Trabajador:</td>
    <td width="14%" class="session2_contenido"><?php echo $idtrabajador;?></td>
    <td width="2%">&nbsp;</td>
  </tr>
  <tr>
    <td class="session2_subTitulos">Nº C.I.:</td>
    <td class="session2_contenido"><?php echo $boleta['carnetidentidad'];?></td>
    <td class="session2_subTitulos">Cargo:</td>
    <td colspan="3" class="session2_contenido"><?php echo $boleta['cargo'];?></td>
    <td class="session2_subTitulos">Nº C.N.S.:</td>
    <td class="session2_contenido">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="session2_subTitulos">Nacionalidad:</td>
    <td class="session2_contenido"><?php echo $boleta['nacionalidad'];?></td>
    <td class="session2_subTitulos">Fecha de Ing.:</td>
    <td colspan="3" class="session2_contenido"><?php echo $boleta['fechaingreso'];?></td>
    <td class="session2_subTitulos">Credito Fiscal:</td>
    <td class="session2_contenido">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="session2_subTitulos">Fecha de Nac.:</td>
    <td class="session2_contenido"><?php echo $boleta['fechanacimiento'];?></td>
    <td class="session2_subTitulos">Antiguedad:</td>
    <td width="7%" class="session2_contenido"><?php echo $boleta['anios']." Año";?></td>
    <td width="7%" class="session2_contenido"><?php echo $boleta['meses']." Mes";?></td>
    <td width="6%" class="session2_contenido"><?php echo $boleta['dias']." Dia";?></td>
    <td class="session2_subTitulos">Sucursal:</td>
    <td class="session2_contenido"><?php echo $boleta['nombrecomercial'];?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="session2_subTitulos">Sexo:</td>
    <td class="session2_contenido">
	<?php 
	 $sexo = $boleta['sexo'];
	  if ($sexo == "M")
	   echo "Masculino";
	  else
	   echo "Femenino";  
	?></td>
    <td class="session2_subTitulos">Dias Trabajados:</td>
    <td colspan="3" class="session2_contenido"><?php echo $boleta['diastrabajados'];?></td>
    <td class="session2_subTitulos">Haber Basico:</td>
    <td class="session2_contenido"><?php echo number_format($boleta['sueldooficial'] ,2);?></td>
    <td>&nbsp;</td>
  </tr>
</table>
</div>

<div class="session3_ingresos">
<table width="100%" border="0">
  <tr>
    <td width="49%" class="session3_contornos">
    <table width="100%" border="0">
  <tr>
    <td colspan="2" class="sessiontituloDP">INGRESOS</td>
    </tr>
  <tr>
    <td width="300px" class="sessionsubTitulos">Haber Basico:</td>
    <td width="100px"><?php echo number_format($boleta['sueldobasico'],2);?></td>
  </tr>
  <tr>
    <td class="sessionsubTitulos">Bono de Antiguedad:</td>
    <td><?php echo number_format($boleta['bonoantiguedad'],2);?></td>
  </tr>
  <tr>
    <td class="sessionsubTitulos">Horas Extras Pagadas:</td>
    <td><?php echo number_format($boleta['horasextras'],2);?></td>
  </tr>
  <tr>
    <td class="sessionsubTitulos">Bono de Produccion:</td>
    <td><?php echo number_format($boleta['bonoproduccion'],2);?></td>
  </tr>
  <tr>
    <td class="sessionsubTitulos">Otros Bonos:</td>
    <td><?php 
	$otrosbonos = $boleta['transporte'] + $boleta['puntualidad'] + $boleta['comisiones'] + $boleta['asistencia'];
	echo number_format($otrosbonos,2);?></td>
  </tr>
</table>

    
    </td>
    <td width="1%"></td>
    <td width="50%" class="session3_contornos">
    <table width="100%" border="0">
  <tr>
    <td colspan="2" class="sessiontituloDP">DESCUENTOS</td>
    </tr>
  <tr>
    <td width="300px" class="sessionsubTitulos">A.F.P.:</td>
    <td width="100px"><?php echo number_format($boleta['afp'],2);?></td>
  </tr>
  <tr>
    <td class="sessionsubTitulos">R.C-I.V.A.:</td>
    <td>0.00</td>
  </tr>
  <tr>
    <td class="sessionsubTitulos">Anticipos:</td>
    <td><?php echo number_format($boleta['anticipo'],2);?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

    
    
    
    </td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td class="session3_contornos">
      <table width="100%" border="0">
       <tr>
         <td width="300px" align="right" class="sessiontituloDP">TOTAL INGRESOS:</td>
         <td width="100px"><?php echo number_format($boleta['totalganado'],2);?></td>
         <td width="50px">&nbsp;</td>
       </tr>
      </table>
    </td>
    <td></td>
    <td class="session3_contornos">
        <table width="100%" border="0">
          <tr>
            <td width="300px" class="sessiontituloDP" align="right">TOTAL DESCUENTOS:</td>
            <td width="100px"><?php echo number_format($boleta['totaldescuento'],2);?></td>
            <td width="50px">&nbsp;</td>
          </tr>
        </table>    
    </td>
  </tr>
</table>
</div>

<div class="session4_firma">
<table width="100%" border="0">
  <tr>
    <td width="39%" class="session3_contornos">
    <table width="100%" border="0">
       <tr>
        <td width="300px">&nbsp;</td>
       </tr>
       <tr>
         <td class="session4_tituloFirma" >Recibi Conforme</td>
       </tr>
       </table>   
    </td>
    <td width="1%"></td>
    <td width="60%" class="session3_contornos">
       <table width="100%" border="0">
         <tr>
          <td colspan="2" class="session2_tituloDP">LIQUIDO PAGABLE </td>
          <td width="100px" class="session3_contornos">
          <?php
		  $total = $boleta['totalganado'] - $boleta['totaldescuento'];
		  echo number_format($total,2);
		  ?>
          </td>
          <td width="150px">&nbsp;</td>
         </tr>
         <tr>
           <td width="5px" class="session2_subTitulos">Son:</td>
           <td colspan="3" class="session2_letraAliteral"><?php echo strtoupper(NumerosALetras($total)); ?></td>
          </tr>
        </table>

    
    </td>
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