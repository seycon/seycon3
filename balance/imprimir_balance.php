<?php
	session_start();
	if (!isset($_SESSION['softLogeoadmin'])) {
	    header("Location: ../index.php");	
	}
	ob_start();
	include("../MPDF53/mpdf.php");
	include("../conexion.php");
	include('../reportes/literal.php');
	$db = new MySQL();
	$logo = $_GET['logo'];
	$moneda = $_GET['moneda'];
	$sucursal = $_GET['sucursal'];
		
	$sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
	$tc = $db->getCampo('dolarcompra',$sql);
	
	$sql = "select imagen,left(nombrecomercial,40)as 'nombrecomercial',nit from empresa;";
	$datoGeneral = $db->arrayConsulta($sql);
	
	$fechaInicial = $db->GetFormatofecha($_GET['desde'],"/");
	$fechaFinal = $db->GetFormatofecha($_GET['hasta'],"/");
	$fechaInicio = explode("/",$fechaInicial);
	$fechaFin = explode("/",$fechaFinal);
	$auxiliares = ($_GET['auxiliar'] == "true") ? "si" : "no";

	if ($moneda == "Bolivianos") {
	    $tc = 1;	
	}
	
	function getEspacios($nivel)
	{
	  $cadena = "";	
	  $nivel = ($nivel>4) ? ($nivel-2)*3 : $nivel;
	  for ($i = 1; $i <= $nivel; $i++) {
		 $cadena = $cadena."&nbsp;"; 
	  }
	  return $cadena;	
	}
	
	function getTotalSubGeneral($cuenta, $total)
	{
	 echo "<tr>
		<td width='64%' class='cuentaNivel3'>$cuenta</td>
		<td width='12%'></td>
		<td width='12%'></td>
		<td width='12%' class='montoTotal'>".number_format($total,2)."</td>
	  </tr>";	
	}
	
	
	function getTotalGeneral($cuenta, $total)
	{
	 echo "<tr>
		<td class='cuentaNivel3' align='right'>$cuenta</td>
		<td ></td>
		<td ></td>
		<td class='montoTotal'>".number_format($total,2)."</td>
	  </tr>";	
	}

   function getVacio()
   {
   echo "<tr>
	  <td >&nbsp;</td>
	  <td >&nbsp;</td>
	  <td >&nbsp;</td>
	  <td >&nbsp;</td>
	</tr>";	
   }
  
   function getNivelCuenta($nivel, $cuenta)
   {
	  $espacio = getEspacios($nivel); 
	  echo "
	  <tr>
	  <td class='cuentaNivel3'>$espacio $cuenta</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>";	 
   }
   
   function getNivelDato($nivel, $cuenta, $monto)
   {
	  $espacio = getEspacios($nivel); 
	  echo "
	  <tr>
	  <td class='cuentaNivel5'>$espacio $cuenta</td>
	  <td>&nbsp;</td>
	  <td class='monto'>".number_format($monto,2)."</td>
	  <td>&nbsp;</td>
	  </tr>";	 
   }
   
   function getNivelDato2($nivel, $cuenta, $monto)
   {
	  $espacio = getEspacios($nivel); 	
	  echo "
	  <tr>
	  <td class='cuentaNivel5'>$espacio $cuenta</td>
	  <td class='monto'>".number_format($monto,2)."</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>";	 
   }
  
 
   function getResultados($db, $fechaInicial, $fechaFinal)
   {
   $totalIngreso = 0;
   $totalEgreso = 0;
   for ($j = 4; $j <= 6; $j++) {
	 if ($j == 4) {	
		$sql = "
		SELECT (SUM(  dl.haber ) -	SUM( dl.debe )) AS  'total'
		FROM detallelibrodiario dl, librodiario l 
		where l.idlibrodiario = dl.idlibro
		AND l.estado=1 
		AND l.fecha <=  '$fechaFinal' 
			AND l.fecha >=  '$fechaInicial'
		AND left(dl.idcuenta,1)='$j'
		GROUP BY dl.idcuenta;";
	 } else {	
		$sql = "
		SELECT (SUM(dl.debe ) - SUM(dl.haber )) AS  'total'
		FROM detallelibrodiario dl, librodiario l 
		where l.idlibrodiario = dl.idlibro
		AND l.estado=1 
		AND l.fecha <=  '$fechaFinal' 
			AND l.fecha >=  '$fechaInicial'
		AND left(dl.idcuenta,1)='$j'
		GROUP BY dl.idcuenta;";
	 }
	 $consulta = $db->consulta($sql);
	 $subTotal = 0;
	  while ($dato = mysql_fetch_array($consulta)) {
		  $subTotal = $subTotal + $dato['total'];	
	  }
	  if ($j == 4) {
		  $totalIngreso = $subTotal;	
		  continue;
	  }
	  if ($j == 5) {
		  $totalIngreso = $totalIngreso - $subTotal;	
		  continue;
	  }
	  $totalEgreso = $subTotal;
	  
	 }
   return $totalIngreso - $totalEgreso;
  }
 
  function getMontos($db, $fechaInicial, $fechaFinal, $i)
  {
	$sql = "
    SELECT (SUM(  dl.haber ) -	SUM( dl.debe )) AS  'total'
	FROM detallelibrodiario dl, librodiario l 
	where l.idlibrodiario = dl.idlibro
	AND l.estado=1 
	AND l.fecha <=  '$fechaFinal' 
        AND l.fecha >=  '$fechaInicial'
	AND left(dl.idcuenta,1)='$i'
	GROUP BY dl.idcuenta;"; 
	$consulta = $db->consulta($sql);
    $subTotal = 0;
	while ($dato = mysql_fetch_array($consulta)) {
	    $subTotal = $subTotal + $dato['total'];	
	}
	return $subTotal; 
  }
 
  function getMontosA($db, $fechaInicial, $fechaFinal)
  {
	$sql = "
	SELECT (SUM( dl.debe ) -	SUM(dl.haber)) AS  'total'
	FROM detallelibrodiario dl, librodiario l 
	where l.idlibrodiario = dl.idlibro
	AND l.estado=1 
	AND l.fecha <=  '$fechaFinal' 
		AND l.fecha >=  '$fechaInicial'
	AND left(dl.idcuenta,1)='1'
	GROUP BY dl.idcuenta;"; 
	$consulta = $db->consulta($sql);
	$subTotal = 0;
	while ($dato = mysql_fetch_array($consulta)) {
		$subTotal = $subTotal + $dato['total'];	
	}
	return $subTotal; 
  }
 
 
 function getSql1($fechaInicial, $fechaFinal)
 {
	return "select pp.codigo,pp.cuenta,pp.nivel,pp.moneda,
    (if(pp.nivel = 5 , (SELECT  ( SUM( dl.debe ) - SUM(dl.haber)) 
	FROM detallelibrodiario dl, librodiario l 
	where l.idlibrodiario = dl.idlibro
	AND l.fecha <=  '$fechaFinal'
    AND l.fecha >=  '$fechaInicial'
	AND left(dl.idcuenta,13) = pp.codigo
	AND l.estado=1 
	GROUP BY left(dl.idcuenta,13))
	, (SELECT  ( SUM( dl.debe ) - SUM( dl.haber)) 
	FROM detallelibrodiario dl, librodiario l 
	where l.idlibrodiario = dl.idlibro
	AND l.fecha <=  '$fechaFinal'
    AND l.fecha >=  '$fechaInicial'
	AND dl.idcuenta = pp.codigo
	AND l.estado=1 
	GROUP BY dl.idcuenta)))
	as 'total',
    pcp.cuenta as 'padre'
    from plandecuenta pp,plandecuenta pcp where if (pp.nivel = 6,left(pp.codigo,13)=pcp.codigo,
    left(pp.codigo,10)=pcp.codigo)  
    and left(pp.codigo,1)='1' and pp.nivel>3 and pp.estado=1 and pcp.estado=1 order by pp.codigo;";
 }
 
 function getSql2($fechaInicial, $fechaFinal, $i)
 {
	return "select pp.codigo,pp.cuenta,pp.nivel,pp.moneda,
    (if(pp.nivel = 5 , (SELECT  ( SUM(dl.haber ) - SUM(dl.debe)) 
	FROM detallelibrodiario dl, librodiario l 
	where l.idlibrodiario = dl.idlibro
	AND l.fecha <=  '$fechaFinal'
    AND l.fecha >=  '$fechaInicial'
	AND left(dl.idcuenta,13) = pp.codigo
	AND l.estado=1 
	GROUP BY left(dl.idcuenta,13))
	, (SELECT  ( SUM( dl.haber ) - SUM( dl.debe)) 
	FROM detallelibrodiario dl, librodiario l 
	where l.idlibrodiario = dl.idlibro
	AND l.fecha <=  '$fechaFinal'
    AND l.fecha >=  '$fechaInicial'
	AND dl.idcuenta = pp.codigo
	AND l.estado=1 
	GROUP BY dl.idcuenta)))
	as 'total',
    pcp.cuenta as 'padre'
    from plandecuenta pp,plandecuenta pcp where if (pp.nivel = 6,left(pp.codigo,13)=pcp.codigo,
    left(pp.codigo,10)=pcp.codigo)  
    and left(pp.codigo,1)='$i' and pp.nivel>3 and pp.estado=1 and pcp.estado=1 order by pp.codigo;";
 }

  function nextPage()
  {	
	 for ($m = 1; $m < 48; $m++) {
	     echo "<br>";
	 }      
  }
  
  function getTotalFinal($cuenta, $total)
  {
  echo "<tr>
          <td colspan='3' class='cuentaNivel3' align='right'>$cuenta</td>
          <td class='montoTotal2'>".number_format($total,2)."</td>
        </tr>";	
  }


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="balance.css" type="text/css" />
<title>Reporte de Balance General</title>
</head>

