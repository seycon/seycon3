<?php
	ob_start();
	session_start();
	include('../../MPDF53/mpdf.php');
	include('../../conexion.php');
	$db = new MySQL();

    $fechaGeneral = explode("/", $_GET['fecha']);
	$datosFecha = explode("/", $_GET['fecha']);
	$fechaGeneral = $fechaGeneral[2]."/".$fechaGeneral[1]."/".$fechaGeneral[0];
	$idsucursal = $_GET['sucursal'];	

	$sql = "select imagen,left(nombrecomercial,13)as 'nombrecomercial',nit from empresa;";
	$datoGeneral = $db->arrayConsulta($sql);
    $sql = "select nombrecomercial from sucursal where idsucursal=$idsucursal;";
	$datoSucursal = $db->arrayConsulta($sql);
	
	function setcabecera()
	{
		echo "<tr >
      <td colspan='2' >&nbsp;</td>
      <td colspan='2' class='session1_cabecera5'>Creditos</td>
      <td class='session1_cabecera6'>&nbsp;</td>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
    </tr>
    <tr >
    <td width='5%' class='session1_cabecera1'>Nº</td>
    <td width='18%' class='session1_cabecera1'>Garzón</td>
    <td width='21%' class='session1_cabecera1'>Nombre</td>
    <td width='10%' class='session1_cabecera1'>Bs.</td>
    <td width='11%' class='session1_cabecera1'>Nulos</td>
    <td width='12%' class='session1_cabecera1'>Faltante</td>
    <td width='11%' class='session1_cabecera1'>Entrego</td>
    <td width='12%' class='session1_cabecera2'>Ventas</td>
    </tr>";
	}
		
	
	function pie($clase)
	{
	  echo "<div class='$clase'> 
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


	function setTotalF($total)
	{
		$clase1 = "border-top:1.5px solid";
	    echo "
		<tr >
		  <td align='left' style='$clase1'>&nbsp;</td>
		  <td align='left' style='$clase1'>&nbsp;</td>
		  <td align='left' style='$clase1'>&nbsp;</td>
		  <td  class='session3_datosF1_Total' style='$clase1' align='center'>".number_format($total[0] ,2)."</td>
		  <td  class='session3_datosF1_Total' style='$clase1' align='center'>".number_format($total[1], 2)."</td>
		  <td  class='session3_datosF1_Total' style='$clase1' align='center'>".number_format($total[2], 2)."</td>
		  <td  class='session3_datosF1_Total' style='$clase1' align='center'>".number_format($total[3], 2)."</td>
		  <td  class='session3_datosF2_Total' style='$clase1' align='center'>".number_format($total[4], 2)."</td>
		</tr>";	
		
	}

	function setDato($tipo, $nro, $garzon, $nombre, $montoCredito , $nulos, $faltante, $acumulado, $efectivo, $tipoG)
	{
	 $clase1 = "";	
	 if ($tipo == "cierre") {
		 if ($nro > 1) {
	     $clase1 = "border-top:1.5px solid";
		 }
	 }	
	  if ($tipo == "final") {
		 if (trim($garzon) == "") {
			 $clase1 = "border-bottom:1.5px solid";
		 } else {			 
	         $clase1 = "border-top:1.5px solid;border-bottom:1.5px solid";			
		 }
	 }		 
	 
	 $tipoGarzon = $tipoG;
	 if ($tipoGarzon != ""){
		$tipoGarzon = $tipoGarzon." - ";  
	 }
	 
	 echo "<tr >
	  <td  class='session3_datosF1_1' style='$clase1' align='left'>&nbsp;$nro</td>
	  <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$tipoGarzon$garzon</td>
	  <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$nombre</td>
	  <td  class='session3_datosF1_2' style='$clase1' align='center'>".number_format($montoCredito ,2)."</td>
	  <td  class='session3_datosF1_2' style='$clase1' align='center'>".number_format($nulos, 2)."</td>
	  <td  class='session3_datosF1_2' style='$clase1' align='center'>".number_format($faltante, 2)."</td>
	  <td  class='session3_datosF1_2' style='$clase1' >".number_format($acumulado, 2)."</td>
	  <td  class='session3_datosF1_3' style='$clase1' >".number_format($efectivo, 2)."</td>
	 </tr>";	
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet"  type="text/css" href="style_ventas.css"/>
<!--La linea anterior incluye el archivo .css-->
<title>Reporte de Ventas por Sucursal</title>
</head>

<body>
<?php
	$consulta = "(select left(concat(t.nombre,' ',t.apellido),17) as 'garzon',
	(select round(a.efectivo,4) from atencion a where a.credito=1 and socio=0 
		and a.idatencion=ap.idatencion and a.idsucursal=$idsucursal)as 'montocredito',
	(select left(concat(t.nombre,' ',t.apellido),25) from atencion a,trabajador t
	 where a.credito=1 and socio=0 and a.idsucursal=$idsucursal 
		and a.idatencion=ap.idatencion and a.idtrabajador=t.idtrabajador )as 'nombrerecibido',
	(
	  select sum(precio*cantidad) from atencion a,detalleatencion d 
	  where d.idatencion=a.idatencion and d.estado=0 
	  and date(fecha)=date(ap.fecha) and a.estado='cobrado' 
	  and idusuariorestaurante=up.idusuario and a.idsucursal=$idsucursal 
	  group by idusuariorestaurante  
	  ) as 'nulos',
	(
	 select sum(cortesia) from atencion where credito=0 and socio=0 and idsucursal=$idsucursal 
	 and date(fecha)=date(ap.fecha) and idusuariorestaurante=up.idusuario and estado='cobrado'  
	 group by  idusuariorestaurante 
	)as 'cortesia',
	(
	 select round(sum(efectivo),4) from atencion where idusuariorestaurante=up.idusuario
	 and estado='cobrado' and date(fecha)=date(ap.fecha) and idsucursal=$idsucursal and 
	 credito=0 and socio=0 group by idusuariorestaurante
	)as 'efectivo',
	(
	 select sum(acumulado-monto)  from entregadinero where fecha=date(ap.fecha) and 
	idtrabajador=up.idtrabajador and estado=1 and tipo='fijo' and (acumulado-monto)>0 
	and idsucursal=$idsucursal group by fecha
	)as 'faltante',	
	(
	 select sum(monto)  from entregadinero where fecha=date(ap.fecha) and 
	idtrabajador=up.idtrabajador and estado=1 and tipo='fijo' and idsucursal=$idsucursal group by fecha
	)as 'acumulado',
	t.idtrabajador as 'idpersonal','F' as 'tipoGarzon'  
	  
	from atencion ap,usuariorestaurante up,trabajador t
	where ap.idusuariorestaurante=up.idusuario 
	and t.idtrabajador=up.idtrabajador 
	and date(ap.fecha)='$fechaGeneral' and up.tipo='fijo' 
	and ap.idsucursal=$idsucursal and ap.estado='cobrado' order by t.idtrabajador)
	union all(


    select left(concat(t.nombre,' ',t.apellido),17) as 'garzon',
	(select round(a.efectivo,4) from atencion a where a.credito=1 and socio=0 
		and a.idatencion=ap.idatencion)as 'montocredito',
	(select left(concat(t.nombre,' ',t.apellido),25) from atencion a,trabajador t
	 where a.credito=1 and socio=0 
		and a.idatencion=ap.idatencion and a.idtrabajador=t.idtrabajador )as 'nombrerecibido',
	(
	  select sum(precio*cantidad) from atencion a,detalleatencion d 
	  where d.idatencion=a.idatencion and d.estado=0 
	  and date(fecha)=date(ap.fecha) and a.estado='cobrado'
	  and idusuariorestaurante=up.idusuario and a.idsucursal=$idsucursal 
	  group by idusuariorestaurante  
	  ) as 'nulos',
	(
	 select sum(cortesia) from atencion where credito=0 and socio=0 and idsucursal=$idsucursal 
	 and date(fecha)=date(ap.fecha) and idusuariorestaurante=up.idusuario  and estado='cobrado' 
	 group by  idusuariorestaurante 
	)as 'cortesia',
	(
	 select round(sum(efectivo),4) from atencion where idusuariorestaurante=up.idusuario
	 and estado='cobrado' and date(fecha)=date(ap.fecha) and idsucursal=$idsucursal and 
	 credito=0 and socio=0 group by idusuariorestaurante
	)as 'efectivo',
	(
	 select sum(acumulado-monto)  from entregadinero where fecha=date(ap.fecha) and 
	idtrabajador=up.idtrabajador and estado=1 and tipo='apoyo' 
	and idsucursal=$idsucursal and (acumulado-monto)>0 group by fecha
	)as 'faltante',
	(
	 select sum(monto)  from entregadinero where fecha=date(ap.fecha) and 
	idtrabajador=up.idtrabajador and estado=1 and idsucursal=$idsucursal and tipo='apoyo' group by fecha
	)as 'acumulado',
	
	t.idpersonalapoyo as 'idpersonal','A' as 'tipoGarzon'  	  
	from atencion ap,usuariorestaurante up,personalapoyo t
	where ap.idusuariorestaurante=up.idusuario 
	and t.idpersonalapoyo=up.idtrabajador 
	and date(ap.fecha)='$fechaGeneral' and up.tipo='apoyo' 
	and ap.idsucursal=$idsucursal and ap.estado='cobrado' order by t.idpersonalapoyo)order by idpersonal;";
	
	$cant = $db->getnumrow($consulta);
	
	if ($cant > 9) {
	    $clase = "borde";	
		$clasePie = "session4_pie";
	} else {
		$clase = "borde2";
		$clasePie = "session4_pie2";
	}
	
	
	$numero = 0;
	$row = $db->consulta($consulta);
	$subtotal = 0;
	$totalGeneral = array(0, 0, 0, 0, 0);
	while($numero < $cant) {
?>


<div style=" position : absolute;left:5%; top:20px;"></div>
<div class='<?php echo $clase;?>'></div>

<div class='session1_logotipo'><?php echo  "<img src='../../$datoGeneral[imagen]' width='200' height='70'/>"; ?></div>

<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1"><?php echo "VENTAS POR SUCURSAL";?></td></tr>  
     <tr><td align="center" class="session1_titulo2"><?php echo "del $datosFecha[0] de ".$db->mes($datosFecha[1])." del "
	 ." $datosFecha[2]"  ;?></td></tr>     
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
  $item = 0;
  while ($data = mysql_fetch_array($row)) {
	  $numero++;
	  $i++;	  	  
	  
	  if (($nota == $data['garzon'] || $i == 1) && $numero < $cant && $i < 46) { 	       	      
	      $nota = $data['garzon'];
		  if ($i == 1) {
			$item++;  
			setDato("normal", $item, $data['garzon'], $data['nombrerecibido'], $data['montocredito'] 
			, $data['nulos'], $data['faltante'], $data['acumulado'], $data['efectivo'], $data['tipoGarzon']);  
			$totalGeneral[0] = $totalGeneral[0] + $data['montocredito'];
			$totalGeneral[1] = $totalGeneral[1] + $data['nulos'];
			$totalGeneral[2] = $totalGeneral[2] + $data['faltante'];
			$totalGeneral[3] = $totalGeneral[3] + $data['acumulado'];
			$totalGeneral[4] = $totalGeneral[4] + $data['efectivo'];
		  } else {
			  if ($data['nombrerecibido'] != "") {
				  $item++;
				setDato("normal", $item, "", $data['nombrerecibido'], $data['montocredito'] 
				, 0, 0, 0, 0, ""); 
				$totalGeneral[0] = $totalGeneral[0] + $data['montocredito']; 
			  }
		  }
	  } else {
		  $i++;			  
		  if ($numero < $cant && $i < 46) {
			  //setTotal($subtotal);
			  $item++;
			  setDato("cierre", $item, $data['garzon'], $data['nombrerecibido'], $data['montocredito'] 
			  , $data['nulos'], $data['faltante'], $data['acumulado'], $data['efectivo'], $data['tipoGarzon']);
			  $totalGeneral[0] = $totalGeneral[0] + $data['montocredito'];
			  $totalGeneral[1] = $totalGeneral[1] + $data['nulos'];
			  $totalGeneral[2] = $totalGeneral[2] + $data['faltante'];
			  $totalGeneral[3] = $totalGeneral[3] + $data['acumulado'];
			  $totalGeneral[4] = $totalGeneral[4] + $data['efectivo'];
			  
			  
		  } else {
			  $nota = ($data['garzon'] != $nota) ? $data['garzon'] : "";
			  if ($nota != "") {
			  $totalGeneral[0] = $totalGeneral[0] + $data['montocredito'];
			  $totalGeneral[1] = $totalGeneral[1] + $data['nulos'];
			  $totalGeneral[2] = $totalGeneral[2] + $data['faltante'];
			  $totalGeneral[3] = $totalGeneral[3] + $data['acumulado'];
			  $totalGeneral[4] = $totalGeneral[4] + $data['efectivo'];
			  }
			  
			  if ($nota != "" || $data['nombrerecibido'] != ""){
			      $item++;	 
				  if ($nota != "") { 
				  setDato("cierre", $item, $nota, $data['nombrerecibido'], $data['montocredito'] 
				, $data['nulos'], $data['faltante'], $data['acumulado'], $data['efectivo'], $data['tipoGarzon']);		  			  
				  } else {
					 setDato("normal", $item, $nota, $data['nombrerecibido'], $data['montocredito'], 0, 0, 0, 0, "");	 
				  }
				  $totalGeneral[0] = $totalGeneral[0] + $data['montocredito']; 
			  }
			  setTotalF($totalGeneral);		  
			  	  
		  }
		  $nota =  $data['garzon'];
	  }
	  if ($i > 45) 
	      break;
	  	  
  }
?>
</table>
</div>
<?php
   pie($clasePie);
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
	$mpdf = new mPDF('utf-8','Letter'); 
	$content = ob_get_clean();
	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit; 
?>