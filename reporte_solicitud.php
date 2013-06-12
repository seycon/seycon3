<?php
session_start();
include('conexion.php');
include('../aumentaComa.php');

	 $db = new MySQL();
	 $codSolicitud = "1";
	 
	 function mes($dato){
	  switch ($dato) {
         case '01':
          return "ENERO";
          break;
         case '02':
           return "FEBRERO";
           break;
         case '03':
           return "MARZO";
           break;
		 case '04':
           return "ABRIL";
           break;
		 case '05':
           return "MAYO";
           break;
		 case '06':
           return "JUNIO";
           break;
		 case '07':
           return "JULIO";
           break;
		 case '08':
           return "AGOSTO";
           break;     
		 case '09':
           return "SEPTIEMBRE";
           break;       
		 case '10':
           return "OCTUBRE";
           break;     
		 case '11':
           return "NOVIEMBRE";
           break;     
		 case '12':
           return "DICIEMBRE";
           break;       
		       
      }
	 }
	 
  $consulta = mysql_query("select p.tiempodecredito,p.idproveedor,s.idsolicitud,year(s.fecha)as 'anio',month(s.fecha)as 'mes',day(s.fecha)as 'dia',s.moneda,s.glosa,a.nombre as 'almacen',p.nombre as 'proveedor' from solicitud s,proveedor p,almacen a  where s.idalmacen=a.idalmacen and s.idproveedor=p.idproveedor and s.idsolicitud=".$codSolicitud.";" );
  $datosGenerales = mysql_fetch_array($consulta);
 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>

<style>
.negrita{
font-weight:bold;	
}

.cabecera{
	border-left:solid 1px #000;border-bottom:solid 1px 
    #000;border-top:solid 1px #000;font-weight:bold;
}

.contenido{
    border-left:solid 1px #000;border-bottom:solid 1px #000;border-left-style:dotted;border-bottom-style:dotted;	
}
</style>

</head>

<body>

<table align="center">
<tr><td>

<div style="border:solid 1px #000;width:90%;margin:0 auto;">

