<?php
	session_start();
	if (!isset($_SESSION['softLogeoadmin'])) {
	   header("Location: ../index.php");	
	}
	ob_start();
	include("../MPDF53/mpdf.php");	 
	include('../aumentaComa.php');
	include('../conexion.php');
	$db = new MySQL();
	$idsucursal = $_GET['sucursal'];
	$numMes = $_GET['mes'];
	$numAnio = $_GET['anio'];
	 
	 $sql = "select left(e.nombrenit,50) as 'nombrenit',left(e.nit,14)as 'nit'
	 ,left(s.direccion,40)as 'direccion',s.numsucursal from sucursal s,empresa e 
	 where idsucursal=".$idsucursal;
     $consulta = mysql_query($sql);
	 $datos = mysql_fetch_array($consulta);

	 function aumentarDigito($valor)
	 {
		if (strlen($valor) == 1)
		    return "0".$valor;
		return $valor; 
	 }
	 
	function nextPage()
	{
	   for ($m = 1; $m < 55; $m++) {
		   echo "<br />";
	   } 
	}	 
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Imprimir Libro de Ventas</title>

<style>

.negrita{
	font-weight:bold;
	text-align:center;

}

.negro{
 font-weight:bold;	
}

.negrita p {
	font-size: 14px;
}

.punteado{
	font-size:10px;
	border-bottom:1px solid #000;
	border-left: 1px solid #000;
	border-style: dotted;
	
}

.tablacabecera{
	font-weight:bold;
	text-align:center;
	font-size:9px; 
	border-left:1px solid #000;
	border-top:1px solid #000;
	border-bottom:1px solid #000;
}
</style>

</head>

<body>

<p>&nbsp;</p>
<p></p>
<p><br>
 <?php
 
   
 $header = '        	
     <div style="border:1px solid #000; position:absolute; left:84.5%; 
	 text-align:center; font-size:9px; width:4%; top:6.84%;">'.$numAnio.'</div>
     <div style="border:solid 1px #000; position:absolute; left:90.5%; 
	 text-align:center; font-size:9px; width:4%; top:6.84%;">{PAGENO}/{nb}</div>
     <div style="border:solid 0px #000; position:absolute; left:90.5%; 
	 text-align:center; font-size:9px; width:4%; top:4.5%;font-weight:bold;">FOLIO</div>
       <table style="border:0px;width:100%;margin-top:-3%;" >
        <col style="width: 25%">
        <col style="width: 6%">
        <col style="width: 15%">
        <col style="width: 3%">
        <col style="width: 9%">
        <col style="width: 15%">
        <col style="width: 27%">
 <thead>
  <tr>
    <td colspan="8" align="center">      
	   <div style="margin:0 auto;text-align:center;font-size:24px;font-weight:bold;">LIBRO DE VENTAS I.V.A.</div>
	</td>
    </tr>
   </thead> 
  <tr>
    <td width="23%" >&nbsp;</td>
    <td width="3%" >&nbsp;</td>
    <td width="33%" >&nbsp;</td>
    <td width="1%" >&nbsp;</td>
    <td width="15%" >&nbsp;</td>
    <td width="17%" class="negrita">&nbsp;</td>
    <td width="17%" style="text-align:left;font-weight:bold;font-size:9px;">&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PERIODO FISCAL</td>
    <td width="8%" style="font-size:9px;font-weight:bold;" align="center">FOLIO</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>   
      </td>
    <td align="center">{PAGENO}/{nb}</td>
  </tr>
   <tr>
     <td style="font-size:9px; text-align:right;font-weight:bold"> NOMBRE O RAZON SOCIAL:</td>
     <td colspan="2"><div style="border:1px #000;font-size:11px;position:relative">&nbsp;&nbsp;&nbsp;'
	 .$datos[nombrenit].'</div>  </td>
     <td>&nbsp;</td>
     <td style="text-align:right;font-weight:bold;font-size:9px;">NIT.:</td>
     <td><div style="border:1px solid #000;font-size:11px;width:100px;">
	 &nbsp;&nbsp;'.$datos[nit] .'</div></td>
     <td></td>
     <td>&nbsp;</td>
   </tr>
  <tr>
    <td style="font-size:9px;text-align:right;font-weight:bold;">DIRECCION:</td>
    <td colspan="2"><div  style="border:solid 1px #000;font-size:11px;width:240px;">
	&nbsp;&nbsp;&nbsp;&nbsp;'. $datos[direccion].'</div>  <div align="right"></div></td>
    <td>&nbsp;</td>
    <td style="text-align:right;font-weight:bold;font-size:9px;">Nº DE SUCURSAL:</td>
    <td><div style="border:solid 1px #000;font-size:9px;width:100px;">
	&nbsp;&nbsp;&nbsp;&nbsp;'.$datos[numsucursal].'  </div></td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
