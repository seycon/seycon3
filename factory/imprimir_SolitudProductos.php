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
  $codSolicitud = $_GET['idsolicitud'];	  
  $sql = "update solicitudF set estadoatencion='Atencion' where idsolicitud=$codSolicitud;";
  $db->consulta($sql);
  $logo = "true";
  $sql = "select s.idsolicitud,s.fecha,a.idalmacen,left(a.nombre,25)as 'almacen',s.detalle,left(concat(t.nombre,' ',t.apellido),30)as 'responsable' from solicitudF s,almacen a,usuarioF u,trabajador t  where s.idalmacen=a.idalmacen and a.idusuario=u.idusuario and u.idtrabajador=t.idtrabajador and s.idsolicitud=".$codSolicitud.";";
  $datosGenerales = $db->arrayConsulta($sql);
  $fecha = explode("-",$datosGenerales['fecha']);
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
 $sql = "select p2.idproducto,p2.nombre,ds.pedido,p2.stockminimo, 
		(select sum(d.cantidadactual) as 'cantidad'  
		from ingresoproducto i,producto p,detalleingresoproducto d where 
		p.idproducto=d.idproducto and d.idingresoprod=i.idingresoprod and i.idalmacen=$datosGenerales[idalmacen] 
		and p.idproducto=ds.idproducto group by p.idproducto) as 'disponible' from detallesolicitudF ds,producto p2 
		where ds.idsolicitud=$codSolicitud and p2.idproducto=ds.idproducto order by ds.iddetallesolicitud;";
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
   <tr><td class="session1_titulo_num"><?php echo "Nº $codSolicitud"; ?></td></tr> 
 </table>
</div>
<div class="session1_logotipo"><?php if ($logo == 'true'){ echo "<img src='../$empresa[imagen]' width='200' height='70'/>";} ?></div>
<div class="session1_contenedorTitulos">
  <table width="100%" border="0">
   <tr><td align="center" class="session1_titulo1">SOLICITUD DE PRODUCTOS</td></tr> 
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
    <td align="right" class="negrita">Fecha:</td>
    <td><?php echo $fecha[2]." de ".$db->mes($fecha[1])." del ".$fecha[0];?> </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right" class="negrita">Almacen:</td>
    <td><?php echo $datosGenerales['almacen']; ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="negrita">Trabajador:</td>
    <td><?php echo $datosGenerales['responsable'];?>    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
    <td>&nbsp;</td>
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
    <td  class="cabecera">PRODUCTOS</td>
    <td width="105" class="cabecera">Disponible</td>
    <td width="111" class="cabecera">Stock Minimo</td>
    <td width="96" class="cabecera">Faltante</td>
    <td width="114" class="cabecera" style="border-right:solid 1px #000;">Pedir</td>
  </tr>
  
  <?php
 
   $contador = 0;
   while ( $detalleSolicitud = mysql_fetch_array($cons)){
    $contador++;	
	$contadorFila++; 
	$faltante = $detalleSolicitud['stockminimo'] - $detalleSolicitud['disponible'];
   
	$totalcantidad = $totalcantidad + $detalleSolicitud['pedido'];
	
	 if ($contador==$maxFila || $contador == $cantidad ){
	   echo "<tr>";
        echo "<td style='border-bottom:solid 1px #000;border-left:solid 1px #000;'>$detalleSolicitud[nombre]</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;text-align:center'>&nbsp;$detalleSolicitud[disponible]</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;text-align:center'>$detalleSolicitud[stockminimo]</td>";
		echo "<td class='contenido' style='border-bottom:solid 1px #000;text-align:center'>$faltante</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;border-right:solid 1px #000;text-align:center'>".$detalleSolicitud['pedido']."</td>";
       echo "</tr>";
	   break;
	 }
	else{
      echo " <tr>";
          echo " <td style='border-bottom:solid 1px #000;border-bottom-style:dotted;border-left:solid 1px #000;'>$detalleSolicitud[nombre]</td>";
          echo " <td class='contenido' style='text-align:center;'>&nbsp;$detalleSolicitud[disponible]</td>";
          echo " <td class='contenido' style='text-align:center;'>$detalleSolicitud[stockminimo]</td>";
		  echo "<td class='contenido'  style='text-align:center;'>$faltante</td>";
          echo " <td class='contenido' style='border-right:solid 1px #000;text-align:center;'>".$detalleSolicitud['pedido']."</td>";
      echo "</tr>";
	}  
  
   }
  
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
    <td colspan="2"><?php echo $datosGenerales['detalle'];?></td>
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