<body>
<?php
	$totalIngreso = 0;
	$totalEgreso = 0;
	$cantidadItem = 0;
	$auxPuntero = 0;
	$cuentaAuxiliarPadre = "";
	$cuentaAuxiliar = "";
	$i = 1;
	$maxFilas = 32;
	$session = "ACTIVO";
	$resultado = getResultados($db,$fechaInicial,$fechaFinal) / $tc;
	while ($i <= 3) {   
		 
		$flag = true;  
		$cantidadItem = 0;
?>
<div style=" position : absolute;left:5%; top:20px;"></div>
<div class="borde"></div>
<div class="session1_numTransaccion">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr><td align="right" class="session1_titulo1_2"><?php echo $datoGeneral['nombrecomercial']; ?></td></tr> 
     <tr><td align="right" class="session1_titulo1_2"><?php echo "Nit: ".$datoGeneral['nit']; ?></td></tr>
     <tr><td align="right" class="session1_titulo1_2"><?php echo "Santa Cruz-Bolivia"; ?></td></tr>
    </table>
</div>
<div class="session1_logotipo"><?php if ($logo == 'true'){ echo  "<img src='../$datoGeneral[imagen]' width='200' height='70'/>";} ?></div>
<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1">BALANCE GENERAL</td></tr>
     <tr><td align="center" class="session1_titulo1_1"><?php echo "Del ".$fechaInicio[2]." de "
	 .$db->mes($fechaInicio[1])." de ".$fechaInicio[0]
	 ." al ".$fechaFin[2]." de ".$db->mes($fechaFin[1])." de ".$fechaFin[0]; ?></td></tr> 
     <tr><td align="center" class="session1_titulo1_1">(Expresado en <?php echo $moneda;?>)</td></tr> 
    </table>
</div>


<div class="session3_datos">

<table width="80%" border="0" align="center">
<?php

	
  while ($flag) {
    
	 if ($i != $auxPuntero) {
	    if($i == 1) { 	  
			$montoT = getMontosA($db,$fechaInicial,$fechaFinal);
			echo getTotalSubGeneral($session,$montoT);  
			$cuentaActivos = $db->consulta(getSql1($fechaInicial,$fechaFinal));
		} else {
			$montoT = getMontos($db,$fechaInicial,$fechaFinal,$i);	
			if ($i==3)
			 $montoT = $montoT + $resultado;
			echo getTotalSubGeneral($session,$montoT);  
			$cuentaActivos = $db->consulta(getSql2($fechaInicial,$fechaFinal,$i));
		}
		
		$auxPuntero = $i;
     }

  while ($cuenta = mysql_fetch_array($cuentaActivos)) {
	 if ($cuenta['nivel'] <= 5) { 
	   $totalActivo = $totalActivo + ($cuenta['total'] / $tc);
	 }
	   
	  if ($cuenta['nivel'] == 4) {
		  $padre = $cuenta['cuenta'];		 
	  }
	  
	  if ($cuenta['nivel'] == 5) {
		  $cuentaAuxiliar = $cuenta['cuenta'];
		if ($cuenta['total'] != "" && $cuenta['total'] != 0) {  
		 if ($superPadre != $padre) {
			 getNivelCuenta(4,$padre);
			 $cantidadItem++;
			 $superPadre = $padre;
		 }
		  getNivelDato(5,$cuenta['cuenta'],($cuenta['total']/$tc));	
		  $cantidadItem++;	
		  $cuentaAuxiliarPadre = $cuentaAuxiliar;
		}
	  }	  
	  
	  if ($cuenta['nivel'] == 6 && $cuenta['total'] != ""  && $cuenta['total'] != 0 && $auxiliares == "si") {
		 if ($superPadre != $padre) {
			 getNivelCuenta(4,$padre);
			 $cantidadItem++;
			 $superPadre = $padre;
		 } 
		 if ($cuentaAuxiliar != $cuentaAuxiliarPadre) {
			 getNivelCuenta(5,$cuentaAuxiliar);
			 $cantidadItem++;
			 $cuentaAuxiliarPadre = $cuentaAuxiliar;
		 } 
		 
		 getNivelDato2(6,$cuenta['cuenta'],($cuenta['total']/$tc));	
		 $cantidadItem++;
	  }
	  
	 if ($cantidadItem >= $maxFilas) {
	   break;	   
	 }
	   		    
  }
  
  if ($cantidadItem < $maxFilas) {
     
	if ($i == 1) {
	   getVacio();	
	   $totalIngreso =  $totalActivo;	 
       $session = "PASIVO";  	 
	}  
	
	if ($i == 2) {
       $totalEgreso = $totalActivo;
       getVacio();
	   $session = "PATRIMONIO";
    }
	
	if ($i == 3) {
       if (($resultado) > 0)	
	       $modo = "UTILIDAD";
	   else
	       $modo = "PERDIDA"; 
	   getNivelDato(5,$modo." DE LA GESTION", $resultado);	
	   $totalEgreso = $totalEgreso + $totalActivo + $resultado;
	}
	
	$padre = "";
	$totalActivo = 0;
	$i++;
	
	if ($i == 4) {
	  getTotalFinal("TOTAL ACTIVO",$totalIngreso);
      getTotalFinal("TOTAL PASIVO Y PATRIMONIO",$totalEgreso);	  
	  break;	 
	}
  }

  if ($cantidadItem >= $maxFilas) {	  
	   break;	   
  }  
 }

?>
  
</table>
</div>

  <div class="session4_pie"> 
  <table width="93%" border="0" align="center">
  <tr>
    <td width="120" align="right"></td>
    <td width="324"></td>
    <td width="93">&nbsp;</td>
    <td width="189">&nbsp;</td>
    <td width="201">&nbsp;</td>
    <td width="170" >Impreso: <?php echo date("d/m/Y");?></td>
    <td width="130">Hora: <?php echo date("H:i:s");?></td>
  </tr>
  </table>
  </div> 
 
 <?php
	 if ($cantidadItem >= $maxFilas) {
		 nextPage();
	 } 
  } 
 ?>
</body>
</html>
<?php
	$mpdf = new mPDF('utf-8','Letter'); 
	$content = ob_get_clean();
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit;
?>