<?php
	ob_start();
	session_start();
	date_default_timezone_set("America/La_Paz");
	include('../MPDF53/mpdf.php');
	include('../conexion.php');
	$db = new MySQL();

	$tituloGeneral = "ESTADO DE CUENTA POR COBRAR";
    $iddeudor = $_GET['iddeudor'];
	$tipoPersona = $_GET['tipo'];
	$hasta = $db->GetFormatofecha($_GET['fecha'], "/");
	
	$fechaFinal = explode("/", $_GET['fecha']);
	
	switch ($tipoPersona) {
	  case "trabajador":
	     $sql = "select left(concat(nombre,' ',apellido),30)as 'nombre' from trabajador where idtrabajador=$iddeudor;";
	  break;
	  case "cliente":
	      $sql = "select left(nombre,30) as 'nombre' from cliente where idcliente=$iddeudor";
	  break;
	  case "proveedor":
	      $sql = "select left(nombre,30)as 'nombre' from proveedor where idproveedor=$iddeudor";
	  break;
	}
	$datoTrabajador = $db->arrayConsulta($sql);
    $sql = "select * from empresa";
	$datoGeneral = $db->arrayConsulta($sql);
	
	
	function setcabecera()
	{
	   echo "<tr >
		  <td width='12%' class='session1_cabecera1'>Fecha</td>
		  <td width='10%' class='session1_cabecera1'>Nº Nota</td>
		  <td width='12%' class='session1_cabecera1'>Importe Original</td>
		  <td width='12%' class='session1_cabecera1'>Aportes</td>
		  <td width='13%' class='session1_cabecera1'>Saldo Actual</td>
		  <td width='41%' class='session1_cabecera2'>Glosa</td>
       </tr>";
	}
		
	
	function setDato($num, $tipo, $fecha, $nroNota, $importe, $aporte, $saldo, $glosa)
	{
	  $clase1 = "";	
	  if ($tipo == "final") {
	      $clase1 = "border-bottom:1.5px solid";		
	  }	
	  if ($tipo == "cerrar") {
	      $clase1 = "border-top:1.5px solid";		
	  }
	  if ($tipo == "cerrar2") {
	      $clase1 = "border-top:1.5px solid;border-bottom:1.5px solid";		
	  }
	  
	  $clase2 = "";
	  if ($num % 2 == 0) {
		  $clase2 = "cebra";
	  }	 
	 
	  echo "<tr class='$clase2'>
	   <td  class='session3_datosF1_1' style='$clase1' align='center'>&nbsp;$fecha</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='center'>&nbsp;$nroNota</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='center'>&nbsp;".number_format($importe, 2)."</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='center'>&nbsp;".number_format($aporte, 2)."</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='center'>".number_format($saldo, 2)."</td>
	   <td  class='session3_datosF1_3' style='$clase1' align='left'>$glosa</td>	  
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
		  <td align='right' class='titulo_1'>Total Saldo:</td>
          <td class='session3_datosF2_Total' align='center'>".number_format($total, 2)."</td>
		  <td >&nbsp;</td>
		</tr>";	
		
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet"  type="text/css" href="style_estadocuentacobrar.css"/>
<title>Reporte Estado de Cuenta</title>
</head>

<body>
<?php
    $subConsulta = "";
    if ($tipoPersona == "cliente") {
	  $subConsulta = "
		 union all	(
	  select nv.numero as 'idtransaccion',nv.fecha,nv.credito as 'monto',0 as 'aporte',
	  left(nv.glosa,45)as 'glosa','VP' as 'transaccion',nv.idnotaventa as 'ordenar',
	  2 as 'nivel',1 as 'suborden' 
	   from notaventa nv where nv.fecha<='$hasta' and nv.credito>0 
	   and nv.estado=1 and nv.tiponota='productos' and nv.idcliente=$iddeudor 
	  ) union all
	  (
	  select i.idingreso as 'idtransaccion',nv.fecha,0 as 'monto',
	  (d.montobolivianos+d.montodolares)as 'aporte',left(i.glosa,45)as 'glosa','ID' as 'transaccion',
	  nv.idnotaventa as 'ordenar',2 as 'nivel',2 as 'suborden' 
	   from ingreso i,detalleingreso d,notaventa nv 
	  where d.idingreso=i.idingreso and i.estado=1 and d.transaccion='Nota Venta Producto' and i.fecha<='$hasta' 
	  and d.idtransaccion=nv.idnotaventa and nv.credito>0 and nv.idcliente=$iddeudor and nv.tiponota='productos'
	  ) union all
	  (
	  select i.nroingresoprod as 'idtransaccion',i.fecha,0 as 'monto',(i.monto)as 'aporte',
	   left(i.glosa,45)as 'glosa', 'IP' as 'transaccion',nv.idnotaventa as 'ordenar',2 as 'nivel'
	   ,3 as 'suborden'  
	   from ingresoproducto i,notaventa nv  
	   where i.facproveedor=nv.numero and nv.credito>0 and nv.estado=1 and nv.tiponota='productos' and 
       i.tipoingreso='NotaVentaProducto' and i.estado=1 and i.fecha<='$hasta' 
	   and i.idpersonarecibida=$iddeudor and i.receptor='$tipoPersona' 
	  )
	    union all
	  (
	  select nv.numero as 'idtransaccion',nv.fecha,nv.credito as 'monto',0 as 'aporte',
	  left(nv.glosa,45)as 'glosa','VS' as 'transaccion',nv.idnotaventa as 'ordenar',
	  3 as 'nivel',1 as 'suborden'  
	   from notaventa nv where nv.fecha<='$hasta' and nv.credito>0 
	   and nv.estado=1 and nv.tiponota='servicios' and nv.idcliente=$iddeudor 
	  ) union all
	  (
	  select i.idingreso as 'idtransaccion',nv.fecha
	  ,0 as 'monto',(d.montobolivianos+d.montodolares)as 'aporte',left(i.glosa,45)as 'glosa', 'ID' as 'transaccion'
	  ,nv.idnotaventa as 'ordenar',3 as 'nivel',2 as 'suborden'
	   from ingreso i,detalleingreso d,notaventa nv 
	  where d.idingreso=i.idingreso and i.estado=1 and d.transaccion='Nota Venta Servicios' and i.fecha<='$hasta'   
	  and d.idtransaccion=nv.idnotaventa and nv.credito>0 and nv.idcliente=$iddeudor and nv.tiponota='servicios'
	  )";	
		
	}
	$consulta = "
	(
	select cc.idporcobrar as 'idtransaccion',cc.fecha,cc.monto,0 as 'aporte',left(cc.glosa,45)as 'glosa',
	'CxC' as 'transaccion',cc.idporcobrar as 'ordenar',1 as 'nivel',1 as 'suborden'
	 from cuentaporcobrar cc where cc.tipodeudor='$tipoPersona' 
	 and cc.estado=1 and cc.fecha<='$hasta' and iddeudor=$iddeudor
	) union all
	(
	select i.idingreso as 'idtransaccion',cc.fecha,0 as 'monto',(d.montobolivianos+d.montodolares)as 'aporte',
	left(i.glosa,45)as 'glosa', 'ID' as 'transaccion',cc.idporcobrar as 'ordenar',1 as 'nivel'
	,2 as 'suborden'  
	 from ingreso i,detalleingreso d,
	cuentaporcobrar cc  
	where d.idingreso=i.idingreso and i.estado=1 and d.transaccion='Cuenta Por Cobrar' and i.fecha<='$hasta' 
	and d.idtransaccion=cc.idporcobrar and cc.iddeudor=$iddeudor and cc.tipodeudor='$tipoPersona' 
	) $subConsulta order by nivel,ordenar,suborden asc;";
	//echo $consulta;
	
	$tope = 49;
	$cant = $db->getnumrow($consulta);
	$numero = 0;
	$row = $db->consulta($consulta);
	$totalGeneral = 0;
	$saldoSession = 0;
	$codigoTransaccion = "";
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
    <td width="11%" align="right" class="session1_subtitulo1"><?php echo ucfirst($tipoPersona);?>:</td>
    <td width="89%" class="session1_subtitulo1"><?php echo $datoTrabajador['nombre'];?></td>
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
	  $fecha = $db->GetFormatofecha($data['fecha'], "-");  
	  $transaccion = $data['transaccion']."-".$data['idtransaccion'];
	  $tipoFila = "normal";
	  $saldoSession = ($data['monto'] - $data['aporte']) + $saldoSession;
	  $totalGeneral = $totalGeneral + ($data['monto'] - $data['aporte']);
	  if ($numero == $cant || $i == $tope) {
		$tipoFila = "final";  
	  }
	  
	  if ($i != 1  && $i != $tope && $codigoTransaccion != $data['ordenar']){
		  $saldoSession = 0;	  
		  $saldoSession = ($data['monto'] - $data['aporte']) + $saldoSession;
		  if ($numero != $cant) {
		      $tipoFila = "cerrar";  
		  } else {
			  $tipoFila = "cerrar2";  
		  }
		  
	  }
	  setDato($i, $tipoFila, $fecha, $transaccion, $data['monto'], $data['aporte'], $saldoSession, $data['glosa']);
	  $codigoTransaccion = $data['ordenar'];
	  
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