</table>';

?>

<?php
	$indiceRecorrido = "123";
	$recuentoICE = 0;
	$recuentoExento = 0;
	$recuentoNeto = 0;
	$recuentoDebito = 0;
	$recuentoFactura = 0;
  
	$sql = "select idlibroventasiva,day(fechadeemision)as 'dia',Month(fechadeemision)as 'mes',
			year(fechadeemision)as 'anio',numcinitcliente,left( nomrazonsocicliente,29) 
			as 'nomrazonsocicliente',numfactura,       numautorizacion,codigodecontrol
			,round(totalfactura,2) as 'totalfactura',round(totalice,2)as 'totalice'
			,round(importeexcento,2)as 'importeexcento',round(importeneto,2)as 'importeneto'
			,round(debitofiscal,2)as 'debitofiscal'  from libroventasiva 
			where Month(fechadeemision)=$numMes and folio=$idsucursal and estado=1  
			and year(fechadeemision)=$numAnio  order by numfactura";
	$consulta = mysql_query($sql);
	$contadorFilas = 0;
	$cant = mysql_num_rows($consulta);

    while ($indiceRecorrido != "") {
?>

</p>
<div align="center" style="margin:0 auto;position:absolute;width:90%;top:150px;">
<table style="width:100%;text-align:center " cellspacing="0px">
  <col style="width: 2.5%">
  <col style="width: 2.5%">
  <col style="width: 4%">
  <col style="width: 10%">
  <col style="width: 24%">
  <col style="width: 9%">
  <col style="width: 9%">
  <col style="width: 9%">
  <col style="width: 7%">
  <col style="width: 5%">
  <col style="width: 6%">
  <col style="width: 6%">
  <col style="width: 6%">
<thead>
  <tr>
    <td colspan="3" class="tablacabecera" align="center">FECHA DE EMISION</td>
    <td width="8%"  rowspan="2" class="tablacabecera"  >Nº DE CI / NIT CLIENTE</td>
    <td width="21%"  rowspan="2" class="tablacabecera" >NOMBRE RAZON SOCIAL CLIENTE</td>
    <td width="8%"  rowspan="2" class="tablacabecera"  >Nº DE FACTURA</td>
    <td width="10%"  rowspan="2" class="tablacabecera"  >Nº DE AUTORIZACION</td>
    <td width="9%" rowspan="2" class="tablacabecera"  >CODIGO DE CONTROL</td>
    <td width="8%"  class="tablacabecera"  >TOTAL FACTURA</td>
    <td width="6%"  class="tablacabecera"  >TOTAL I.C.E</td>
    <td width="6%"  class="tablacabecera"  >IMPORTE EXCENTO</td>
    <td width="8%"  class="tablacabecera"  >IMPORTE NETO</td>
    <td width="6%"  class="tablacabecera"  style="border-right:solid 1px #000;">DEBITO FISCAL</td>
  </tr>
 </thead> 
  <tr>
    <td width="4%" class="negrita" style="font-size:9px;border-left:solid 1px #000;border-bottom:solid 1px #000;"> D</td>
    <td width="4%" class="negrita" style="font-size:9px;border-left:solid 1px #000;border-bottom:solid 1px #000;"> M</td>
    <td width="4%" class="negrita" style="font-size:9px;border-left:solid 1px #000;border-bottom:solid 1px #000;">A</td>
    <td class="negrita" style="font-size:9px;border-left:solid 1px #000;border-bottom:solid 1px #000;" >A</td>
    <td class="negrita" style="font-size:9px;border-left:solid 1px #000;border-bottom:solid 1px #000;" >B</td>
    <td class="negrita" style="font-size:9px;border-left:solid 1px #000;border-bottom:solid 1px #000;" >C</td>
    <td class="negrita" style="font-size:9px;border-left:solid 1px #000;border-bottom:solid 1px #000;" >(A-B-C)</td>
    <td class="negrita" style="font-size:9px;border-left:solid 1px #000;border-bottom:solid 1px #000;
    border-right:solid 1px #000;" >I.V.A.</td>
  </tr>
  <?php
  
	$i = 0;		
	while ($dato = mysql_fetch_array($consulta)) {
		$i = $i +1 ;
		$contadorFilas = $contadorFilas + 1;
		$indiceRecorrido = $dato['idlibroventasiva'];	
		$recuentoICE =  $recuentoICE + $dato['totalice'];
		$recuentoExento = $recuentoExento + $dato['importeexcento'];
		$recuentoNeto = $recuentoNeto + $dato['importeneto'];
		$recuentoDebito = $recuentoDebito + $dato['debitofiscal'];
		$recuentoFactura = $recuentoFactura + $dato['totalfactura'];

		
		if ($i == 31 || $contadorFilas == $cant) {
		  echo "<tr >";
		  echo "<td  style='font-size:8px;border-left:solid 1px #000;border-bottom:solid 1px         
		   #000;'>".aumentarDigito($dato['dia'])."</td>";
 		  echo "<td  style='font-size:10px;border-left:solid 1px #000;border-bottom:solid 1px          #000;
		  border-left-style:dotted'>".aumentarDigito($dato['mes'])."</td>";
 		  echo "<td style='font-size:10px;border-left:solid 1px #000;
		  border-bottom:solid 1px #000;border-left-style:dotted'>".$dato['anio']."</td>";
    	  echo "<td style='font-size:10px;border-left:solid 1px #000
		  ;border-bottom:solid 1px #000;border-left-style:dotted'>".$dato['numcinitcliente']."</td>";
    	  echo "<td   style='text-align:left;font-size:10px;border-left:solid 1px #000;
		  border-bottom:solid 1px #000;border-left-style:dotted'>". strtoupper($dato['nomrazonsocicliente'])."</td>";
		  echo "<td style='font-size:10px;border-left:solid 1px #000;
		  border-bottom:solid 1px #000;border-left-style:dotted'>".$dato['numfactura']."</td>";
		  echo "<td style='font-size:10px;border-left:solid 1px #000;
		  border-bottom:solid 1px #000;border-left-style:dotted'>".$dato['numautorizacion']."</td>";
		  echo "<td style='font-size:10px;border-left:solid 1px #000;
		  border-bottom:solid 1px #000;border-left-style:dotted'>".$dato['codigodecontrol']."</td>";		
		  echo "<td style='font-size:10px;border-left:solid 1px #000;border-bottom:solid 1px #000;
		  border-left-style:dotted'>".convertir($dato['totalfactura'])."</td>";				
		  echo "<td style='font-size:10px;border-left:solid 1px #000;border-bottom:solid 1px #000;
		  border-left-style:dotted'>".convertir($dato['totalice'])."</td>";	
		  echo "<td style='font-size:10px;border-left:solid 1px #000;border-bottom:solid 1px #000;
		  border-left-style:dotted'>".convertir($dato['importeexcento'])."</td>";	
		  echo "<td style='font-size:10px;border-left:solid 1px #000;border-bottom:solid 1px #000;
		  border-left-style:dotted'>".convertir($dato['importeneto'])."</td>";	
		  echo "<td style='font-size:10px;border-left:solid 1px #000;border-right:solid 1px #000;
		  border-bottom:solid 1px #000;border-left-style:dotted'>".convertir($dato['debitofiscal'])."</td>";	
		  echo "</tr>";
		break;	
		
		} else {
			echo "<tr >";
			echo "<td  style='font-size:8px;border-left:solid 1px #000;border-bottom:solid 1px #000;
			border-bottom-style:dotted'>".aumentarDigito($dato['dia'])."</td>";
			echo "<td class='punteado'>".aumentarDigito($dato['mes'])."</td>";
			echo "<td class='punteado'>".$dato['anio']."</td>";
			echo "<td class='punteado'>".$dato['numcinitcliente']."</td>";
			echo "<td  class='punteado' style='text-align:left;'>".strtoupper($dato['nomrazonsocicliente'])."</td>";
			echo "<td class='punteado'>".$dato['numfactura']."</td>";
			echo "<td class='punteado'>".$dato['numautorizacion']."</td>";
			echo "<td class='punteado'>".$dato['codigodecontrol']."</td>";		
			echo "<td class='punteado'>".convertir($dato['totalfactura'])."</td>";				
			echo "<td class='punteado'>".convertir($dato['totalice'])."</td>";	
			echo "<td class='punteado'>".convertir($dato['importeexcento'])."</td>";	
			echo "<td class='punteado'>".convertir($dato['importeneto'])."</td>";	
			echo "<td class='punteado' style='border-right:solid 1px #000;'>".convertir($dato['debitofiscal'])."</td>";	
			echo "</tr>";
		}
										
	}
  
  ?>
  
 
</table>
</div>


  <div style="width:4%; font-size:9px; position:absolute;left:82%;top:10%;
  text-align:center; border:1px solid #000;"><?php echo $numAnio;?></div>
  
  <div style="width:7.5%; font-size:9px; position:absolute;left:74.2%;top:10%; 
  text-align:center; border:1px solid #000;"><?php echo mes($numMes);?></div>
  
  <div style="width:7%; font-size:9px; position:absolute;left:88%;top:10%; 
  text-align:center; border:1px solid #000;">&nbsp;&nbsp;</div>
  
  <div style="width:7.5%; font-size:9px; position:absolute;left:62.4%;top:12.3%; 
  text-align:center; border:1px solid #000;">&nbsp;&nbsp;</div>
  
  <div style="width:7.5%; font-size:9px; position:absolute;left:62.4%;top:14.5%; 
  text-align:center; border:1px solid #000;">&nbsp;&nbsp;</div>
  
  <div style="width:24.5%; font-size:9px; position:absolute;left:23.8%;top:12.3%; 
  text-align:center; border:1px solid #000;">&nbsp;&nbsp;</div>
  
  <div style="width:24.5%; font-size:9px; position:absolute;left:23.8%;top:14.5%; 
  text-align:center; border:1px solid #000;">&nbsp;&nbsp;</div>

  <div style="height:70px;width:20%; position:absolute;left:27%;top:87%;border:1px solid #000;">
        <br><br>
            <div style="position:relative;text-align:center;">
            ..........................
            </div>
            <div style="position:relative;text-align:center;font-size:9px;">
            NOMBRE COMPLETO  </div>
  </div>
  
  <div style="height:70px;width:15%; position:absolute;left:8%;top:87%;border:1px solid #000;">
        <br><br>
            <div style="position:relative;text-align:center;">
            ..........................
            </div>
            <div style="position:relative;text-align:center;font-size:9px;">
            C.I. </div>
  </div>

<div style="width:90%;margin:0px auto; position:absolute;top:88%;" align="center">

  <table style="width:100%;text-align:center " cellspacing="0px">
    <col style="width: 2.5%">
    <col style="width: 2.5%">
    <col style="width: 4%">
    <col style="width: 10%">
    <col style="width: 24%">
    <col style="width: 9%">
    <col style="width: 9%">
    <col style="width: 9%">
    <col style="width: 7%">
    <col style="width: 5%">
    <col style="width: 6%">
    <col style="width: 6%">
    <col style="width: 6%">
  <thead>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
    <td width="9%"  rowspan="2"  >&nbsp;</td>
    <td width="11%"  rowspan="2" ></td>
    <td width="3%"  rowspan="2"  >&nbsp;</td>
    <td width="3%"  rowspan="2"  >&nbsp;</td>
    <td width="16%"  align="right"></td>
    <td width="10%" >&nbsp;&nbsp;&nbsp;<strong>Total</strong></td>
    <td width="8%" class="tablacabecera" style="font-size:10px;">
    <?php echo convertir(number_format($recuentoFactura,2));?></td>
    <td width="6%"  class="tablacabecera"  style="font-size:10px;">
	<?php echo convertir(number_format($recuentoICE,2));?></td>
    <td width="6%"  class="tablacabecera"  style="font-size:10px;">
	<?php echo convertir(number_format($recuentoExento,2));?></td>
    <td width="8%"  class="tablacabecera"  style="font-size:10px;">
	<?php echo convertir(number_format($recuentoNeto,2));?></td>
   <td width="6%"  class="tablacabecera"  style="border-right:1px solid #000;font-size:10px;">
   <?php echo convertir(number_format($recuentoDebito,2));?></td>
  </tr>
 </thead> 
  
  <tr>
    <td width="4%" class="negrita" >&nbsp; </td>
    <td width="4%" class="negrita" >&nbsp; </td>
    <td width="6%" class="negrita" >&nbsp;</td>
    <td class="negrita"  >&nbsp;</td>
    <td class="negrita" >&nbsp;</td>
    <td class="negrita" >&nbsp;</td>
    <td class="negrita"  >&nbsp;</td>
    <td class="negrita"  >&nbsp;</td>
  </tr>

</table>

</div>
<?php
	   if ($cant == $contadorFilas) {
	       $indiceRecorrido = "";
	   } else {
	       nextPage();    
	   }
	  
	}
?>

</body>
</html>

<?php
	$mpdf = new mPDF('utf-8','Letter-L'); 
	$content = ob_get_clean();
	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit;
?>
