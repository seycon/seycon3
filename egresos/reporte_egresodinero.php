<?php
	ob_start();
	session_start();
	date_default_timezone_set("America/La_Paz");
	include('../MPDF53/mpdf.php');
	include('../conexion.php');
	$db = new MySQL();

	$tituloGeneral = "REPORTE EGRESO DE DINERO";
	$idtipo = $_GET['tipo'];
	if ($idtipo == "caja") {
	  $sql = "select idtrabajador from cajero where cuentacaja1='$_GET[cuenta]' or cuentacaja2='$_GET[cuenta]' limit 1;";	
	  $trabajador = $db->arrayConsulta($sql);	
	} else {
	  $sql = "select idtrabajador from cajero where cuentabanco1='$_GET[cuenta]' or cuentabanco2='$_GET[cuenta]'
	    or cuentabanco3='$_GET[cuenta]' limit 1;";	
	  $trabajador = $db->arrayConsulta($sql);
	}
    $idtrabajador = $trabajador['idtrabajador'];
	$idcuenta = $_GET['cuenta'];
 	$desde = $db->GetFormatofecha($_GET['desde'], "/");
	$hasta = $db->GetFormatofecha($_GET['hasta'], "/");;
	$tipoCambio = $_GET['moneda'];
	$fechaInicio = explode("/", $_GET['desde']);
	$fechaFinal = explode("/", $_GET['hasta']);
	$sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
	$tcsistema = $db->getCampo('dolarcompra',$sql);
	

	$sql = "select left(concat(nombre,' ',apellido),30)as 'nombre' from trabajador where idtrabajador=$idtrabajador;";
	$datoTrabajador = $db->arrayConsulta($sql);
    $sql = "select left(cuenta,35)as 'nombre' from plandecuenta where codigo='$idcuenta' and estado=1;";
	$datoCuenta = $db->arrayConsulta($sql);
    $sql = "select * from empresa";
	$datoGeneral = $db->arrayConsulta($sql);
	
	
	function setcabecera()
	{
	   echo "<tr >
		  <td width='13%' class='session1_cabecera1'>Fecha</td>
		  <td width='12%' class='session1_cabecera1'>Transacción</td>
		  <td width='11%' class='session1_cabecera1'>Doc</td>
		  <td width='33%' class='session1_cabecera1'>Glosa</td>
		  <td width='18%' class='session1_cabecera1'>Beneficiario</td>
		  <td width='13%' class='session1_cabecera2'>Importe</td>
       </tr>";
	}
		
	
	function setDato($num, $tipo, $fecha, $transaccion, $doc, $glosa, $trabajador , $importe)
	{
	  $clase1 = "";	
	  if ($tipo == "final") {
	      $clase1 = "border-bottom:1.5px solid";		
	  }	
	  $clase2 = "";
	  if ($num % 2 == 0) {
		  $clase2 = "cebra";
	  }	 
	  $glosa = ucfirst(strtolower($glosa));
	  $trabajador = ucfirst(strtolower($trabajador));
	  echo "<tr class='$clase2'>
	   <td  class='session3_datosF1_1' style='$clase1' align='center'>&nbsp;$fecha</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='center'>&nbsp;$transaccion</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='center'>&nbsp;$doc</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$glosa</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$trabajador</td>
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
		  <td  class='session3_datosF2_Total' align='center'>".number_format($total, 2)."</td>
		</tr>";	
		
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet"  type="text/css" href="style_egresodinero.css"/>
<title>Reporte Egreso Dinero</title>
</head>

<body>
<?php
	$consulta = "(select e.fecha,e.idegreso as 'idtransaccion',left(e.nombrepersona,23)as 'nombre',
	e.egresoBolivianos as 'montobolivianos',e.egresoDolares as 'montodolares',
	left(e.recibo,8)as 'recibo',e.tipocambio,left(e.glosa,40)as 'glosa','ED' as 'tipoT' 
	from egreso e where e.estado=1 and e.fecha>='$desde' and 
	e.fecha<='$hasta' and e.cuenta='$idcuenta')
	union all
	
	(select c.fecha,c.idporpagar as 'idtransaccion',left(cl.nombre,23)as 'nombre',
	c.monto as 'montobolivianos',0 as 'montodolares',0 as 'recibo',
	c.tipocambio,left(c.glosa,40)as 'glosa','CxP' as 'tipoT' 
	from cuentaporpagar c,cliente cl where c.cuentacaja='$idcuenta' and 
	c.fecha>='$desde' and c.fecha<='$hasta' and c.estado=1 
	and c.tipodeudor='cliente' and c.iddeudor=cl.idcliente)
	union all
	
	(select c.fecha,c.idporpagar as 'idtransaccion',left(concat(t.nombre,' ',t.apellido),23)as 'nombre',
	c.monto as 'montobolivianos',0 as 'montodolares',0 as 'recibo',
	c.tipocambio,left(c.glosa,40)as 'glosa','CxP' as 'tipoT' 
	from cuentaporpagar c,trabajador t where c.cuentacaja='$idcuenta' and 
	c.fecha>='$desde' and c.fecha<='$hasta' and c.estado=1 
	and c.tipodeudor='trabajador' and c.iddeudor=t.idtrabajador)
	union all
	
	(select c.fecha,c.idporpagar as 'idtransaccion',left(t.nombre,23)as 'nombre',
	c.monto as 'montobolivianos',0 as 'montodolares',0 as 'recibo',
	c.tipocambio,left(c.glosa,40)as 'glosa','CxP' as 'tipoT' 
	from cuentaporpagar c,proveedor t where c.cuentacaja='$idcuenta' and 
	c.fecha>='$desde' and c.fecha<='$hasta' and c.estado=1 
	and c.tipodeudor='proveedor' and c.iddeudor=t.idproveedor)
	union all
	
	(select a.fecha,a.idanticipo as 'idtransaccion',left(concat(t.nombre,' ',t.apellido),23)as 'nombre',
	a.anticipo as 'montobolivianos',0 as 'montodolares',left(a.documento,8) as 'recibo',0 as 'tipocambio',
	left(a.glosa,40)as 'glosa','A' as 'tipoT' 
	from anticipo a,trabajador t where t.idtrabajador=a.idtrabajador and
	a.estado=1 and a.egreso='$idcuenta' and a.fecha>='$desde' and a.fecha<='$hasta')
	union all
	
	(select a.fechacompra as 'fecha',a.idactivo as 'idtransaccion',left(concat(t.nombre,' ',t.apellido),23)as 'nombre',
	(a.precio*a.cantidad)as 'montobolivianos',0 as 'montodolares',0 as 'recibo',0 as 'tipocambio',
	left(a.detalle,40) as 'glosa','AF' as 'tipoT'
	from activo a,trabajador t where t.idtrabajador=a.idtrabajador and 
	a.estado=1 and a.cuenta='$idcuenta' and a.tipocuenta='caja' and 
	a.fechacompra='$desde' and a.fechacompra<='$hasta' and 
	a.estado=1)
	union all
	
	(select i.fecha,i.nroingresoprod as 'idtransaccion',left(i.nombreasignado,23)as 'nombre',
	i.monto as 'montobolivianos',0 as 'montodolares',left(i.facproveedor,8) as 'recibo',0 as 'tipocambio',
	left(i.glosa,40)as 'glosa','IP' as 'tipoT'  from ingresoproducto i
	where i.caja='$idcuenta' and i.fecha>='$desde' and i.fecha<='$hasta' and 
	i.estado=1);";
	
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
	 <?php echo "Del $fechaInicio[0] de ".$db->mes($fechaInicio[1])." del "
	 ." $fechaInicio[2] al $fechaFinal[0] de".$db->mes($fechaFinal[1])." del $fechaFinal[2]"    ;?></td></tr>    
     <tr><td align="center" class="session1_titulo2"><?php echo "(Expresado en $tipoCambio)";?></td></tr>  
  </table>
</div>


<div class="session3_datos">

<table width="100%" border="0">
  <tr>
    <td width="11%" align="right" class="session1_subtitulo1">Trabajador:</td>
    <td width="89%" class="session1_subtitulo1"><?php echo $datoTrabajador['nombre'];?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo1">
    <?php 
	if ($idtipo == "caja") {
	    echo "Caja";
	} else {
	    echo "Banco";	
	}
	?>:</td>
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
		  if ($data['tipocambio'] == 0){
			  $tc = $tcsistema;  
		  } else {
			  $tc = $data['tipocambio'];
		  }	      
	  }  
	  $fecha = $db->GetFormatofecha($data['fecha'], "-");
	  $transaccion = $data['tipoT']."-".$data['idtransaccion'];
	  $importe = ($data['montobolivianos'] + $data['montodolares']) / $tc;
	  $totalGeneral = $totalGeneral + $importe;
	  $tipoFila = "normal";	  
	  if ($numero == $cant || $i == $tope) {
		$tipoFila = "final";  
	  }
	  setDato($i, $tipoFila, $fecha, $transaccion, $data['recibo'], $data['glosa'], $data['nombre'], $importe);
	  
	  
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