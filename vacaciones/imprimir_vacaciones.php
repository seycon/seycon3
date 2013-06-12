<?php
	ob_start();
	session_start();
	date_default_timezone_set("America/La_Paz");
	include('../MPDF53/mpdf.php');
	include('../conexion.php');
	$db = new MySQL();
	if (!isset($_SESSION['softLogeoadmin']) || !isset($_GET['idvacaciones'])) {
		   header("Location: ../index.php");	
	}	
	$tituloGeneral = "AUTORIZACION DE VACACIONES";
	$idvacaciones = $_GET['idvacaciones'];
	$logo = $_GET['logo'];	
	
    $sql = "select * from empresa";
	$datoGeneral = $db->arrayConsulta($sql);
	
	
	function pie($usuario)
	{
		echo "
		<div class='session4_pie'> 
		  <table width='93%' border='0' align='center'>
		  <tr>
			<td width='120' align='right'>Elaborado por:</td>
			<td width='324'>$usuario</td>
			<td width='93'>&nbsp;</td>
			<td width='189'>&nbsp;</td>
			<td width='201'>&nbsp;</td>
			<td width='170' >Impreso:".date('d/m/Y');echo"</td>
			<td width='130'>Hora:".date('H:i:s');echo"</td>
		  </tr>
		  </table>
		</div>";
    }
	
	function getDia($indice) 
	{
	  $dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"); 
	  return $dias[$indice - 1];
	}

	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet"  type="text/css" href="style_vacaciones.css"/>
<title>Reporte de Vacaciones</title>
</head>

<body>
  <?php
      $sql = "select left(concat(t.nombre,' ',t.apellido),30)as 'nombre',
	  left(concat(tb.nombre,' ',tb.apellido),30)as 'usuario'
      ,t.fechaingreso,left(v.motivo,300)as 'motivo',v.idvacaciones,v.diashabilitado,
      (YEAR(CURDATE())-YEAR(t.fechaingreso))- (RIGHT(CURDATE(),5)<RIGHT(t.fechaingreso,5))as 'anios',
      t.carnetidentidad,t.origenci,left(c.cargo,20)as 'cargo',left(s.nombrecomercial,15)as 'sucursal',
      left(s.ciudad,25)as 'ciudad',v.fecha,left(d.nombre,20)as 'departamento'    
       from vacaciones v,trabajador t,cargo c,sucursal s,trabajador tb,usuario u,
	   departamento d    
       where v.idtrabajador=t.idtrabajador 
       and t.idcargo=c.idcargo   
       and t.idsucursal=s.idsucursal 
	   and v.idusuario=u.idusuario 
	   and t.seccion=d.iddepartamento 
	   and u.idtrabajador=tb.idtrabajador 
       and v.idvacaciones=$idvacaciones limit 1;";
      $datoVacaciones = $db->arrayConsulta($sql);
  ?>
  <div style=" position : absolute;left:5%; top:20px;"></div>
  <div class='margenprincipal'></div>
  <div class='session1_logotipo'>
  <?php 
  if ($logo == 'true') {
      echo  "<img src='../$datoGeneral[imagen]' width='200' height='70'/>";
  }
  ?>
  </div>

<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1"><?php echo $tituloGeneral;?></td></tr>  
     <tr><td align="center" class="session1_titulo2"></td></tr>    
     <tr><td align="center" class="session1_titulo2"></td></tr>  
  </table>
</div>

<div class="session1_sucursal">
    <table width="100%" border="0">
      <tr><td align="center" class="session1_tituloSucursal">
	  <?php echo strtoupper($datoVacaciones['sucursal']); ?></td></tr>
    </table>
</div>

<div class="session3_datos">

