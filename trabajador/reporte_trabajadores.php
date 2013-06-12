<?php
	ob_start();
	session_start();
	include('../MPDF53/mpdf.php');
	include('../conexion.php');
	$db = new MySQL();

	$sucursal = $_GET['sucursal'];


	$sql = "select imagen,left(nombrecomercial,13)as 'nombrecomercial',nit from empresa;";
	$datoGeneral = $db->arrayConsulta($sql);
    $sql = "select * from sucursal where idsucursal=$sucursal;";
	$datoSucursal = $db->arrayConsulta($sql);

	
	function setcabecera()
	{
	  echo "<tr>
		<td width='19%' rowspan='2' class='session1_cabecera1'>Nombre</td>
		<td width='9%' rowspan='2' class='session1_cabecera1'>C.I.</td>
		<td width='5%' rowspan='2' class='session1_cabecera1'>Sexo</td>
		<td width='7%' rowspan='2' class='session1_cabecera1'>Teléfono</td>
		<td width='7%' rowspan='2' class='session1_cabecera1'>Fecha Ingreso</td>
		<td width='11%' rowspan='2' class='session1_cabecera1'>Cargo</td>
		<td width='7%' rowspan='2' class='session1_cabecera1'>Sueldo Básico</td>
		<td colspan='4' class='session1_cabecera2_1'>Bonos</td>
	  </tr>
	  <tr >
		<td width='8%' class='session1_cabecera1'>Producción</td>
		<td width='9%' class='session1_cabecera1'>Transporte</td>
		<td width='8%' class='session1_cabecera1'>Puntualidad</td>
		<td width='7%' class='session1_cabecera2'>Asistencia</td>
	  </tr>";
	}
		
	
	function pie()
	{
	  echo "<div class='session4_pie'> 
	  <table width='93%' border='0' align='center'>
	  <tr>
		<td width='120' align='right'></td>
		<td width='324'></td>
		<td width='93'>&nbsp;</td>
		<td width='189'>&nbsp;</td>
		<td width='201'>&nbsp;</td>
		<td width='170' >Impreso:".date('d/m/Y');echo"</td>
		<td width='130'>Hora:".date('H:i:s');echo"</td>
	  </tr>
	  </table>
	  </div>";
    }
	
	function nextPage()
	{
	   for ($m = 1; $m < 55; $m++) {
		   echo "<br />";
	   } 
	}	


   	function setDato($nombre, $ci, $sexo, $telefono, $fecha, $cargo, $sueldo, $produccion
	    , $transporte, $puntualidad, $asistencia, $num, $tipo)
	{
	 $clase1 = "";	
	 if ($tipo == "cierre") {
	     $clase1 = "border-bottom:1.5px solid";
	 }	
	 $clase2 = "";
	 if ($num % 2 == 0) {
		 $clase2 = "cebra";
	 }
	 
	 echo "<tr class='$clase2'>
		     <td class='session3_datosF1_1' style='$clase1' align='left'>".ucfirst(strtolower($nombre))."</td>
		     <td class='session3_datosF1_2' style='$clase1'>$ci</td>
			 <td class='session3_datosF1_2' style='$clase1'>$sexo</td>
			 <td class='session3_datosF1_2' style='$clase1'>$telefono</td>
			 <td class='session3_datosF1_2' style='$clase1'>$fecha</td>
			 <td class='session3_datosF1_2' style='$clase1'>".ucfirst(strtolower($cargo))."</td>
			 <td class='session3_datosF1_2' style='$clase1'>".number_format($sueldo, 2)."</td>
			 <td class='session3_datosF1_2' style='$clase1'>".number_format($produccion, 2)."</td>
		     <td class='session3_datosF1_2' style='$clase1'>".number_format($transporte, 2)."</td>
		     <td class='session3_datosF1_2' style='$clase1'>".number_format($puntualidad, 2)."</td>
		     <td class='session3_datosF1_3' style='$clase1'>&nbsp;".number_format($asistencia, 2)."</td>
		   </tr>";

	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet"  type="text/css" href="style_trabajadores.css"/>
<!--La linea anterior incluye el archivo .css-->
<title>Reporte Trabajadores</title>
</head>

<body>
<?php
	$consulta = "select left(concat(t.nombre,' ',t.apellido),27)as 'nombre',t.carnetidentidad,t.sexo,t.telefono,
    t.fechaingreso,left(c.cargo,16) as 'cargo',t.sueldobasico,t.bonoproduccion,t.transporte,t.puntualidad,t.asistencia 
    from trabajador t,cargo c where t.idcargo=c.idcargo and t.estado=1 and idsucursal=$sucursal;";	
	$cant = $db->getnumrow($consulta);
	$numero = 0;
	$row = $db->consulta($consulta);
	$subtotal = 0;
	while($numero < $cant) {
?>


<div style=" position : absolute;left:5%; top:20px;"></div>
<div class='margenprincipal'></div>
<div class='session1_logotipo'><?php echo  "<img src='../$datoGeneral[imagen]' width='200' height='70'/>"; ?></div>

<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1"><?php echo "TRABAJADORES";?></td></tr>   
  </table>
</div>


<div class="session3_datos">

<table width="100%" border="0">
  <tr><td width="6%" align="right" class="session1_subtitulo1">Sucursal:</td>
    <td width="94%" class="session1_subtitulo1"><?php echo $datoSucursal['nombrecomercial'];?></td>
  </tr> 
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<?php
  setcabecera();
 $nota = "";
  $i = 0;
  while ($dato = mysql_fetch_array($row)) {
	  $numero++;
	  $i++;	  	  
      $fecha = $db->GetFormatofecha($dato['fechaingreso'], "-");
	  if ($numero < $cant && $i < 48) { 
	     setDato($dato['nombre'], $dato['carnetidentidad'], $dato['sexo'], $dato['telefono'], $fecha, $dato['cargo']
		 , $dato['sueldobasico'], $dato['bonoproduccion'], $dato['transporte']
		 , $dato['puntualidad'], $dato['asistencia'], $i, "normal");	       	      
	  } else {		  
  	     setDato($dato['nombre'], $dato['carnetidentidad'], $dato['sexo'], $dato['telefono'], $fecha, $dato['cargo']
		 , $dato['sueldobasico'], $dato['bonoproduccion'], $dato['transporte']
		 , $dato['puntualidad'], $dato['asistencia'], $i, "cierre");
	  }
	  
	  if ($i > 47) 
	      break;
  }
?>
</table>
</div>
<?php
   pie();
       if($numero < $cant) {
	       nextPage();
	   }

	}
?>
</body>
</html>

<?php
	$header = "
	<table align='right' width='10%' >  
	  <tr><td align='center' style='border:1px solid;' bgcolor='#E6E6E6' >{PAGENO}/{nb}</td></tr>
	</table>";
	$mpdf=new mPDF('utf-8','Letter-L'); 
	$content = ob_get_clean();
	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit; 
?>