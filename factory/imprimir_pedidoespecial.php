<?php
session_start();
if (!isset($_SESSION['idusuarioF'])){
       header("Location: index.php");	
}
ob_start();
include("../MPDF53/mpdf.php");
include('../conexion.php');
  $db = new MySQL();
//  $logo = $_GET['logo'];
  $codigo = $_GET['idpedido'];	
  $logo = "true";
  $sql = "select left(nombre,25)as 'nombre',telefono,pirotines,masa,crema,relleno,acuenta,
  left(glosa,50)as 'glosa' from pedidoespecialF where idpedido=".$codigo.";";
  $datosGenerales = $db->arrayConsulta($sql);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reporte de Solicitud Productos</title>
<link rel="stylesheet" type="text/css" href="reporteSolicitud.css" />
</head>

<body>

<?php
 $totalcantidad = 0;

 $limite = "1";
 $maxFila = 35;
 $sql = "select c.idcupcakes,left(c.nombre,40)as 'nombre',d.tipo,d.precio,d.cantidad from detallepedidoF d,cupcakesF c where 
         d.idcupcakes=c.idcupcakes and d.idpedido=$codigo order by iddetallepedido asc";
 $cons = mysql_query($sql);
 $cantidad = mysql_num_rows($cons);
 $contadorFila = 0;  
 $sql = "select *from empresa";
 $empresa = $db->arrayConsulta($sql); 
  
  
  //while ($limite!="") {
?>


<div class="borde"></div>
<div class="session1_numTransaccion">
 <table width="100%" border="0">
   <tr><td align="center" height="3"></td></tr> 
   <tr><td class="session1_titulo_num"><?php echo "Nº $codigo"; ?></td></tr> 
 </table>
</div>
<div class="session1_logotipo"><?php if ($logo == 'true'){ echo "<img src='../$empresa[imagen]' width='200' height='70'/>";} ?></div>
<div class="session1_contenedorTitulos">
  <table width="100%" border="0">
   <tr><td align="center" class="session1_titulo1">PEDIDO ESPECIAL</td></tr> 
  </table>
</div>
 

<table align="center">
<tr><td>


<div style="border:solid 1px #000;width:90%;margin:0 auto;">

<table width="90%" border="0" align="center">
  <tr>
    <td colspan="2">
    </td>
    <td width="165">&nbsp;</td>
    <td width="115">&nbsp;</td>
    <td width="115">&nbsp;</td>
    <td width="115">&nbsp;</td>
    <td width="115">&nbsp;</td>
    <td width="115">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" align="center">
  <tr>
    <td width="144">&nbsp;</td>
    <td width="285">&nbsp;</td>
    <td width="16">&nbsp;</td>
    <td width="8">&nbsp;</td>
    <td width="12">&nbsp;</td>
    <td width="78">&nbsp;</td>
    <td width="187">&nbsp;</td>
    <td width="57">&nbsp;</td>
  </tr>
  <tr> 
    <td align="right" class="negrita">Señor(es):</td>
    <td><?php echo $datosGenerales['nombre'];?> </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right" class="negrita">Telefono:</td>
    <td><?php echo $datosGenerales['telefono']; ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="negrita">Pirotines:</td>
    <td><?php echo $datosGenerales['pirotines']; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right" class="negrita">Masa:</td>
    <td><?php echo $datosGenerales['masa']; ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="negrita">Crema:</td>
    <td><?php echo $datosGenerales['crema'];?>    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right" class="negrita">Relleno:</td>
    <td><?php echo $datosGenerales['relleno']; ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td ></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  </table>


