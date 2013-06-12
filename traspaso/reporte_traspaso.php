<?php
  session_start();
  if (!isset($_SESSION['softLogeoadmin'])){
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
      
   function setSession1($usuario,$caja,$tipo){
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
    <td width='20%' class='subtitulo' style='font-size:12px;' align='right'>$tipo DESTINO:</td>
    <td width='42%' style='font-size:11px;'>".substr($caja,0,28)."</td>
    <td width='20%' class='subtitulo' style='font-size:12px;'></td>
    <td width='18%' style='font-size:13px;'></td>
    </tr>
    </table>";	   
   }
   
   
   function setTotal($total){
	echo "
	<tr>
    <td width='9%' class='session_detalle_top'>&nbsp;</td>
    <td class='session_detalle_top'>&nbsp;</td>
    <td class='session_detalle_top'>&nbsp;</td>
    <td align='right' class='session_detalle_textoTotal'>Total:</td>
    <td width='12%' class='tablainiciototal' align='center'>".number_format($total,2)."</td>
  </tr>
	";   
   }
   
   function setPie(){
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
  $sql = "select numero,recibo,left(glosa,25)as 'glosa',left(p.cuenta,20)as 'nombre',ingresoBolivianos from traspasodinero t,plandecuenta p where t.cuenta='$codigoCuenta' and   t.idusuario='$_SESSION[id_usuario]' and fecha>='$desde' and fecha<='$hasta' and t.estado=1 and t.cuenta=p.codigo;";
  $result = $db->consulta($sql);
  $totalItem = $db->getnumRow($sql);
  $numFila = ($totalItem == 0) ? -1 : 0;
  $totalT = 0;
   while ($numFila < $totalItem ){
	if($numFila == -1)
       $numFila = 0;   
?>

<div style=" position : absolute;left:5%; top:20px;"></div>


<div class="margenPrincipal"></div>
<div class="session1_logotipo"><?php if ($logo == 'true'){ echo "<img src='../$empresa[imagen]' width='200' height='70'/>";} ?></div>
<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1"><?php echo "TRASPASO ENTRE $tipoReporte" ?></td></tr>
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
    <td width="48%" class="tablainiciotitulo" style="font-size:11px;">Glosa</td>
    <td width="21%" class="tablainiciotitulo" style="font-size:11px;">Caja Origen</td>
    <td class="tablainiciotitulo" style="border-right:1px solid #000;font-size:11px;">Importe</td>
  </tr>
  
  <?php 
  $i = 0;
   while($dato = mysql_fetch_array($result)){      
	  $totalT = $totalT + $dato[4];
	  echo "  
	  <tr>
		<td class='tablacontenidoLateral' align='center' style='font-size:11px;'>$dato[0]</td>
		<td class='tablacontenido_1' align='center' style='font-size:11px;'>$dato[1]</td>
		<td class='tablacontenido_1' style='font-size:11px;'>&nbsp;&nbsp;$dato[2]</td>
		<td class='tablacontenido_1' align='center' style='font-size:11px;'>$dato[3]</td>
		<td class='tablacontenido_1' style='border-right:1px solid #000;font-size:11px;' align='center'>".number_format($dato[4],2)."</td>
	  </tr>  
	  ";
	  $i++;
	  if ($i == 37)
	  break;
	}	
	$numFila = $numFila + $i;	
	for ($j=$i;$j<=37;$j++){		
		echo "  
    	  <tr>
	    	<td class='session_detalle_lizq'>&nbsp;</td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		    <td class='session_detalle_lder'>&nbsp;</td>
	      </tr>  
	      ";	
	}	
	setTotal($totalT);	
  ?>

</table> 
  <?php
   	setPie();	  	       
	   if ($numFila < $totalItem ){
	       for($h=0;$h<=48;$h++){
			echo "<br>";
		   }	  
	   }
    }
  ?>



</body>
</html>

<?php
$mpdf=new mPDF('utf-8','Letter'); 
$content = ob_get_clean();
$mpdf->SetHTMLHeader($header);
$mpdf->WriteHTML($content);
$mpdf->Output();
exit;

?>