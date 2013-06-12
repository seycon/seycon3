<?php
	ob_start();
	session_start();
	date_default_timezone_set("America/La_Paz");
	include('../MPDF53/mpdf.php');
	include('../conexion.php');
	$db = new MySQL();

	$tituloGeneral = "REPORTE DE EGRESOS POR BENEFICIARIO";
	$beneficiario = $_GET['idbeneficiario'];
	$tipoBeneficiario = $_GET['tipobeneficiario'];
	
	$idcuenta = $_GET['cuenta'];
 	$desde = $db->GetFormatofecha($_GET['desde'], "/");
	$hasta = $db->GetFormatofecha($_GET['hasta'], "/");
	$tipoCambio = $_GET['moneda'];
	$fechaInicio = explode("/", $_GET['desde']);
	$fechaFinal = explode("/", $_GET['hasta']);
	
	
    switch($tipoBeneficiario) {
	  case "cliente":
    	$sql = "select left(nombre,30)as 'nombre' from cliente where idcliente=$beneficiario;";
	  break;
	  case "trabajador":
       	$sql = "select left(concat(nombre,' ',apellido),30)as 'nombre' from trabajador where idtrabajador=$beneficiario;";
	  break;
	  case "proveedor":
      	$sql = "select left(nombre,30)as 'nombre' from proveedor where idproveedor=$beneficiario;";
	  break;	
		
	}
	$datoPersona = $db->arrayConsulta($sql);
	
	
    $sql = "select left(cuenta,35)as 'nombre' from plandecuenta where codigo='$idcuenta' and estado=1;";
	$datoCuenta = $db->arrayConsulta($sql);
    $sql = "select * from empresa";
	$datoGeneral = $db->arrayConsulta($sql);
	
	
	function setcabecera()
	{
	   echo "<tr >
		  <td width='10%' class='session1_cabecera1'>Fecha</td>
		  <td width='15%' class='session1_cabecera1'>Transacción</td>
		  <td width='11%' class='session1_cabecera1'>Doc</td>
		  <td width='15%' class='session1_cabecera1'>Sucursal</td>
		  <td width='29%' class='session1_cabecera1'>Descripción</td>
		  <td width='18%' class='session1_cabecera1'>Cuenta Contable</td>
		  <td width='13%' class='session1_cabecera2'>Importe</td>
       </tr>";
	}
		
	
	function setDato($num, $tipo, $fecha, $transaccion, $doc, $sucursal, $glosa, $cuenta, $importe)
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
	   <td  class='session3_datosF1_2' style='$clase1' align='center'>&nbsp;$transaccion</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$doc</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$sucursal</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$glosa</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$cuenta</td>
	   <td  class='session3_datosF1_3' style='$clase1' align='center'>".number_format($importe, 2)."</td>	  
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
		  <td >&nbsp;</td>
          <td >&nbsp;</td>
		  <td  class='session3_datosF2_Total' align='center'>".number_format($total, 2)."</td>
		</tr>";	
		
	}
	
	function getTransaccion($transaccion, $idegreso, $idtransaccion)
	{
	  switch($transaccion){
		case "Egreso Dinero":
		  $transaccion = "ED-$idegreso";
		break;  
		case "Cuenta Por Pagar":
		  $transaccion = "CXP-$idtransaccion";
		break;
		case "Ingreso Producto":
		  $transaccion = "IP-$idtransaccion";
		break;  
	  }
	  return $transaccion;
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet"  type="text/css" href="style_egresocliente.css"/>
<title>Reporte Egreso de Dinero</title>
</head>

<body>
<?php
	$consulta = "
	select i.fecha,di.transaccion,left(i.recibo,10)as 'recibo',left(s.nombrecomercial,15)as 'sucursal'
	,di.montobolivianos,di.montodolares,i.tipocambio,di.idtransaccion,i.idegreso
        ,left(di.descripcion,30)as 'descripcion',left(pc.cuenta,20)as 'cuenta'   
	 from egreso i,detalleegreso di,sucursal s,plandecuenta pc 
         where i.idegreso=di.idegreso
         and pc.codigo=di.idcuenta  
         and pc.estado=1 
         and s.idsucursal=i.idsucursal  
         and i.idpersona = $beneficiario 
         and i.tipopersona = '$tipoBeneficiario' 
         and i.cuenta='$idcuenta' 
         and i.fecha>='$desde' 
         and i.fecha<='$hasta' 
         and i.estado=1;";
	
	$tope = 49;
	$cant = $db->getnumrow($consulta);
	$numero = 0;
	$row = $db->consulta($consulta);
	$totalGeneral = 0;
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
     <tr><td align="center" class="session1_titulo2"><?php echo "(Expresado en $tipoCambio)";?></td></tr>  
  </table>
</div>


<div class="session3_datos">

<table width="100%" border="0">
  <tr>
    <td width="11%" align="right" class="session1_subtitulo1"><?php echo ucfirst($tipoBeneficiario);?>:</td>
    <td width="89%" class="session1_subtitulo1"><?php echo ucfirst($datoPersona['nombre']);?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo1">
    <?php echo "Caja/Banco";?>:</td>
    <td class="session1_subtitulo1"><?php echo $datoCuenta['nombre'];?></td>
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
	  $tc = 1;
	  if ($tipoCambio == "Dolares") {
		  $tc = $data['tipocambio'];
	  }
	  $fecha = $db->GetFormatofecha($data['fecha'], "-");	  
	  $importe = ($data['montobolivianos'] + $data['montodolares']) / $tc;
	  $totalGeneral = $totalGeneral + $importe;
	  $tipoFila = "normal";
	  if ($numero == $cant || $i == $tope) {
		$tipoFila = "final";  
	  }
	  $transaccion = getTransaccion($data['transaccion'], $data['idegreso'], $data['idtransaccion']);
	  setDato($i, $tipoFila, $fecha, $transaccion, $data['recibo'], $data['sucursal']
	  , $data['descripcion'], $data['cuenta'], $importe);
	  
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