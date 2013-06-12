<?php
	ob_start();
	session_start();
	date_default_timezone_set("America/La_Paz");
	include('../MPDF53/mpdf.php');
	include('../conexion.php');
	$db = new MySQL();

	$tituloGeneral = "REPORTE DE MOVIMIENTO DE DINERO";
    $idtipo = $_GET['tipo'];
	$idcuenta = $_GET['cuenta'];
	if ($idtipo == "caja") {
	  $sql = "select idtrabajador from cajero where cuentacaja1='$idcuenta' or cuentacaja2='$idcuenta' limit 1;";	
	  $trabajador = $db->arrayConsulta($sql);	
	} else {
	  $sql = "select idtrabajador from cajero where cuentabanco1='$idcuenta' or cuentabanco2='$idcuenta'
	    or cuentabanco3='$idcuenta' limit 1;";	
	  $trabajador = $db->arrayConsulta($sql);
	}
	$idtrabajador = $trabajador['idtrabajador'];
	$desde = $db->GetFormatofecha($_GET['desde'], "/");
	$hasta = $db->GetFormatofecha($_GET['hasta'], "/");
	
	$fechaInicio = explode("/", $_GET['desde']);
	$fechaFinal = explode("/", $_GET['hasta']);
	
	

	$sql = "select left(concat(nombre,' ',apellido),30)as 'nombre' from trabajador where idtrabajador=$idtrabajador;";
	$datoTrabajador = $db->arrayConsulta($sql);
    $sql = "select left(cuenta,35)as 'nombre' from plandecuenta where codigo='$idcuenta' and estado=1;";
	$datoCuenta = $db->arrayConsulta($sql);
    $sql = "select * from empresa";
	$datoGeneral = $db->arrayConsulta($sql);
	
	
	function setcabecera()
	{
	   echo "<tr >
		  <td width='11%' class='session1_cabecera1'>Fecha</td>
		  <td width='10%' class='session1_cabecera1'>Transacción</td>
		  <td width='9%' class='session1_cabecera1'>Doc</td>
		  <td width='29%' class='session1_cabecera1'>Glosa</td>
		  <td width='14%' class='session1_cabecera1'>Beneficiario</td>
		  <td width='9%' class='session1_cabecera1'>Ingresos</td>
  		  <td width='9%' class='session1_cabecera1'>Egresos</td>
  		  <td width='9%' class='session1_cabecera2'>Saldo</td>
       </tr>";
	}
		
	
	function setDato($num, $tipo, $fecha, $transaccion, $doc, $glosa, $beneficiario, $ingresos, $egresos, $saldo)
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
	  $beneficiario =  ucfirst(strtolower($beneficiario));
	  echo "<tr class='$clase2'>
	   <td  class='session3_datosF1_1' style='$clase1' align='center'>&nbsp;$fecha</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='center'>&nbsp;$transaccion</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$doc</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$glosa</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$beneficiario</td>
	   <td  class='session3_datosF1_2' style='$clase1' align='center'>".number_format($ingresos, 2)."</td>	
	   <td  class='session3_datosF1_2' style='$clase1' align='center'>".number_format($egresos, 2)."</td>	
	   <td  class='session3_datosF1_3' style='$clase1' align='center'>".number_format($saldo, 2)."</td>
	  </tr>";	
	}	
	
	function setAnteriorSaldo($saldo) {
		$clase1 = "";
	   echo "<tr>
	   <td  class='session3_datosF1_1' colspan='7' style='$clase1' align='right'><b>Saldo Anterior:</b></td>	
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


	function setTotalF($total, $saldo)
	{
		$clase1 = "border-top:1.5px solid";
	    echo "
		<tr >
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
          <td >&nbsp;</td>
		  <td  class='session3_datosF1_Total' align='center'>".number_format($total[0], 2)."</td>
 	      <td  class='session3_datosF1_Total' align='center'>".number_format($total[1], 2)."</td>
		  <td  class='session3_datosF2_Total' align='center'>".number_format($saldo, 2)."</td>
		</tr>";	
		
	}
	
	function getConsultaTotal($idcuenta) {
	  return "select(
	  coalesce((select -sum(a.anticipo) as 'egresos' 
	  from anticipo a,trabajador t where t.idtrabajador=a.idtrabajador and
	  a.estado=1 and a.egreso='$idcuenta' and a.fecha<'$desde' group by a.egreso),0)
	  +
	  coalesce((select sum(i.ingresoBolivianos+i.ingresoDolares)as 'ingresos'
	   from ingreso i where i.cuenta='$idcuenta' and 
	   i.fecha<'$desde' and i.estado=1 group by i.cuenta),0)
	  +
	  coalesce((select -sum(e.egresoBolivianos+e.egresoDolares) as 'egresos' 
	  from egreso e where e.estado=1  and 
	  e.fecha<'$desde' and e.cuenta='$idcuenta' group by e.cuenta),0)
	  +
	  coalesce((select sum(d.montobolivianos+d.montodolares)as 'ingresos'
	   from detalletraspasodinero d,traspasodinero t 
	  where t.idtraspaso=d.idtraspaso 
	   and t.fecha<'$desde' and t.estado=1 and d.idcuenta='$idcuenta.' group by d.idcuenta),0)
	  +
	  coalesce((select -sum(d.montobolivianos+d.montodolares)as 'egresos'
	  from detalletraspasodinero d,traspasodinero t 
	  where t.idtraspaso=d.idtraspaso  
	   and t.fecha<'$desde' and t.estado=1 and t.cuenta='$idcuenta' group by t.cuenta),0)
	  +
	  coalesce((select sum(c.monto) as 'ingresos' 
	  from cuentaporcobrar c where 
	   c.cuentacaja='$idcuenta' and c.estado=1 
	   and c.fecha<'$desde' group by c.cuentacaja),0)
	  +
	  
	  coalesce((select -sum(c.monto) as 'egresos'
	  from cuentaporpagar c where c.cuentacaja='$idcuenta'
	   and c.fecha<'$desde' and c.estado=1 
		 group by c.cuentacaja),0)
	  +
	  coalesce((select sum(n.monto) as 'ingresos'
	  from notaventa n where  n.caja='$idcuenta' and 
	   n.fecha<'$desde' and tiponota='servicios' and 
	   n.estado=1 group by n.caja),0)
	  +
	  coalesce((select sum(n.monto) as 'ingresos'  
	  from notaventa n where  n.caja='1.1.01.05.02.' 
	  and n.fecha<'$desde' and tiponota='productos' and 
	   n.estado=1 group by n.caja),0)
	  +
	  coalesce((select -sum(a.precio*a.cantidad)as 'egresos'
	  from activo a where a.estado=1 and a.cuenta='$idcuenta' and a.tipocuenta='caja' 
	  and a.fechacompra<'$desde' and 
	  a.estado=1 group by a.cuenta),0)
	  +
	  coalesce((select -coalesce(sum(i.efectivo), 0) as 'egresos' from ingresoproducto i
	  where i.caja='$idcuenta' and i.fecha<'$desde' and 
	  i.estado=1 group by i.caja),0)	  
	   ) as 'total';";	
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet"  type="text/css" href="style_movimientodinero.css"/>
<title>Reporte Traspaso de Dinero</title>
</head>

<body>
<?php
	$consulta = "
	(select a.fecha,a.idanticipo as 'idtransaccion',left(concat(t.nombre,' ',t.apellido),15)as 'nombre',0 as 'ingresos',
	  a.anticipo as 'egresos',left(a.documento,10) as 'recibo',left(a.glosa,35)as 'glosa','A' as 'tipoT' 
	  from anticipo a,trabajador t where t.idtrabajador=a.idtrabajador and
	  a.estado=1 and a.egreso='$idcuenta' and a.fecha>='$desde' and a.fecha<='$hasta')
	  union all
	  
	  (select i.fecha,i.idingreso as 'idtransaccion',left(i.nombrepersona,15)as 'nombre'
	  ,(i.ingresoBolivianos+i.ingresoDolares)as 'ingresos',0 as 'egresos',
	  left(i.recibo,10)as 'recibo',left(i.glosa,35)as 'glosa',
	  'ID' as 'tipoT'  
	   from ingreso i where i.cuenta='$idcuenta' and 
	   i.fecha>='$desde' and i.fecha<='$hasta' and i.estado=1) 
	  union all
	  
	  (select e.fecha,e.idegreso as 'idtransaccion',left(e.nombrepersona,15)as 'nombre',0 as 'ingresos',
	  (e.egresoBolivianos+e.egresoDolares) as 'egresos',
	  left(e.recibo,10)as 'recibo',left(e.glosa,35)as 'glosa','ED' as 'tipoT' 
	  from egreso e where e.estado=1 and e.fecha>='$desde' and 
	  e.fecha<='$hasta' and e.cuenta='$idcuenta')
	  union all
	  
	  
	  (select t.fecha,t.idtraspaso as 'idtransaccion',left(t.nombrepersona,15)as 'nombre'
	  ,(d.montobolivianos+d.montodolares)as 'ingresos',0 as 'egresos',
	  left(t.recibo,10)as 'recibo',left(t.glosa,35)as 'glosa','TDI' as 'tipoT'   
	  from detalletraspasodinero d,traspasodinero t 
	  where t.idtraspaso=d.idtraspaso and t.fecha>='$desde' 
	   and t.fecha<='$hasta' and t.estado=1 and d.idcuenta='$idcuenta')
	  union all
	  
	  (select t.fecha,t.idtraspaso as 'idtransaccion',left(concat(tb.nombre,' ',tb.apellido),15)as 'nombre'
		,0 as 'ingresos',(d.montobolivianos+d.montodolares)as 'egresos',
	  left(t.recibo,10)as 'recibo',left(t.glosa,35)as 'glosa','TDE' as 'tipoT'   
	  from detalletraspasodinero d,traspasodinero t,trabajador tb 
	  where t.idtraspaso=d.idtraspaso and d.idtrabajador=tb.idtrabajador 
	   and  t.fecha>='$desde' 
	   and t.fecha<='$hasta' and t.estado=1 and t.cuenta='$idcuenta')
	  union all
	  
	  (select c.fecha,c.idporcobrar as 'idtransaccion',left(cl.nombre,15)as 'nombre'
	  ,0 as 'ingresos',
	  c.monto as 'egresos',0 as 'recibo',left(c.glosa,35)as 'glosa','CxC' as 'tipoT' 
	  from cuentaporcobrar c,cliente cl where c.tipodeudor='cliente'
	  and c.cuentacaja='$idcuenta' and c.estado=1 and cl.idcliente=c.iddeudor 
	  and c.fecha>='$desde' and c.fecha<='$hasta')
	  union all
	  
	  (select c.fecha,c.idporcobrar as 'idtransaccion',left(concat(cl.nombre,' ',cl.apellido),15)as 'nombre'
	  ,0 as 'ingresos',
	  c.monto as 'egresos',0 as 'recibo',left(c.glosa,35)as 'glosa','CxC' as 'tipoT' 
	  from cuentaporcobrar c,trabajador cl where c.tipodeudor='trabajador'
	  and c.cuentacaja='$idcuenta' and c.estado=1 and cl.idtrabajador=c.iddeudor 
	  and c.fecha>='$desde' and c.fecha<='$hasta')
	  union all
	  
	  
	  (select c.fecha,c.idporcobrar as 'idtransaccion',left(concat(cl.nombre),15)as 'nombre'
	  ,0 as 'ingresos',
	  c.monto as 'egresos',0 as 'recibo',left(c.glosa,35)as 'glosa','CxC' as 'tipoT' 
	  from cuentaporcobrar c,proveedor cl where c.tipodeudor='proveedor'
	  and c.cuentacaja='$idcuenta' and c.estado=1 and cl.idproveedor=c.iddeudor 
	  and c.fecha>='$desde' and c.fecha<='$hasta')
	  union all
	  
	  
	  (select c.fecha,c.idporpagar as 'idtransaccion',left(cl.nombre,15)as 'nombre',
	   c.monto as 'ingresos',0 as 'egresos',0 as 'recibo',
	   left(c.glosa,35)as 'glosa','CxP' as 'tipoT' 
	  from cuentaporpagar c,cliente cl where c.cuentacaja='$idcuenta' and 
	  c.fecha>='$desde' and c.fecha<='$hasta' and c.estado=1 
	  and c.tipodeudor='cliente' and c.iddeudor=cl.idcliente)
	  union all
	  
	  (select c.fecha,c.idporpagar as 'idtransaccion',left(concat(t.nombre,' ',t.apellido),15)as 'nombre',
	  c.monto as 'ingresos',0 as 'egresos',0 as 'recibo',
	  left(c.glosa,35)as 'glosa','CxP' as 'tipoT' 
	  from cuentaporpagar c,trabajador t where c.cuentacaja='$idcuenta' and 
	  c.fecha>='$desde' and c.fecha<='$hasta' and c.estado=1 
	  and c.tipodeudor='trabajador' and c.iddeudor=t.idtrabajador)
	  union all
	  
	  (select c.fecha,c.idporpagar as 'idtransaccion',left(t.nombre,15)as 'nombre',
	  c.monto as 'ingresos',0 as 'egresos',0 as 'recibo',
	  left(c.glosa,35)as 'glosa','CxP' as 'tipoT' 
	  from cuentaporpagar c,proveedor t where c.cuentacaja='$idcuenta' and 
	  c.fecha>='$desde' and c.fecha<='$hasta' and c.estado=1 
	  and c.tipodeudor='proveedor' and c.iddeudor=t.idproveedor)
	  union all
	  
	  
	  (select n.fecha,n.numero as 'idtransaccion',left(c.nombre,15)as 'nombre',
	  n.monto as 'ingresos',0 as 'egresos',left(n.numfactura,10) as 'recibo',
	  left(n.glosa,35)as 'glosa','VS' as 'tipoT'  
	  from notaventa n,cliente c where  n.caja='$idcuenta' and 
	  n.fecha>='$desde' and n.fecha<='$hasta' and tiponota='servicios' and 
	  n.idcliente=c.idcliente and n.estado=1)
	  union all
	  
	  
	  (select n.fecha,n.numero as 'idtransaccion',left(c.nombre,15)as 'nombre',
	  n.monto as 'ingresos',0 as 'egresos',left(n.numfactura,10) as 'recibo',
	  left(n.glosa,35)as 'glosa','VP' as 'tipoT'  
	  from notaventa n,cliente c where  n.caja='$idcuenta' and 
	  n.fecha>='$desde' and n.fecha<='$hasta' and tiponota='productos' and 
	  n.idcliente=c.idcliente and n.estado=1)
	  union all
	  
	  
	  (select a.fechacompra as 'fecha',a.idactivo as 'idtransaccion',
	  left(concat(t.nombre,' ',t.apellido),15)as 'nombre',0 as 'ingresos',
	  (a.precio*a.cantidad)as 'egresos',0 as 'recibo',
	  left(a.detalle,35) as 'glosa','AF' as 'tipoT'
	  from activo a,trabajador t where t.idtrabajador=a.idtrabajador and 
	  a.estado=1 and a.cuenta='$idcuenta' and a.tipocuenta='caja' and 
	  a.fechacompra>='$desde' and a.fechacompra<='$hasta' and 
	  a.estado=1)
	  union all 
	  
	  (select i.fecha,i.nroingresoprod as 'idtransaccion',left(i.nombreasignado,15)as 'nombre',
	  0 as 'ingresos',
	  i.efectivo as 'egresos',left(i.facproveedor,10) as 'recibo',
	  left(i.glosa,35)as 'glosa','IP' as 'tipoT'  from ingresoproducto i
	  where i.caja='$idcuenta' and i.fecha>='$desde' and i.fecha<='$hasta' and 
	  i.estado=1)	order by fecha
	";
	
	$tope = 49;
	$cant = $db->getnumrow($consulta);
	$numero = 0;
	$row = $db->consulta($consulta);
	$totalGeneral = array(0, 0, 0, 0, 0);
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
	 ." $fechaInicio[2] al $fechaFinal[0] de ".$db->mes($fechaFinal[1])." del $fechaFinal[2]"    ;?></td></tr>    
     <tr><td align="center" class="session1_titulo2"><?php echo "(Expresado en Bolivianos)";?></td></tr>  
  </table>
</div>


<div class="session3_datos">

<table width="100%" border="0">
  <tr>
    <td width="11%" align="right" class="session1_subtitulo1">Trabajador:</td>
    <td width="89%" class="session1_subtitulo1"><?php echo ucfirst($datoTrabajador['nombre']);?></td>
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
  if ($numero == 0) {
	$i++;
	$sql = getConsultaTotal($idcuenta, $desde); 
	$saldo = $db->arrayConsulta($sql);
	$saldo = $saldo['total'];
	setAnteriorSaldo($saldo);
  }
  while ($data = mysql_fetch_array($row)) {
	  $numero++;
	  $i++;	  	  
	  $tipoFila = "normal";
	  $transaccion = $data['tipoT']."-".$data['idtransaccion'];
	  $totalGeneral[0] = $totalGeneral[0] + $data['ingresos'];
  	  $totalGeneral[1] = $totalGeneral[1] + $data['egresos'];
	  $saldo = $saldo + ($data['ingresos'] - $data['egresos']);
	  $fecha = $db->GetFormatofecha($data['fecha'], "-");
	  if ($numero == $cant || $i == $tope) {
		$tipoFila = "final";  
	  }
	  setDato($i, $tipoFila, $fecha, $transaccion, $data['recibo'], $data['glosa'], $data['nombre']
	  , $data['ingresos'], $data['egresos'], $saldo);
	  
	  
	  if ($i == $tope) 
	      break;
	  	  
  }
  setTotalF($totalGeneral, $saldo);
  
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