<table width="100%" border="0">
  <tr>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
    <td align="right" class="session1_subtitulo1">&nbsp;</td>
    <td class="session1_subtitulo1"></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Fecha de Ingreso:</td>
    <td class="session1_subtitulo1"><?php echo $db->GetFormatofecha($datoVacaciones['fechaingreso'],"-");?></td>
    <td align="right" class="session1_subtitulo">Años de Servicio:</td>
    <td class="session1_subtitulo1"><?php echo $datoVacaciones['anios'];?></td>
  </tr>
  <tr>
    <td width="20%" align="right" class="session1_subtitulo">Trabajador:</td>
    <td width="35%" class="session1_subtitulo1"><?php echo $datoVacaciones['nombre'];?></td>
    <td width="16%" align="right" class="session1_subtitulo">C.I.:</td>
    <td width="29%" class="session1_subtitulo1"><?php echo $datoVacaciones['carnetidentidad'].
	" / ".$datoVacaciones['origenci'];?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Departamento:</td>
    <td class="session1_subtitulo1"><?php echo $datoVacaciones['departamento'];?></td>
    <td align="right" class="session1_subtitulo">Cargo:</td>
    <td class="session1_subtitulo1"><?php echo $datoVacaciones['cargo'];?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo">Observación:</td>
    <td class="session1_subtitulo1">&nbsp;</td>
    <td align="right" class="session1_subtitulo">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align="center"><table width="650" border="0">
      <tr>
        <td class="bordeGlosa">&nbsp;<?php echo $datoVacaciones['motivo'];?></td>
      </tr>
    </table></td>
    </tr>
</table>

  <table width="100%" border="0">
    <tr>
      <td width="20%" align="right" class="session1_subtitulo">Días de Vacaciones:</td>
      <td width="80%">&nbsp;</td>
    </tr>
  </table>

 <div class="cuadroFirmas">   
     <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="2%">&nbsp;</td>
        <td colspan="3" class="session1_subtitulo1">
        <?php
			  $sql = "select date_format(fecha,'%d/%m/%Y')as 'fecha',DAYOFWEEK(fecha)as 'dias'  
			  from detallevacaciones where idvacaciones=$datoVacaciones[idvacaciones] order by fecha";
			  $dato = $db->consulta($sql);	
			  $num = $db->getnumRow($sql);	
			  $i = 0; 
			  while ($data = mysql_fetch_array($dato)) {
				$i++;  
				if ($num == $i) {  
				    echo getDia($data['dias'])." ".$data['fecha'].".";	
				} else {
				    echo getDia($data['dias'])." ".$data['fecha'].", ";
				}
			  }
		?>
        </td>
        <td width="2%">&nbsp;</td>
      </tr>
     </table>
 </div>
 
<table width="886" border="0">
  <tr>
    <td class="session1_subtitulo" align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="session1_subtitulo" align="right">Días que le corresponden:</td>
    <td class="session1_subtitulo1"><?php echo $datoVacaciones['diashabilitado'];?> Días</td>
    <td></td>
  </tr>
  <tr>
    <td width="687" class="session1_subtitulo" align="right">Días de vacaciones:</td>
    <td width="163" class="session1_subtitulo1"><?php echo $num;?> Días</td>
    <td width="22"></td>
  </tr>
  <tr>
    <td class="session1_subtitulo" align="right">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
    <td></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="session1_subtitulo3">
    <?php 
		$datofecha = explode("-", $datoVacaciones['fecha']);
		echo $datoVacaciones['ciudad'].", ".getDia($datofecha[2])." ".$datofecha[2]
		." de ".ucfirst(strtolower($db->mes($datofecha[1])))." del ". $datofecha[0];
	?>
    </td>
    <td></td>
  </tr>
</table>
</div>
<?php pie($datoVacaciones['usuario']);?>

 <div class="session3_subPie"> 
 <table width="93%" border="0" align="center">
  <tr>
    <td width="87">&nbsp;</td>
    <td width="184" class="negrita">............................................</td>
    <td width="75">&nbsp;</td>
    <td width="181" class="negrita">...........................................</td>
    <td width="60">&nbsp;</td>
    <td width="181" class="negrita">..........................................</td>
    <td width="156">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold">Elaborado Por</td>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold">Autorizado</td>
    <td>&nbsp;</td>
    <td align="center" style="font-weight:bold">Trabajador</td>
    <td>&nbsp;</td>
  </tr>
 </table> 
 </div>
  
</body>
</html>

<?php
	$header = "
	<table align='right' width='10%' >  
	  <tr><td align='center' style='border:1px solid;' bgcolor='#E6E6E6' >{PAGENO}/{nb}</td></tr>
	</table>";
	$mpdf = new mPDF('utf-8','Letter'); 
	$content = ob_get_clean();
	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit; 
?>