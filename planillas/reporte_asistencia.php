<?php
	ob_start();
	session_start();
	include('../MPDF53/mpdf.php');
	include('../conexion.php');
	$db = new MySQL();


   /* $almacen = $_GET['almacen'];
	$hasta = $db->GetFormatofecha($_GET['hasta'],'/');
	$idtrabajador = $_GET['grupo'];*/
	

	$hasta = "$_POST[anio]/$_POST[meses]/24";
	$idtrabajador = $_POST['trabajador'];
	
	$fechaF = explode('/',$hasta);
	
	$sql = "SELECT day(LAST_DAY('$hasta')) as 'dia';";
	$maxDia = $db->arrayConsulta($sql);

	$sql = "select imagen,left(nombrecomercial,13)as 'nombrecomercial',nit from empresa;";
	$datoGeneral = $db->arrayConsulta($sql);
	$sql = "select left(concat(t.nombre,' ',t.apellido),20) as 'trabajador',
	 t.fechaingreso,c.cargo,s.nombrecomercial,t.sueldobasico  
	 from trabajador t,cargo c,sucursal s
	 where t.idcargo=c.idcargo 
	 and t.idsucursal=s.idsucursal 
	 and t.idtrabajador=$idtrabajador;";
	$datoTrabajador = $db->arrayConsulta($sql);
	
	$sql = "select *from bono where idtrabajador=$idtrabajador and month(fecha)=$fechaF[1] and year(fecha)=$fechaF[0];";
	$datosBono = $db->arrayConsulta($sql);
	$sql = "select *from planilla where idtrabajador=$idtrabajador and month(fecha)=$fechaF[1] and year(fecha)=$fechaF[0];";
	$datoPlanilla = $db->arrayConsulta($sql);
	
	
	function setcabecera()
	{
		echo "
		<tr >
		  <td width='8%' rowspan='2' class='session1_cabecera1'>Fecha</td>
		  <td colspan='2' class='session1_cabecera1'>AM</td>
		  <td colspan='2' class='session1_cabecera1'>PM</td>
		  <td colspan='4' class='session1_cabecera1'>DESCUENTO</td>
		  <td colspan='3' class='session1_cabecera3'>COMISION</td>
		</tr>
		<tr >
		  <td width='6%' class='session1_cabecera2'>Ing.</td>
		  <td width='6%' class='session1_cabecera2'>Salida</td>
		  <td width='7%' class='session1_cabecera2'>Ing.</td>
		  <td width='7%' class='session1_cabecera2'>Salida</td>
		  <td width='7%' class='session1_cabecera2'>Atraso</td>
		  <td width='7%' class='session1_cabecera2'>Cons.</td>
		  <td width='7%' class='session1_cabecera2'>Anticipo</td>
		  <td width='7%' class='session1_cabecera2'>Faltante</td>
		  <td width='7%' class='session1_cabecera2'>Venta</td>
		  <td width='7%' class='session1_cabecera2'>4%</td>
		  <td width='7%' class='session1_cabecera2' style='border-right:1.5px solid;'>Botella</td>
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


    function diaSemana($ano, $mes, $dia)
	{
		$dias = array("Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab");
	    $dia = date("w",mktime(0, 0, 0, $mes, $dia, $ano));
	    return $dias[$dia];
	}

   	function setDato($num, $tipo, $dia, $anticipo, $faltante, $consumo, $ventas, $botella, $comision, $diasemana)
	{
	 $clase1 = "";	
	 if ($tipo == "cierre") {
	     $clase1 = "border-bottom:1.5px solid";
	 }	
	 $clase2 = "";
	 if ($num % 2 == 0) {
		 $clase2 = "cebra";
	 }	 
	 
	 if ($diasemana != 6 && $diasemana != 7){
		 $comision = 0;
		 $botella = 0;
	 }
	 
	 echo "<tr class='$clase2'>
		  <td class='session3_datosF1_1' style='$clase1' align='center'>$dia $num</td>
		  <td class='session3_datosF1_2' style='$clase1'></td>
		  <td class='session3_datosF1_2A' style='$clase1'></td>
		  <td class='session3_datosF1_2A' style='$clase1'></td>
		  <td class='session3_datosF1_2A' style='$clase1'></td>
		  <td class='session3_datosF1_2' style='$clase1'></td>
		  <td class='session3_datosF1_2A' style='$clase1'>".number_format($consumo, 2)."</td>
		  <td class='session3_datosF1_2A' style='$clase1'>".number_format($anticipo, 2)."</td>
		  <td class='session3_datosF1_2A' style='$clase1'>".number_format($faltante, 2)."</td>
		  <td class='session3_datosF1_2' style='$clase1'>".number_format($ventas, 2)."</td>
  	      <td class='session3_datosF1_2A' style='$clase1'>".number_format($comision, 2)."</td>
		  <td class='session3_datosF1_3' style='$clase1'>".number_format($botella, 2)."</td>
		</tr>";

	}
	
	function setDatoTotales($subtotal)
	{	 
	 echo "<tr >
		  <td></td>
		  <td></td>
		  <td></td>
		  <td></td>
		  <td></td>
		  <td class='session3_datosTotales1' >&nbsp;</td>
		  <td class='session3_datosTotales1' >".number_format($subtotal[1],2)."</td>
		  <td class='session3_datosTotales1' >".number_format($subtotal[2],2)."</td>
		  <td class='session3_datosTotales1' >".number_format($subtotal[3],2)."</td>
		  <td class='session3_datosTotales1' >".number_format($subtotal[4],2)."</td>
		  <td class='session3_datosTotales1' >".number_format($subtotal[5],2)."</td>
		  <td class='session3_datosTotales2' >".number_format($subtotal[6],2)."</td>
		</tr>";
	}
	
	function setSessionFirmas()
	{
	 echo "
	 <table width='100%' border='0' align='center'>
	  <tr>
		<td width='124'>&nbsp;</td>
		<td width='211' class='titulosNegros' align='center'>...........................................................</td>
		<td width='39'>&nbsp;</td>
		<td width='210' class='titulosNegros' align='center'>..........................................................</td>
		<td width='39'>&nbsp;</td>
		<td width='171' class='titulosNegros' align='center'>.........................................................</td>
		<td width='293'>&nbsp;</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td align='center' class='session1_subtitulo1'>Contabilidad</td>
		<td>&nbsp;</td>
		<td align='center' class='session1_subtitulo1'>RR-HH</td>
		<td>&nbsp;</td>
		<td align='center' class='session1_subtitulo1'>C.I.:..............................................</td>
		<td>&nbsp;</td>
	  </tr>
	</table> 
	 ";	
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet"  type="text/css" href="style_asistencia.css"/>
<!--La linea anterior incluye el archivo .css-->
<title>Reporte de Asistencia</title>
</head>

<body>
<?php
	$consulta = "select fa.fecha,DAYOFWEEK(fa.fecha)as 'diasemana',
	(
	select sum(a.anticipo)
	 from anticipo a
	where a.idtrabajador=$idtrabajador and a.estado=1 and fa.fecha=a.fecha 
	group by a.fecha)as 'anticipo'
	,(
	select round(sum((e.acumulado-e.monto)),4)as 'faltante' from entregadinero e where e.estado=1 and
	e.idtrabajador=$idtrabajador and e.tipo='fijo' and e.fecha=fa.fecha and (e.acumulado-e.monto)>0 group by e.fecha
	)as 'faltante',
	(
	select round(sum(a.efectivo),4) from atencion a where a.credito=1 and socio=0 
    and a.idtrabajador=$idtrabajador and date(a.fecha)=fa.fecha  group by date(a.fecha)
	)as 'consumo',
	(
	 select round(sum(b.descuento*cantidad),2) as 'botella' 
	 from atencion a,detalleatencion d,usuariorestaurante u,
	 bonoproducto b  
	 where d.idatencion=a.idatencion and d.estado=1 and a.idusuariorestaurante=u.idusuario 
	 and u.idtrabajador=$idtrabajador and u.tipo='fijo' and a.estado='cobrado' 
	 and date(a.fecha)=fa.fecha and d.idcombinacion=b.idcombinacion group by date(a.fecha)
	) as 'botella',
	
	(
	select round(sum(a.efectivo),4) from atencion a,usuariorestaurante u where a.idusuariorestaurante=u.idusuario and 
    a.estado='cobrado' and credito=0 and socio=0 and u.idtrabajador=$idtrabajador and u.tipo='fijo'   
	and date(a.fecha)=fa.fecha group by a.fecha
	)as 'ventas' 
	from fechaasistencia fa where year(fecha)=$fechaF[0] and month(fecha)=$fechaF[1] order by fecha asc;  ";
	
	$cant = $db->getnumrow($consulta);
	$numero = 0;
	$row = $db->consulta($consulta);
	$subtotal = array(0, 0, 0, 0, 0, 0);
	//while($numero < $cant) {
?>


<div style=" position : absolute;left:5%; top:20px;"></div>
<div class='margenprincipal'></div>
<div class='session1_logotipo'><?php echo  "<img src='../$datoGeneral[imagen]' width='200' height='70'/>"; ?></div>

<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1"><?php echo "PLANILLA DE CONTROL DE ASISTENCIA";?></td></tr> 
     <tr><td align="center" class="session1_titulo2">
	 <?php echo "Mes de ".$db->mes($fechaF[1])." del $fechaF[0]" ;?></td></tr>
  </table>
</div>


<div class="session3_datos">

<table width="100%" border="0">
  <tr>
    <td width="19%" align="right" class="session1_subtitulo1">Trabajador:</td>
    <td width="36%" class="session1_subtitulo1"><?php echo $datoTrabajador['trabajador'];?></td>
    <td width="15%" class="session1_subtitulo1" align="right">Sucursal:</td>
    <td width="30%" class="session1_subtitulo1"><?php echo $datoTrabajador['nombrecomercial'];?></td>
  </tr>
  <tr>
    <td width="19%" align="right" class="session1_subtitulo1">Cargo:</td>
    <td width="36%" class="session1_subtitulo1"><?php echo $datoTrabajador['cargo'];?></td>
    <td width="15%" class="session1_subtitulo1" align="right">Fecha de Ingreso:</td>
    <td width="30%" class="session1_subtitulo1"><?php 
	echo $db->GetFormatofecha($datoTrabajador['fechaingreso'],"-");?></td>
  </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<?php
  setcabecera();

  $i = 0;
  while ($dato = mysql_fetch_array($row)) {
	  $numero++;
	  $i++;	  	  
	  
	 $comision = ($dato['ventas'] * 0.04) - ($datoTrabajador['sueldobasico'] / 30);
	 $comision = ($comision < 0) ? 0 : $comision;
	  
	  $subtotal[1] = $subtotal[1] + $dato['consumo'];
      $subtotal[2] = $subtotal[2] + $dato['anticipo'];
	  $subtotal[3] = $subtotal[3] + $dato['faltante'];
	  $subtotal[4] = $subtotal[4] + $dato['ventas'];
	  $subtotal[5] = $subtotal[5] + $comision;
	  $subtotal[6] = $subtotal[6] + $dato['botella'];
	  $dia = diaSemana($fechaF[0], $fechaF[1], $i);
	  if ($i == $maxDia['dia']) {
	      setDato($i, "cierre", $dia, $dato['anticipo'], $dato['faltante'], $dato['consumo'], $dato['ventas']
		  , $dato['botella'], $comision, $dato['diasemana']);  
	  } else {		  
		  setDato($i, "normal", $dia, $dato['anticipo'], $dato['faltante'], $dato['consumo'], $dato['ventas']
		  , $dato['botella'], $comision, $dato['diasemana']);
	  }
  }
  
  setDatoTotales($subtotal);  
?>
</table>
</div>
<div class="resumen1">
  <table width="191" border="0" align="center" cellspacing="0" cellpadding="0">
  <tr>
    <td width="88" class="session3_resumen1_1">Atraso</td>
    <td width="103" class="session3_resumen2_1">0.00</td>
  </tr>
  <tr>
    <td class="session3_resumen1_3">Consumo</td>
    <td class="session3_resumen2_3"><?php echo number_format($subtotal[1],2);?></td>
  </tr>
  <tr>
    <td class="session3_resumen1_3">Anticipo</td>
    <td class="session3_resumen2_3"><?php echo number_format($subtotal[2],2);?></td>
  </tr>
  <tr>
    <td class="session3_resumen1_3">Faltante</td>
    <td class="session3_resumen2_3"><?php echo number_format($subtotal[3],2);?></td>
  </tr>
  <tr>
    <td class="session3_resumen1_2">Prestamo</td>
    <td class="session3_resumen2_2">0.00</td>
  </tr>
  <tr>
    <td align="right" class="titulo_datos">Total Desc.:</td>
    <td class="session3_resumen2_T">
    <?php
        $totalDescuento = ($subtotal[1] + $subtotal[2] + $subtotal[3]);
		echo number_format($totalDescuento,2);
	?>
    </td>
  </tr>
</table>

</div>
´

<div class="resumen2">
  <table width="191" border="0" align="center" cellspacing="0" cellpadding="0">
  <tr>
    <td width="103" class="session3_resumen1_1">Haber Basico</td>
    <td width="88" class="session3_resumen2_1"><?php echo number_format($datoPlanilla['sueldobasico'],2);?></td>
  </tr>
  <tr>
    <td class="session3_resumen1_3">Bono Producción</td>
    <td class="session3_resumen2_3"><?php echo number_format($datosBono['bonoproduccion'],2);?></td>
  </tr>
  <tr>
    <td class="session3_resumen1_3">Bono Asistencia</td>
    <td class="session3_resumen2_3"><?php echo number_format($datosBono['asistencia'],2);?></td>
  </tr>
  <tr>
    <td class="session3_resumen1_3">Bono Puntualidad</td>
    <td class="session3_resumen2_3"><?php echo number_format($datosBono['puntualidad'],2);?></td>
  </tr>
  <tr>
    <td class="session3_resumen1_3">Comision</td>
    <td class="session3_resumen2_3"><?php echo number_format($datosBono['comisiones'],2);?></td>
  </tr>
  <tr>
    <td class="session3_resumen1_2">Horas Extras</td>
    <td class="session3_resumen2_2"><?php echo $datosBono['horasextras'];?></td>
  </tr>
  <tr>
    <td align="right" class="titulo_datos">Total Ganado:</td>
    <td class="session3_resumen2_T">
	<?php 
	  $totalGanado = $datoPlanilla['sueldobasico'] + $datosBono['bonoproduccion'] + $datosBono['asistencia'] 
	  + $datosBono['puntualidad'] + $datosBono['comisiones'];
	  echo number_format($totalGanado, 2); 
	?></td>
  </tr>
  </table>
</div>

<div class="resumen3">
  <table width="191" border="0" align="center" cellspacing="0" cellpadding="0">
  <tr>
    <td width="112" class="session3_resumen1_1">TOTAL GANADO</td>
    <td width="79" class="session3_resumen2_1"><?php echo number_format($totalGanado, 2);?></td>
  </tr>
   <tr>
    <td class="session3_resumen1_2">TOTAL DESCUENTO</td>
    <td class="session3_resumen2_2"><?php echo number_format($totalDescuento, 2);?></td>
  </tr>
  <tr>
    <td align="right" class="session3_resumen1_2" style="background-color:#E2E2E2;">LIQUIDO PAGABLE:</td>
    <td class="session3_resumen2_T"><?php echo number_format(($totalGanado - $totalDescuento), 2);?></td>
  </tr>
  </table>
</div>
<div class="session5_firmas"> 
 <?php setSessionFirmas(); ?>
 </div>

<?php
  /* pie();
       if($numero < $cant) {
	       nextPage();
	   }

	}*/
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