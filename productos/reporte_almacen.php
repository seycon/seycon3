<?php
	ob_start();
	session_start();
	include('../MPDF53/mpdf.php');
	include('../conexion.php');
	$db = new MySQL();
	$almacen = $_GET['idalmacen'];
	$grupo = $_GET['idgrupo'];	
	//$almacen = 12 ;
	//$grupo = 16;
	$desde = $_GET['fecha'];
	
	$fechaF = explode('/',$desde);
	$desde = $fechaF[2]."/".$fechaF[1]."/".$fechaF[0];
	
	$sql = "select imagen,left(nombrecomercial,13)as 'nombrecomercial',nit from empresa;";
	$datoGeneral = $db->arrayConsulta($sql);
	$sql = "select nombre from grupo where idgrupo=$grupo;";
	$datoGrupo = $db->arrayConsulta($sql);
    $sql = "select nombre from almacen where idalmacen=$almacen;";
	$datoAlmacen = $db->arrayConsulta($sql);
	
	function setcabecera()
	{
		echo "<tr >
		<td colspan='2' rowspan='2' class='session1_cabecera1'>Sub Grupo</td>
		<td width='34%' rowspan='2' class='session1_cabecera1'>Producto</td>
		<td colspan='2' class='session1_cabecera1'>Sistema</td>
		<td colspan='2' class='session1_cabecera2'>Fisico</td>
	  </tr>
	  <tr>
		<td width='13%' class='session1_cabecera3'>U.P.</td>
		<td width='12%' class='session1_cabecera3'>U.A.</td>
		<td width='11%' class='session1_cabecera3'>U.P.</td>
		<td width='11%' class='session1_cabecera4'>U.A.</td>
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
			

	function getCantidades($cantidad, $conversion)
	{
		$total = array(0,0);
		if ($cantidad != "") {
		    $dato = explode(".",$cantidad);
		    $total[0] = $dato[0];
		    $cant = "0.".$dato[1];
		    $total[1] = (float) $cant * $conversion;
		}
		return $total;	
	}
	
	function setDato($tipo, $subgrupo, $producto ,$cantidad, $conversion)
	{
	 $clase1 = "";	
	 if ($tipo == "cierre") {
	     $clase1 = "border-top:1.5px solid";
	 }	
	  if ($tipo == "final") {
		 if (trim($subgrupo) == "") {
			 $clase1 = "border-bottom:1.5px solid";
		 } else {
	         $clase1 = "border-top:1.5px solid;border-bottom:1.5px solid";
		 }
	 }	
	 $stock = getCantidades($cantidad, $conversion);		
		
	 echo "<tr >
	  <td colspan='2'  class='session3_datosF1_1' style='$clase1' align='left'>&nbsp;$subgrupo</td>
	  <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$producto</td>
	  <td  class='session3_datosF1_2' style='$clase1' >".number_format($stock[0],2)."</td>
	  <td  class='session3_datosF1_2' style='$clase1' >".number_format($stock[1],2)."</td>
	  <td  class='session3_datosF1_2' style='$clase1' >_________</td>
	  <td  class='session3_datosF1_3' style='$clase1' >_________</td>
	 </tr>";	
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet"  type="text/css" href="style.css"/>
<!--La linea anterior incluye el archivo .css-->
<title>Reporte de Almacén</title>
</head>

<body>
<?php
	$consulta = "select 
	round((SUM( IF( d.unidadmedida = psa.unidaddemedida, d.cantidadingresada, d.cantidadingresada / psa.conversiones ) ) 
	- 
	COALESCE((
	
	SELECT 
	SUM( IF( d.unidadmedida = p.unidaddemedida, d.cantidad, d.cantidad / p.conversiones ) ) AS 'Total_Engreso' 
	FROM  producto p, detalleegresoproducto d, egresoproducto e, almacen a 
	WHERE 
	p.idproducto = d.idproducto AND d.idegresoprod = e.idegresoprod 
	AND a.idalmacen = e.idalmacen AND a.idalmacen =$almacen 
	and e.fecha<='$desde'  
	and p.idproducto=psa.idproducto and e.estado=1 
	GROUP BY p.idproducto 
	
	),0) -
	COALESCE((
	SELECT 
	SUM( IF( d.unidadmedida = p.unidaddemedida, d.cantidad, d.cantidad / p.conversiones ) ) AS 'Total_Trapaso' 
	FROM  producto p, detalletraspaso d, traspaso t, almacen a 
	WHERE 
	 p.idproducto = d.idproducto AND d.idtraspaso = t.idtraspaso 
	AND a.idalmacen = t.idalmacenorigen AND a.idalmacen =$almacen 
	and t.fecha<='$desde'
	and p.idproducto=psa.idproducto and t.estado=1 
	GROUP BY p.idproducto 
	
	),0)-
	
	COALESCE((
	SELECT 
	SUM( IF( d.unidadmedida = p.unidaddemedida, d.cantidad, d.cantidad / p.conversiones ) ) AS 'Total_VentaR' 
	FROM producto p, detallerequerimiento d,detalleingresoproducto di,detalleatencion da,atencion at  
	, almacen a,ingresoproducto i 
	WHERE 
	 da.idatencion = at.idatencion  and d.iddetalleatencion = da.iddetalleatencion 
	and d.iddetalleingreso = di.iddetalleingreso and di.idingresoprod = i.idingresoprod 
	and p.idproducto = di.idproducto AND a.idalmacen = i.idalmacen 
	AND a.idalmacen =$almacen and date(at.fecha)<='$desde' 
	and da.estado=1 and p.idproducto=psa.idproducto 
	GROUP BY p.idproducto 
	),0)-
	
	COALESCE((
	
	SELECT 
	
	SUM( IF( d.unidadmedida = p.unidaddemedida, d.cantidad, d.cantidad / p.conversiones ) ) AS 'Total_Venta' 
	FROM producto p, detallenotaventa d,detalleingresoproducto di,notaventa n
	, almacen a,ingresoproducto i 
	WHERE 
	p.idproducto = d.idproducto AND d.idnotaventa = n.idnotaventa 
	and d.iddetalleingreso = di.iddetalleingreso and di.idingresoprod = i.idingresoprod 
	AND a.idalmacen = i.idalmacen AND a.idalmacen =$almacen 
	and n.fecha<='$desde'   
	and p.idproducto=psa.idproducto and n.estado=1 
	GROUP BY p.idproducto 
	
	),0)),4)as 'total',psa.nombre,s.nombre as 'subgrupo',g.nombre as 'grupo',psa.conversiones   
	FROM  producto psa, detalleingresoproducto d, ingresoproducto i, almacen a,grupo g,subgrupo s 
	WHERE psa.idproducto = d.idproducto AND d.idingresoprod = i.idingresoprod 
	AND a.idalmacen = i.idalmacen AND a.idalmacen = $almacen 
	and psa.idsubgrupo=s.idsubgrupo and s.idgrupo=g.idgrupo and i.estado=1 and g.idgrupo=$grupo   
	AND d.estado =1 and i.fecha<='$desde' group by psa.idproducto order by g.nombre,s.nombre; ";
	
	$tope = 35;
	$cant = $db->getnumrow($consulta);
	$numero = 0;
	$row = $db->consulta($consulta);
	while($numero < $cant) {
?>


<div style=" position : absolute;left:5%; top:20px;"></div>
<div class='margenprincipal'></div>
<div class='session1_logotipo'><?php echo  "<img src='../$datoGeneral[imagen]' width='200' height='70'/>"; ?></div>

<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1"><?php echo "INVENTARIO DE PRODUCTO";?></td></tr> 
     <tr><td align="center" class="session1_titulo2">
	 <?php echo "Al $fechaF[0] de ".$db->mes($fechaF[1])." de $fechaF[2]";?></td></tr>
  </table>
</div>


<div class="session3_datos">

<table width="100%" border="0">
  <tr>
    <td width="10%" align="right" class="session1_subtitulo1">Almacén:</td>
    <td width="90%" class="session1_subtitulo1"><?php echo $datoAlmacen['nombre'];?></td>
  </tr>
  <tr><td align="right" class="session1_subtitulo1">Grupo:</td>
    <td class="session1_subtitulo1"><?php echo $datoGrupo['nombre'];?></td>
  </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<?php
  setcabecera();
  $subgrupo = "";
  $i = 0;
  while ($data = mysql_fetch_array($row)) {
	  $numero++;
	  $i++;	  	  
	  if (($subgrupo == $data['subgrupo'] || $i == 1) && $numero < $cant && $i < 48) { 	       	      
	      $subgrupo = $data['subgrupo'];
		  if ($i == 1) {
              setDato("normal", $subgrupo, $data['nombre'], $data['total'], $data['conversiones']);    	  
		  } else {
			  setDato("normal", "", $data['nombre'], $data['total'], $data['conversiones']);    	  
		  }
	  } else {		  
		  if ($numero < $cant && $i < 48) {
		      setDato("cierre", $data['subgrupo'], $data['nombre'], $data['total'], $data['conversiones']);    	  
		  } else {
			  $subgrupo = ($data['subgrupo'] != $subgrupo) ? $data['subgrupo'] : "";
		      setDato("final", $subgrupo, $data['nombre'], $data['total'], $data['conversiones']);    	   	  
		  }
		  $subgrupo =  $data['subgrupo'];
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
	$mpdf=new mPDF('utf-8','Letter'); 
	$content = ob_get_clean();
	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit; 
?>