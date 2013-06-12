<?php
   session_start();
   if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: ../index.php");	
   }
   ob_start();
   include('../conexion.php');
   include('../aumentaComa.php');
   include("../MPDF53/mpdf.php");
   $db = new MySQL();
   $logo = $_GET['logo']; 
   //$idproyecto = 2;
   $idproyecto = $_GET['idproyecto'];
   $sql = "select titulo,fechainicio,proyectoterminado
    ,privado,trabajarcon,left(recursos,200)as 'recursos',left(glosa,200)as 'glosa',porcentajeavance,p.fechafinalizacion,fechacierre,
    round(presupuesto,2)as presupuesto,round(presupuestoutil,2)as presupuestoutil,left(concat(t.nombre,' ',t.apellido),25)as 'responsable' from proyecto p
	,usuario u,trabajador t where p.idusuario=u.idusuario and u.idtrabajador=t.idtrabajador and idproyecto=$idproyecto;";
   $maestro = $db->arrayConsulta($sql);
   
   $sql = "select * from empresa";
   $empresa = $db->arrayConsulta($sql);
    
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Proyecto</title>
<link rel="stylesheet" href="proyecto.css" type="text/css" />
<script type="text/javascript" src="../proyecto/graphs.js"></script>
</head>

<body>


<div class="margenPrincipal"></div>

<div class="session1_numTransaccion">
 <table width="100%" border="0">
      <tr><td align="center" height="3"></td></tr> 
     <tr><td class="session1_titulo_num"><?php echo "Nº ".$idproyecto; ?></td></tr> 
    </table>
</div>
<div class="session1_logotipo"><?php if ($logo == 'true'){ echo "<img src='../$empresa[imagen]' width='200' height='70'/>";} ?></div>
<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1">PROYECTO</td></tr> 
    </table>
</div>


<br />
<br />
<br />
<table width="722" border="0" align="center">
  <tr>
    <td width="167" class="session1_titulos">Nombre del Proyecto:</td>
    <td colspan="3" class="session1_datos"><?php echo $maestro['titulo'];?></td>
  </tr>
  <tr>
    <td class="session1_titulos">Responsable:</td>
    <td width="209" class="session1_datos"><?php $responsable = $maestro['responsable'];
	echo $responsable;?></td>
    <td width="110" class="session1_titulos">Colaboradores:</td>
    <td width="218" class="session1_datos">&nbsp;</td>
  </tr>
</table>
<br />
<table width="722" border="0" align="center" cellpadding="0" cellspacing="0" class="session2_tabla">
  <tr>
    <td width="43"  class="session2_cabezera">N°</td>
    <td width="387" class="session2_cabezera">Desarrollo</td>
    <td width="101" class="session2_cabezera">Fecha</td>
    <td width="96"  class="session2_cabezera">Horas</td>
    <td width="94"  class="session2_cabezera" style="border-right:1px solid #000;">Costos</td>
  </tr>
  <?php 
   $sql = "select descripcion,fecha,horas,round(costo,2)as costo from detalleproyecto where idproyecto=$idproyecto;";
   $sql = $db->consulta($sql);
   $i = 0;
   $totalTransaccion = 0;
   while($dato = mysql_fetch_array($sql)){ 
   $totalTransaccion = $totalTransaccion + $dato['costo'];
   $i++;
  if ($i == 25){
	   echo "
		  <tr>
			<td class='session2_datos_nro_final'>$i</td>
			<td class='session2_datos_desarrollo_final'>$dato[descripcion]</td>
			<td class='session2_datos_final'>".$db->GetFormatofecha($dato['fecha'],'-')."</td>
			<td class='session2_datos_final'>$dato[horas]</td>
			<td class='session2_datos_finalDer'>".convertir($dato['costo'])."</td>
		  </tr> ";	 
   }
	else{	   
		 echo "
		  <tr>
			<td class='session2_datos_nro'>$i</td>
			<td class='session2_datos_desarrollo'>$dato[descripcion]</td>
			<td class='session2_datos'>". $db->GetFormatofecha($dato['fecha'],'-') ."</td>
			<td class='session2_datos'>$dato[horas]</td>
			<td class='session2_datos_finalDer'>".convertir($dato['costo'])."</td>
		  </tr> ";
	}
   }
   
  for ($j = $i; $j<=25; $j++){
	 echo "
		  <tr>
			<td class='sessionLateralIzq'>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class='sessionLateralDer'>&nbsp;</td>
		  </tr> ";
	
   }
   
 
  ?> 
    <tr>
    <td class="session2_total">&nbsp;</td>
    <td class="session2_total">&nbsp;</td>
    <td class="session2_total">&nbsp;</td>
    <td class="session2_total_texto">Total</td>
    <td class="session2_total_dato"><?php echo number_format($totalTransaccion,2);?></td>
  </tr>
</table>
<br />
<table width="722" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="118" class="session3_cabecera">Fecha de Inicio</td>
    <td width="103" class="session3_cabecera">Fecha Final</td>
    <td width="47">&nbsp;</td>
    <td width="113" class="session3_cabecera">Presupuesto</td>
    <td width="114" class="session3_cabecera">Costo Real</td>
    <td width="227">&nbsp;</td>
  </tr>
  <tr>
    <td class="session3_datos"><?php  echo $db->GetFormatofecha($maestro['fechainicio'],'-') ;?></td>
    <td class="session3_datos"><?php echo $db->GetFormatofecha($maestro['fechafinalizacion'],'-');?></td>
    <td>&nbsp;</td>
    <td class="session3_datos"><?php echo convertir($maestro['presupuesto']);?></td>
    <td class="session3_datos"><?php echo number_format($totalTransaccion,2);?></td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<table width="722" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="118" class="sessionTitulo">GLOSA:</td>
    <td width="103">&nbsp;</td>
    <td width="68">&nbsp;</td>
    <td width="120">&nbsp;</td>
    <td width="113" class="sessionTitulo">RECURSOS:</td>
    <td width="100">&nbsp;</td>
    <td width="100">&nbsp;</td>
  </tr>
  <tr>
    <td height="50" colspan="3" class="session4_glosa">
    <?php echo $maestro['glosa'];?>
    </td>
    <td>&nbsp;</td>
    <td height="50" colspan="3" class="session4_glosa"><?php echo $maestro['recursos'];?></td>
    
  </tr>
</table>



<div class="session4_datosFirma">
<table width="722" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="63">&nbsp;</td>
    <td width="217" class="session5_firma"><?php echo $responsable;?></td>
    <td width="102">&nbsp;</td>
    <td width="103">&nbsp;</td>
    <td width="129" class="session4_textofecha" align="right"><?php if($maestro['fechacierre']!= "0000-00-00") echo "Fecha de Cierre:";?></td>
    <td width="108" class="session4_datofecha"><?php if($maestro['fechacierre']!= "0000-00-00") echo $db->GetFormatofecha($maestro['fechacierre'],'-');?></td>
  </tr>
</table>
</div>


<div class="sessionDatosFinales"> 
<table width="100%" border="0" align="center">
  <tr>
    <td width="6%">&nbsp;</td>
    <td width="46%" class="contenidoDatos">Dir.:<?php echo $empresa['direccion']?></td>
    <td width="23%" class="contenidoDatos">Telf.:<?php echo $empresa['telefono']?></td>
    <td width="25%" class="contenidoDatos"><?php echo $empresa['website']?></td>
  </tr>
</table>
</div>
<div class="sessionFinal_Ciudad">
<table width="100%" border="0">
  <tr><td class="letra_sessionFinal" align="center"><?php echo $empresa['ciudad'];?></td></tr>
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