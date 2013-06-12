<?php
	ob_start();
	session_start();
	date_default_timezone_set("America/La_Paz");
	include('../MPDF53/mpdf.php');
	include('../conexion.php');
	$db = new MySQL();

	$tituloGeneral = "VENTA POR PRODUCTOS";
	$idgrupo = $_GET['grupo'];
 	$desde = $db->GetFormatofecha($_GET['desde'], "/");
	$hasta = $db->GetFormatofecha($_GET['hasta'], "/");
	
	$fechaInicio = explode("/", $_GET['desde']);
	$fechaFinal = explode("/", $_GET['hasta']);	
	
    $sql = "select * from empresa";
	$datoGeneral = $db->arrayConsulta($sql);
	$sql = "select left(nombre,30)as 'nombre' from grupo where idgrupo=$idgrupo;";
	$datoGrupo = $db->arrayConsulta($sql);
	
	function setcabecera()
	{
	   echo "<tr>
		  <td width='27%' class='session1_cabecera1'>Subgrupo</td>
		  <td width='33%' class='session1_cabecera1'>Producto</td>
		  <td width='16%' class='session1_cabecera1'>U.M.</td>
		  <td width='14%' class='session1_cabecera1'>Cantidad</td>
		  <td width='10%' class='session1_cabecera2'>%</td>
		</tr>";
	}
		
	
	function setDato($num, $tipo, $subgrupo, $producto, $unidadmedida, $cantidad, $porcentaje)
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
	   <td  class='session3_datosF1_1' style='$clase1' align='left'>&nbsp;$subgrupo</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$producto</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$unidadmedida</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;".number_format($cantidad, 2)."</td>
	   <td  class='session3_datosF1_3' style='$clase1' align='left'>&nbsp;".number_format($porcentaje, 2)."</td>
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

	function setSubTotal($cantidad, $porcentaje)
	{
	   echo "<tr>
		  <td class='session1_cabecera1_1' ></td>
		  <td class='session1_cabecera2_1'></td>
		  <td class='session1_cabecera2_1' align='right'>Total Venta:</td>
		  <td class='session1_cabecera2_1'>".number_format($cantidad, 2)."</td>
		  <td class='session1_cabecera2_2'>".number_format($porcentaje, 2)."</td>
		</tr>";
	}    


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet"  type="text/css" href="style_ventaproductos.css"/>
<title>Reporte Venta por Producto</title>
</head>

<body>
<?php
    $sql = "select 
	 sum(if (dn.unidadmedida = p.unidaddemedida,dn.cantidad
	 , round((dn.cantidad/p.conversiones),2)))as 'cantidad'      
	 from detallenotaventa dn,notaventa nv,producto p,subgrupo s
	 where dn.idnotaventa= nv.idnotaventa
	 and dn.idproducto=p.idproducto 
	 and nv.estado=1 
	 and nv.fecha>='$desde' 
	 and nv.fecha<='$hasta'
	 and p.idsubgrupo=s.idsubgrupo 
	 and s.idgrupo= $idgrupo 
	 group by s.idgrupo; ";
    $totalGeneral = $db->arrayConsulta($sql);
 
	$consulta = "
	select left(s.nombre,20)as 'subgrupo',left(p.nombre,20) as 'producto',
	sum(if (dn.unidadmedida = p.unidaddemedida,dn.cantidad
	, round((dn.cantidad/p.conversiones),2)))as 'cantidad'
	,left(p.unidaddemedida,15)as 'unidaddemedida',s.idsubgrupo     
	 from detallenotaventa dn,notaventa nv,producto p,subgrupo s
	 where dn.idnotaventa= nv.idnotaventa
	 and dn.idproducto=p.idproducto 
	 and nv.estado=1 
	 and nv.fecha>='$desde' 
	 and nv.fecha<='$hasta'
	 and p.idsubgrupo=s.idsubgrupo 
	 and s.idgrupo= $idgrupo 
	 group by p.idproducto order by subgrupo ;  ";

	$tope = 49;
	$cant = $db->getnumrow($consulta);
	$numero = 0;
	$row = $db->consulta($consulta);
	$totalCantidad = 0;
	$totalPorcentaje  = 0;
	$idsubgrupo = -1;
	while ($numero < $cant) {
?>


<div style=" position : absolute;left:5%; top:20px;"></div>
<div class='margenprincipal'></div>

<div class='session1_logotipo'><?php echo  "<img src='../$datoGeneral[imagen]' width='200' height='70'/>"; ?></div>

<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1"><?php echo $tituloGeneral;?></td></tr>  
     <tr><td align="center" class="session1_titulo2">
	 <?php echo "Del $fechaInicio[0] de ".$db->mes($fechaInicio[1])." del "
	 ." $fechaInicio[2] al $fechaFinal[0] de ".$db->mes($fechaFinal[1])." del $fechaFinal[2]"    ;?></td></tr>    
     <tr><td align="center" class="session1_titulo2"><?php echo "(Expresado en Bolivianos)";?></td></tr>  
  </table>
</div>


<div class="session3_datos">
<table width="100%" border="0">
  <tr><td width="6%" align="right" class="session1_subtitulo1">Grupo:</td>
    <td width="94%" class="session1_subtitulo1"><?php echo $datoGrupo['nombre'];?></td>
  </tr>
</table>
<table width='100%' border='0' cellspacing='0' cellpadding='0' align='center'>
<?php
 setcabecera();
 $nota = "";
  $i = 0;

  while ($data = mysql_fetch_array($row)) {
	  $numero++;
	  $i++;	
	  
	  if ($i == 1) {
		 $idsubgrupo = $data['idsubgrupo']; 
		 $subgrupo = $data['subgrupo'];
	  }
	  
	  if ($data['idsubgrupo'] != $idsubgrupo) {
		  setSubTotal($totalCantidad, $totalPorcentaje);
		  $totalCantidad = 0;
		  $totalPorcentaje = 0;
		  $idsubgrupo = $data['idsubgrupo']; 
		  $i++;
		  $subgrupo = $data['subgrupo'];
	  }
	  
	  $tipoFila = "normal";
	  if ($numero == $cant || $i == $tope) {
		$tipoFila = "normal";  
	  }
	  
	  $porcentaje = ($data['cantidad'] /$totalGeneral['cantidad']) * 100;
	  setDato($i, $tipoFila, $subgrupo, $data['producto'], $data['unidaddemedida'], $data['cantidad'], $porcentaje);
  	  $totalCantidad =  $totalCantidad + $data['cantidad'];
	  $totalPorcentaje = $totalPorcentaje + $porcentaje;
	  $subgrupo = "";
	  
	  
	  if ($i == $tope) 
	      break;
	  	  
  }
   setSubTotal($totalCantidad, $totalPorcentaje);  
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
	$header = "";
	$mpdf = new mPDF('utf-8','Letter'); 
	$content = ob_get_clean();
	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit; 
?>