<table width="90%" border="0" align="center">
  <tr>
    <td colspan="2">
    <?php 
	  $consulta = mysql_query("select nombrecomercial,ciudad  from empresa where idempresa=1");
	  $dato = mysql_fetch_array($consulta);
	  echo "<div style='font-size:20px;text-align:center'>$dato[nombrecomercial]</div>";
	?>
    
    </td>
    <td width="165">&nbsp;</td>
    <td width="115">&nbsp;</td>
    <td width="115">&nbsp;</td>
    <td width="115">&nbsp;</td>
    <td width="115">&nbsp;</td>
    <td width="115">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><?php echo "<div style='font-size:18px;text-align:center'>$dato[ciudad] </div>" ?> </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="115">&nbsp;</td>
    <td width="65">&nbsp;</td>
    <td colspan="4"><div style="font-size:28px;text-align:center;color:#999;">SOLICITUD DE PRODUCTOS</div></td>
    <td><div style="background:#CCC;border:solid 2px #000;width:90%;height:100%;text-align:center;">&nbsp;&nbsp;Nº&nbsp;&nbsp; <?php  echo $datosGenerales['idsolicitud']; ?></div></td>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" align="center">
  <tr>
    <td width="153">&nbsp;</td>
    <td width="322">&nbsp;</td>
    <td width="19">&nbsp;</td>
    <td width="9">&nbsp;</td>
    <td width="14">&nbsp;</td>
    <td width="128">&nbsp;</td>
    <td width="137">&nbsp;</td>
    <td width="85">&nbsp;</td>
  </tr>
  <tr>
 
    <td><div align="right" class="negrita">Fecha:</div></td>
    <td><?php
	      echo $datosGenerales['dia']." de ".mes($datosGenerales['mes'])." del ".$datosGenerales['anio'];
	    ?>    
    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right" class="negrita">T.C.:</div></td>
    <td><?php  
	$indicador = mysql_query("select dolarcompra, ufv from indicadores order by idindicador desc limit 1");
	$valores  = mysql_fetch_array($indicador);
	echo  $valores['dolarcompra'];
	?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right" class="negrita">Moneda:</div></td>
    <td><?php
	      echo $datosGenerales['moneda'];
	    ?>    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right" class="negrita">U.F.V.:</div></td>
    <td><?php 
		echo  $valores['ufv'];
	?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right" class="negrita">Proveedor:</div></td>
    <td><?php
	      echo $datosGenerales['proveedor'];
	    ?> </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right" class="negrita">Tiempo de Credito:</div></td>
    <td><?php
          echo $datosGenerales['tiempodecredito'];
	?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right"><span class="negrita">Almacen:</span></div></td>
    <td><?php
	      echo $datosGenerales['almacen'];
	    ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right" class="negrita"></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="95%" border="0" align="center" cellspacing="0px">
  <tr>
    <td width="51" class="cabecera"><div align="center" >Nº</div></td>
    <td width="100" class="cabecera"><div align="center" class="negrita">Codigo Proveedor</div></td>
    <td width="232" class="cabecera"><div align="center" class="negrita">Descripcion</div></td>
    <td width="109" class="cabecera"><div align="center" class="negrita">Unidad de Medida</div></td>
    <td width="114" class="cabecera"><div align="center" class="negrita">Cantidad</div></td>
    <td width="116" class="cabecera"><div align="center" class="negrita">Precio/Unitario</div></td>
    <td width="108" class="cabecera" style="border-right:solid 1px #000;"><div align="center" class="negrita">Importe</div></td>
  </tr>
  <?php
  $cons = mysql_query("SELECT p.idproducto, p.nombre, p.precio, p.unidaddemedida, cantidad, total
   FROM detallesolicitud ds, producto p, solicitud s WHERE ds.idsolicitud = s.idsolicitud AND ds.idproducto = p.idproducto
   AND s.idsolicitud =".$codSolicitud);
   $cantidad = mysql_num_rows($cons);
   $contador = 0;
   $totalPrecio = 0;
   $totalImporte = 0;
   
   while ( $detalleSolicitud = mysql_fetch_array($cons)){
    $contador = $contador +1 ;
	$totalPrecio = $totalPrecio + $detalleSolicitud['precio'];
	$totalImporte = $totalImporte + $detalleSolicitud['total'];
	 if ($contador==$cantidad){
	  echo "<tr>";
        echo "<td style='border-bottom:solid 1px #000;border-bottom-style:dotted;border-left:solid 1px #000;border-bottom:solid 1px #000;text-align:center'>$contador</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;text-align:center'>$datosGenerales[idproveedor]</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;'>$detalleSolicitud[nombre]</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;text-align:center'>&nbsp;$detalleSolicitud[unidaddemedida]</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;text-align:center'>$detalleSolicitud[cantidad]</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;text-align:center'>$detalleSolicitud[precio]</td>";
        echo "<td class='contenido' style='border-bottom:solid 1px #000;border-right:solid 1px #000;text-align:center'>$detalleSolicitud[total]</td>";
      echo "</tr>";
	 }
	else{
      echo " <tr>";
          echo " <td style='border-bottom:solid 1px #000;border-bottom-style:dotted;border-left:solid 1px #000;text-align:center'>$contador</td>";
          echo " <td class='contenido' style='text-align:center;'>$datosGenerales[idproveedor]</td>";
          echo " <td class='contenido'>$detalleSolicitud[nombre]</td>";
          echo " <td class='contenido' style='text-align:center;'>&nbsp;$detalleSolicitud[unidaddemedida]</td>";
          echo " <td class='contenido' style='text-align:center;'>$detalleSolicitud[cantidad]</td>";
          echo " <td class='contenido' style='text-align:center;'>$detalleSolicitud[precio]</td>";
          echo " <td class='contenido' style='border-right:solid 1px #000;text-align:center;'>$detalleSolicitud[total]</td>";
      echo "</tr>";
	}
  
  
   }
  
  ?>
  
  <tr>
   <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td class="negrita" style="text-align:right">Totales</td>
    <td style="border-bottom:solid 1px #000;border-left:solid 1px #000;text-align:center;"><?php echo $totalPrecio;?></td>
    <td style="border-bottom:solid 1px #000;border-left:solid 1px #000;border-right:solid 1px #000;text-align:center"><?php echo $totalImporte;?></td>
  </tr>


</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center">
  <tr>
    <td width="133" class="negrita">Glosa:</td>
    <td width="749">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $datosGenerales['glosa'];?></td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center">
  <tr>
    <td width="30">&nbsp;</td>
    <td width="132" class="negrita">.................................</td>
    <td width="105">&nbsp;</td>
    <td width="120" class="negrita">..............................</td>
    <td width="142">&nbsp;</td>
    <td width="126" class="negrita">................................</td>
    <td width="313">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center" class="negrita">Elaborado Por</div></td>
    <td>&nbsp;</td>
    <td><div align="center" class="negrita">Proveedor</div></td>
    <td>&nbsp;</td>
    <td><div align="center" class="negrita">Vo.Bo.</div></td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>

</div>
</td></tr></table>

</body>
</html>


