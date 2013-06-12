<?php
	ob_start();
	session_start();
	date_default_timezone_set("America/La_Paz");
	include('../MPDF53/mpdf.php');
	include('../conexion.php');
	$db = new MySQL();	
	if (!isset($_GET['sucursal']) || !isset($_GET['hasta'])) {		
		header("Location: ../index.php");
	}	
	
	$tituloGeneral = "ACTIVOS FIJOS DISPONIBLES";		
	$moneda = $_GET['moneda'];
	$sucursal = $_GET['sucursal'];
	
	$hasta = $db->GetFormatofecha($_GET['hasta'], "/");	
	$fechaFinal = explode("/", $_GET['hasta']);	
	
    $sql = "select * from empresa";
	$datoGeneral = $db->arrayConsulta($sql);
	if ($sucursal != "") {
		$sql = "select left(nombrecomercial,25)as 'sucursal' 
		from sucursal where idsucursal=$sucursal";
		$datoSucursal = $db->arrayConsulta($sql);
		$condicion = " and a.idsucursal=$sucursal ";
	} else {
		$datoSucursal['sucursal'] = "Consolidado";
		$condicion = "";
	}
	$sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
    $tc = $db->getCampo('dolarcompra',$sql);
	if ($moneda == "Bolivianos") {
		$tc = 1;
	}
	
	function setcabecera()
	{
	   echo "
		<tr >
			<td width='13%' class='session1_cabecera1'>F. Ingreso</td>
			<td width='9%' class='session1_cabecera1'>Nº</td>
			<td width='10%' class='session1_cabecera1'>Cant.</td>
			<td width='25%' class='session1_cabecera1'>Tipo de Activo Fijo</td>
			<td width='31%' class='session1_cabecera1'>Nombre</td>
			<td width='12%' class='session1_cabecera2'>Valor Inicial</td>
		</tr>";
	}
		
	
	function setDato($num, $tipo, $fecha, $idactivo, $cantidad, $tipoactivo, $activo, $valor)
	{
	  $clase1 = "";	
	  if ($tipo == "final") {
	      $clase1 = "border-bottom:1.5px solid";		
	  }	
      $clase2 = "";
	  if ($num % 2 == 0) {
		  $clase2 = "cebra";
	  }	
	 
	  echo "<tr class='$clase2'>
	   <td  class='session3_datosF1_1' style='$clase1' align='center'>&nbsp;$fecha</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='center'>&nbsp;AF-$idactivo</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$cantidad</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$tipoactivo</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$activo</td>
	   <td  class='session3_datosF1_3' style='$clase1' align='left'>&nbsp;".number_format($valor, 2)."</td>	  
	  </tr>";	
	}	
		
	function setTotalF($total, $cantidad)
	{
	    echo "
		<tr >
		  <td class='session3_datos1_2'>&nbsp;</td>
		  <td class='session1_subtitulo1' align='right'>Cantidad:</td>
		  <td class='session3_datosF2_Total' align='center'>".number_format($cantidad, 2)."</td>
		  <td class='session3_datos1_2'>&nbsp;</td>
          <td class='session1_subtitulo1' align='right'>Sub Total:</td>
		  <td class='session3_datosF2_Total' align='center'>".number_format($total, 2)."</td>
		</tr>";	
		
	}	
	
	function setTotalF_2($total, $cantidad)
	{
	    echo "
		<tr >
		  <td >&nbsp;</td>
		  <td class='titulo_1' align='right'>Cantidad:</td>
		  <td class='session3_datosF2_Total_1' align='center'>".number_format($cantidad, 2)."</td>
		  <td>&nbsp;</td>
          <td class='titulo_1' align='right'>Sub Total:</td>
		  <td class='session3_datosF2_Total_1' align='center'>".number_format($total, 2)."</td>
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
	
	function totalGeneral($total, $cantidad)
	{
	echo "
	<table width='100%' border='0' cellspacing='0' cellpadding='0' align='center'>  
    <tr>
        <td width='13%' height='7'></td>
        <td width='9%' ></td>
        <td width='12%'></td>
        <td width='29%'></td>
        <td width='25%'></td>
        <td width='12%'></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td class='titulo_1' align='right'>Cantidad:</td>
      <td align='center' class='session3_datosF1_Total'>".number_format($cantidad, 2)."</td>
      <td>&nbsp;</td>
      <td class='titulo_1' align='right'>Total:</td>
      <td align='center' class='session3_datosF1_Total'>".number_format($total, 2)."</td>
    </tr>
    </table>	
	";	
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet"  type="text/css" href="style_activos.css"/>
<title>Reporte Activos Fijos</title>
</head>

<body>
<?php
	$consulta = "
	 SELECT a.idactivo, a.fechacompra, a.cantidad
	 , left( a.nombre, 30 ) AS 'nombre', left( ta.nombre, 20 ) AS 'tipoactivo', 
	 (a.precio * a.cantidad
      ) AS 'total', ta.idtipoactivo,
	  (select sum(b.cantidad) from bajaactivo b where b.idactivo=a.idactivo 
	  and b.estado=1 and b.fechabaja<='$hasta')as 'baja'
     FROM activo a, tipoactivo ta
      WHERE a.idtipoactivo = ta.idtipoactivo
      AND a.estado =1 and a.fechacompra<='$hasta' $condicion order by a.idactivo;";
	
	$tope = 47;
	$cant = $db->getnumrow($consulta);
	$numero = 0;
	$row = $db->consulta($consulta);
	$totalGeneral = array(0, 0);
	$idtipoactivo = "";
	while ($numero < $cant) {
?>


<div style=" position : absolute;left:5%; top:20px;"></div>
<div class='margenprincipal'></div>

<div class="session1_sucursal">
    <table width="100%" border="0">
      <tr><td align="center" class="session1_tituloSucursal">
      <?php echo strtoupper($datoSucursal['sucursal']); ?>
      </td></tr>
    </table>
</div>

<div class='session1_logotipo'><?php echo  "<img src='../$datoGeneral[imagen]' width='200' height='70'/>"; ?></div>

<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1"><?php echo $tituloGeneral;?></td></tr>  
     <tr><td align="center" class="session1_titulo2">
	 <?php echo " Al $fechaFinal[0] de ".$db->mes($fechaFinal[1])." del $fechaFinal[2]"    ;?></td></tr>    
     <tr><td align="center" class="session1_titulo2"><?php echo "(Expresado en $moneda)";?></td></tr>  
  </table>
</div>


<div class="session3_datos">
<table width='100%' border='0' cellspacing='0' cellpadding='0' align='center'>
<?php
  setcabecera();
  $i = 0;
  $numeracion = 0;
  while ($data = mysql_fetch_array($row)) {
	  $numero++;
	  $i++;	
	  $numeracion++;
	  $tipoFila = "normal";
	  $fecha  = $db->GetFormatofecha($data['fechacompra'], "-");
	  if ($i == 1) {
		  $idtipoactivo = $data['idtipoactivo'];  
	  }	  
	  if ($idtipoactivo != $data['idtipoactivo'] && $subTotal > 0) {
		  setTotalF($subTotal, $subCantidad);
		  $i++;
		  $subTotal = 0;  
		  $subCantidad = 0;
		  $numeracion = 1;
	  }	  
	  
	  $subTotal = $subTotal + ($data['total'] / $tc);
	  $subCantidad = $subCantidad + ($data['cantidad'] - $data['baja']);
	  $totalGeneral[0] = $totalGeneral[0] + $data['total'];
	  $totalGeneral[1] = $totalGeneral[1] + ($data['cantidad'] - $data['baja']);
	  
	  if ($numero == $cant || $i >= $tope) {
		  $tipoFila = "final";  
	  }
	  setDato($numeracion, $tipoFila, $fecha, $data['idactivo'], ($data['cantidad'] - $data['baja']), $data['tipoactivo']
	  , $data['nombre'], $data['total']);
	  
	  if ($i == $tope) 
	      break;	  	  
  }
  
  if ($tipoFila == "final") {
      setTotalF_2($subTotal, $subCantidad); 
  }
?>

</table>

<?php

  if ($tipoFila == "final") {
      totalGeneral($totalGeneral[0], $totalGeneral[1]);
  }

?>

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
	<table align='right' width='12%' >  
	  <tr><td heigth='10'></td></tr>
	  <tr><td align='center' style='border:1px solid;font-size:11px;' bgcolor='#E6E6E6' >{PAGENO}/{nb}</td></tr>
	</table>";
	$mpdf = new mPDF('utf-8','Letter'); 
	$content = ob_get_clean();
	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit; 
?>