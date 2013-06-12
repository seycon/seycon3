<?php
	ob_start();
	session_start();
	date_default_timezone_set("America/La_Paz");
	include('../MPDF53/mpdf.php');
	include('../conexion.php');
	$db = new MySQL();

	$tituloGeneral = "RESUMEN DE CUENTA POR PAGAR";
	$tipoPersona = $_GET['tipodeudor'];	
	$hasta = $_GET['fecha'];
	
	$fechaFinal = explode("/", $_GET['fecha']);
	
    $sql = "select * from empresa";
	$datoGeneral = $db->arrayConsulta($sql);
	
	
	function setcabecera()
	{
	   echo "<tr >
		  <td width='35%' class='session1_cabecera1'>Deudor</td>
		  <td width='12%' class='session1_cabecera1'>Fecha</td>
		  <td width='10%' class='session1_cabecera1'>Nº Nota</td>
		  <td width='14%' class='session1_cabecera1'>Importe Original</td>
		  <td width='13%' class='session1_cabecera1'>Aportes</td>
		  <td width='16%' class='session1_cabecera2'>Saldo Actual</td>
       </tr>";
	}
		
	
	function setDato($num, $tipo, $deudor, $fecha, $nroNota, $importe, $aporte, $saldo)
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
	   <td  class='session3_datosF1_1' style='$clase1' align='left'>&nbsp;$deudor</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='center'>&nbsp;$fecha</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='center'>&nbsp;$nroNota</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='center'>".number_format($importe, 2)."</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='center'>".number_format($aporte, 2)."</td>
	   <td  class='session3_datosF1_3' style='$clase1' align='center'>".number_format($saldo, 2)."</td>	  
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


	function setTotalF($total)
	{
		$clase1 = "border-top:1.5px solid";
	    echo "
		<tr >
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
		  <td align='right' class='titulo_1'>Total Saldo:</td>
          <td class='session3_datosF2_Total' align='center'>".number_format($total, 2)."</td>		  
		</tr>";	
		
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet"  type="text/css" href="style_resumenporpagar.css"/>
<title>Reporte Resumen de cuenta por pagar</title>
</head>

<body>
<?php

 switch ($tipoPersona) {
	 case "cliente":
	    $subconsulta = "(select cp.idporpagar as 'idtransaccion',cp.fecha,cp.monto,left(c.nombre,25) as 'nombre',
		  (select  sum(d.montobolivianos+d.montodolares) as 'monto' from 
		  detalleegreso d,egreso e where d.idegreso=e.idegreso and e.estado=1 and  
		  d.transaccion='Cuenta Por Pagar' and d.idtransaccion=cp.idporpagar group by d.idtransaccion)as 'aporte'
		  ,'CxP' as 'transaccion'    
		   from cuentaporpagar cp,cliente c where 
		  cp.tipodeudor='cliente' and c.idcliente=cp.iddeudor and cp.estado=1 and cp.fecha<='$hasta')  
		  ";
	 break;
	 case "trabajador":
	    $subconsulta = "(select cp.idporpagar as 'idtransaccion',cp.fecha
		,cp.monto,left(concat(c.nombre,' ',c.apellido),25) as 'nombre',
		  (select  sum(d.montobolivianos+d.montodolares) as 'monto' from 
		  detalleegreso d,egreso e where d.idegreso=e.idegreso and e.estado=1 and  
		  d.transaccion='Cuenta Por Pagar' and d.idtransaccion=cp.idporpagar group by d.idtransaccion)as 'aporte'
		  ,'CxP' as 'transaccion'    
		   from cuentaporpagar cp,trabajador c where 
		  cp.tipodeudor='trabajador' and c.idtrabajador=cp.iddeudor and cp.estado=1 and cp.fecha<='$hasta')
		  ";
	 break;
	 case "proveedor":
	    $subconsulta = "(select cp.idporpagar as 'idtransaccion',cp.fecha,cp.monto,left(c.nombre,25) as 'nombre',
		(select  sum(d.montobolivianos+d.montodolares) as 'monto' from 
		detalleegreso d,egreso e where d.idegreso=e.idegreso and e.estado=1 and  
		d.transaccion='Cuenta Por Pagar' and d.idtransaccion=cp.idporpagar group by d.idtransaccion)as 'aporte'
		,'CxP' as 'transaccion'    
		 from cuentaporpagar cp,proveedor c where 
		cp.tipodeudor='proveedor' and c.idproveedor=cp.iddeudor and cp.estado=1 and cp.fecha<='$hasta')";
	 break;
 }

	$consulta = "$subconsulta  union all(select i.idingresoprod as 'idtransaccion',i.fecha,i.diascredito as 'monto'   
	,left(i.nombreasignado,25)as 'nombre',
	(
	select  sum(d.montobolivianos+d.montodolares) as 'monto' from 
	detalleegreso d,egreso e where d.idegreso=e.idegreso and e.estado=1 and  
	d.transaccion='Ingreso Producto' and d.idtransaccion=i.idingresoprod group by d.idtransaccion
	)as 'aporte','IP' as 'transaccion' 
	from ingresoproducto i where i.fecha<='$hasta' and i.receptor='$tipoPersona'
	and i.estado=1) order by fecha;
	";
	
	$tope = 49;
	$cant = $db->getnumrow($consulta);
	$numero = 0;
	$row = $db->consulta($consulta);
    $totalGeneral = 0;
	while($numero < $cant) {
?>


<div style=" position : absolute;left:5%; top:20px;"></div>
<div class='margenprincipal'></div>
<div class='session1_logotipo'><?php echo  "<img src='../$datoGeneral[imagen]' width='200' height='70'/>"; ?></div>

<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1"><?php echo $tituloGeneral;?></td></tr>  
     <tr><td align="center" class="session1_titulo2">
	 <?php echo "Al $fechaFinal[0] de ".$db->mes($fechaFinal[1])." del $fechaFinal[2]";?></td></tr>    
     <tr><td align="center" class="session1_titulo2"><?php echo "(Expresado en Bolivianos)";?></td></tr>  
  </table>
</div>


<div class="session3_datos">

<table width="100%" border="0">
  <tr>
    <td width="15%" align="right" class="session1_subtitulo1"><?php echo "Tipo de Deudor";?>:</td>
    <td width="85%" class="session1_subtitulo1"><?php echo ucfirst($tipoPersona);?></td>
  </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<?php
  setcabecera();
  $nota = "";
  $i = 0;

  while ($data = mysql_fetch_array($row)) {
	  $numero++;
	  $i++;	  	  
	  $tipoFila = "normal";
	  $fecha = $db->GetFormatofecha($data['fecha'], "-");
	  $codigo = $data['transaccion']."-".$data['idtransaccion'];
	  $saldo = $data['monto'] - $data['aporte'];
	  $totalGeneral = $totalGeneral + $saldo;
	  if ($numero == $cant || $i == $tope) {
		$tipoFila = "final";  
	  }
	  setDato($i, $tipoFila, $data['nombre'], $fecha, $codigo, $data['monto'], $data['aporte'], $saldo);
	  	  
	  if ($i == $tope) 
	      break;
	  	  
  }
  setTotalF($totalGeneral);

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
	$mpdf = new mPDF('utf-8','Letter'); 
	$content = ob_get_clean();
	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit; 
?>