<table width="120%" border="0" align="center" cellspacing="0px">
  <tr bgcolor="#E6E6E6">
    <td  class="cabecera">CUPCAKES</td>
    <td width="105" class="cabecera">Tipo</td>
    <td width="111" class="cabecera">Cantidad</td>
    <td width="96" class="cabecera">Precio</td>
    <td width="114" class="cabecera" style="border-right:solid 1px #000;">Total</td>
  </tr>
  
  <?php
 
   $contador = 0;
   while ( $detalleSolicitud = mysql_fetch_array($cons)){
    $contador++;	
	$contadorFila++; 
	$total = $detalleSolicitud['cantidad'] * $detalleSolicitud['precio'];
   
	$totalcantidad = $totalcantidad + $total;
	
	 if ($contador==$maxFila || $contador == $cantidad ){
	   echo "<tr>";
        echo "<td style='border-bottom:solid 1px #000;border-left:solid 1px #000;'>$detalleSolicitud[nombre]</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;text-align:center'>&nbsp;$detalleSolicitud[tipo]</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;text-align:center'>$detalleSolicitud[cantidad]</td>";
		echo "<td class='contenido' style='border-bottom:solid 1px #000;text-align:center'>".number_format($detalleSolicitud['precio'],2)."</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;border-right:solid 1px #000;text-align:center'>".number_format($total,2)."</td>";
       echo "</tr>";
	   break;
	 }
	else{
      echo " <tr>";
          echo " <td style='border-bottom:solid 1px #000;border-bottom-style:dotted;border-left:solid 1px #000;'>$detalleSolicitud[nombre]</td>";
          echo " <td class='contenido' style='text-align:center;'>&nbsp;$detalleSolicitud[tipo]</td>";
          echo " <td class='contenido' style='text-align:center;'>$detalleSolicitud[cantidad]</td>";
		  echo "<td class='contenido'  style='text-align:center;'>".number_format($detalleSolicitud['precio'],2)."</td>";
          echo " <td class='contenido' style='border-right:solid 1px #000;text-align:center;'>".number_format($total,2)."</td>";
      echo "</tr>";
	}  
  
   }
  
   $saldo = $totalcantidad - $datosGenerales['acuenta'];
  ?>
  
  <tr>
   <td width="347" >&nbsp;</td>
    <td width="105" >&nbsp;</td>
    <td class="negrita" align="right" >&nbsp;</td>
    <td align="right" class="negrita">Total</td>
    <td width="114" style="border-bottom:solid 1px #000;border-left:solid 1px #000;border-right:solid 1px #000;text-align:center;background:#E6E6E6;">
	<?php echo number_format($totalcantidad,2);?></td>
  </tr>
  <tr>
  <td align="right"></td>
  <td colspan="5"></td>
  </tr>
  <tr>
   <td width="347" >&nbsp;</td>
    <td width="105" >&nbsp;</td>
    <td class="negrita" align="right" >&nbsp;</td>
    <td align="right" class="negrita">A cuenta:</td>
    <td width="114" style="border:solid 1px #000;text-align:center;background:#E6E6E6;">
	<?php echo number_format($datosGenerales['acuenta'],2);?></td>
  </tr>
  <tr>
  <td align="right"></td>
  <td colspan="5"></td>
  </tr>
  <tr>
   <td width="347" >&nbsp;</td>
    <td width="105" >&nbsp;</td>
    <td class="negrita" align="right" >&nbsp;</td>
    <td align="right" class="negrita">Saldo:</td>
    <td width="114" style="border:solid 1px #000;text-align:center;background:#E6E6E6;">
	<?php echo number_format($saldo,2);?></td>
  </tr>
  <tr>
  <td align="right"></td>
  <td colspan="5"></td>
  </tr>

</table>

</div>
</td></tr></table>

<?php
 /*  if($contadorFila>=$cantidad ){
	   $limite = "";
   }
   else{
	echo "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";   
	   
   }*/
//	}
?>


 <table width="90%" border="0" align="center">
  <tr>
    <td width="133" class="negrita" align="left">Glosa:</td>
    <td width="749">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $datosGenerales['glosa'];?></td>
  </tr>
</table>
<table width="100%" border="0">
  <tr>
    <td height="15">&nbsp;</td>
    <td>&nbsp;</td>
  </tr> 
</table>

<div class="contenedor1"></div>
<div style="position:absolute;top:66%;left:21%;width:8.3%;">
<div class="circule"></div>
<div class="espacio"></div>
<div class="circule"></div>
<div class="espacio"></div>
<div class="circule"></div>
</div>
<div style="position:absolute;top:66%;left:33%;width:8.3%;">
<div class="circule"></div>
<div class="espacio"></div>
<div class="circule"></div>
<div class="espacio"></div>
<div class="circule"></div>
</div>


<div class="contenedor2"></div>
<div style="position:absolute;left:58%;width:21%;height:23%;top:68%;">
<div class="torta1"></div>
<div class="torta2"></div>
<div class="torta3"></div>
</div>




<table width="80%" border="0" align="center">
  <tr>
    <td><table width="100%" border="0">
      <tr>
        <td width="48%" height="41"></td>
        <td width="52%"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
    <td>
    
    <table width="100%" border="0">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
    
    </td>
  </tr>
</table>



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
 
</body>
</html>

<?php
$mpdf=new mPDF('utf-8','Letter'); 
$content = ob_get_clean();
$mpdf->WriteHTML($content);
$mpdf->Output();
exit;
?>