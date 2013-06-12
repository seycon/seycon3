<?php
    session_start();
    if (!isset($_SESSION['softLogeoadmin'])) {
        header("Location: ../index.php");	
    }
    ob_start();
    include("../MPDF53/mpdf.php");
    include('../conexion.php');
    $db = new MySQL();
    $desde = $_GET['desde'];
    $hasta = $_GET['hasta'];
    $fechaI = explode('/',$desde);
    $fechaF = explode('/',$hasta);
    $desde = "$fechaI[2]/$fechaI[1]/$fechaI[0]";
    $hasta = "$fechaF[2]/$fechaF[1]/$fechaF[0]"; 
    $tipoReporte = strtoupper($_GET['tipo']);
    $logo = 'true';   
    $codigoCuenta = $_GET['cuenta'];
    $sql = "select cuenta from plandecuenta where codigo='$codigoCuenta';";
    $tipoCuenta = $db->arrayConsulta($sql);   
    $sql = "select * from empresa ";
    $empresa = $db->consulta($sql);
    $empresa = mysql_fetch_array($empresa);
      
    function setSession1($usuario,$caja,$tipo)
	{
	    echo "<table width='90%' border='0' align='center'>
		<tr>
		<td >&nbsp;</td>
		<td colspan='3' >&nbsp;</td>
		</tr>
		<tr>
		<td class='subtitulo' style='font-size:12px;'></td>
		<td colspan='3' style='font-size:13px;'></td>
		</tr>
		<tr>
		<td class='subtitulo' style='font-size:12px;' align='right'>USUARIO:</td>
		<td colspan='3' style='font-size:11px;'>".substr($usuario,0,28)."</td>
		</tr>
		<tr>
		<td width='11%' class='subtitulo' style='font-size:12px;' align='right'>$tipo:</td>
		<td width='42%' style='font-size:11px;'>".substr($caja,0,28)."</td>
		<td width='24%' class='subtitulo' style='font-size:12px;'></td>
		<td width='23%' style='font-size:13px;'></td>
		</tr>
		</table>";	   
    }
   
   
    function setTotal($total)
    {
	    echo "
		<tr>
		<td width='9%' class='session_detalle_top'>&nbsp;</td>
		<td class='session_detalle_top'>&nbsp;</td>
		<td class='session_detalle_top'>&nbsp;</td>
		<td class='session_detalle_top'>&nbsp;</td>
		<td align='right' class='session_detalle_textoTotal'>Total:</td>
		<td width='12%' class='tablainiciototal' align='center'>".number_format($total,2)."</td>
		</tr>
		";   
    }
   
    function setPie()
    {
	    echo "
		<div class='session_pie'>
		<table width='93%' border='0' align='center'>
		<tr>
		<td width='120' align='right'></td>
		<td width='324'></td>
		<td width='93'>&nbsp;</td>
		<td width='189'>&nbsp;</td>
		<td width='201'>&nbsp;</td>
		<td width='170' >Impreso: ".date("d/m/Y")."</td>
		<td width='130'>Hora:".date("H:i:s")."</td>
		</tr>
		</table>
		</div>
		";   	   
    }
 
    function setData($nro, $recibo, $fecha, $glosa, $cliente, $importe, $numFila)
    {
        if ($numFila == 48) {
	       $clase = "tablaContenidoFin";  
		   $claseP = "tablacontenidoLateralFin";
	    } else {
	       $clase = "tablacontenido_1"; 	 
		   $claseP = "tablacontenidoLateral"; 
	    }
	   
	    echo "  
	    <tr>
	 	 <td class='$claseP' align='center' style='font-size:11px;'>$nro</td>
		 <td class='$clase' align='center' style='font-size:11px;'>$recibo</td>
		 <td class='$clase' align='center' style='font-size:11px;'>$fecha</td>
		 <td class='$clase' style='font-size:11px;'>&nbsp;&nbsp;$glosa</td>
		 <td class='$clase' align='left' style='font-size:11px;'>&nbsp;&nbsp;$cliente</td>
		 <td class='$clase' style='border-right:1px solid #000;font-size:11px;' align='center'>".number_format($importe,2)."</td>
	    </tr>  
	    "; 
    }   
	
	function nextPage()
	{
	    for ($h = 0; $h <= 48; $h++) {
		    echo "<br>";
		}  	
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reporte</title>
<link rel="stylesheet" type="text/css" href="../ingresos/reporte.css"/>
</head>

<body>

<?php
    $sql = "select idegreso,recibo,left(glosa,45)as 'glosa',left(nombrepersona,20)as 'nombre',egresoBolivianos
    ,date_format(fecha,'%d/%m/%Y')as   'fecha' from egreso where cuenta='$codigoCuenta' 
    and fecha>='$desde' and fecha<='$hasta' and estado=1;";
    $result = $db->consulta($sql);
    $totalItem = $db->getnumRow($sql);
    $numFila = ($totalItem == 0) ? -1 : 0;
    $totalT = 0;
	while ($numFila < $totalItem ) {
	    if ($numFila == -1) {
		    $numFila = 0;   
		}
?>

<div style=" position : absolute;left:5%; top:20px;"></div>


<div class="margenPrincipal"></div>
<div class="session1_logotipo"><?php if ($logo == 'true'){ echo "<img src='../$empresa[imagen]' width='200' height='70'/>";} ?></div>
<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1"><?php echo "EGRESOS DE $tipoReporte" ?></td></tr>
     <tr><td align="center" class="session1_titulo2">
	 <?php echo "Del $fechaI[0] de ".$db->mes($fechaI[1])." de $fechaI[2] al $fechaF[0] de ".$db->mes($fechaF[1])." de $fechaF[2]";?></td></tr>
     <tr><td align="center" class="session1_titulo3">(Expresado en Bolivianos)</td></tr> 
    </table>
</div>

<?php
  setSession1($_SESSION['nombre_usuario'],$tipoCuenta['cuenta'],$tipoReporte);
?>

<br />
<table width="98%"  align="center"  cellspacing="0">
  <tr class="tablainicio">
    <td class="tablainiciotitulo" style="font-size:11px;">N°</td>
    <td width="10%" class="tablainiciotitulo" style="font-size:11px;">Doc</td>
    <td width="10%" class="tablainiciotitulo" style="font-size:11px;">Fecha</td>
    <td width="48%" class="tablainiciotitulo" style="font-size:11px;">Descripción</td>
    <td width="21%" class="tablainiciotitulo" style="font-size:11px;">Nombre</td>
    <td class="tablainiciotitulo" style="border-right:1px solid #000;font-size:11px;">Egresos</td>
  </tr>
  
<?php 
    $i = 0;
    while ($dato = mysql_fetch_array($result)) {      
	    $totalT = $totalT + $dato[4];
	    $i++;
	    setData($dato[0], $dato[1], $dato['fecha'], $dato[2], $dato[3], $dato[4], $i);	  
	    if ($i == 48)
	        break;
	}	
	$numFila = $numFila + $i;	
	for ($j = ($i+1); $j <= 48; $j++) {		
	    echo "  
    	<tr>
	    <td class='session_detalle_lizq' style='font-size:11px;'>&nbsp;</td>
		<td style='font-size:11px;'>&nbsp;</td>
		<td style='font-size:11px;'>&nbsp;</td>
		<td style='font-size:11px;'>&nbsp;</td>
		<td style='font-size:11px;'>&nbsp;</td>
		<td class='session_detalle_lder' style='font-size:11px;'>&nbsp;</td>
	    </tr>  
	      ";	
	}	
	setTotal($totalT);	
?>

</table> 
<?php
   	setPie();	  	       
	if ($numFila < $totalItem) {
	      nextPage();	  
	   }
    }
?>
</body>
</html>
<?php
    $mpdf = new mPDF('utf-8','Letter'); 
    $content = ob_get_clean();
    $mpdf->SetHTMLHeader($header);
    $mpdf->WriteHTML($content);
    $mpdf->Output();
    exit;
?>