<?php
  session_start();
  
  include("../conexion.php");
  $db = new MySQL();
  $idatencion = $_GET['idatencion'];
//  $idatencion = 17;
  
  $sql = "select *from atencion where idatencion=$idatencion";
  $atencion = $db->arrayConsulta($sql);
  if($atencion['idtrabajador'] != 0){
	$sql = "select left(concat(nombre,' ',apellido),25)as 'nombre' from trabajador where idtrabajador=$atencion[idtrabajador]";  
	$resultado = $db->arrayConsulta($sql);
	$nombreTrabajador = $resultado['nombre'];
  }else{
	$nombreTrabajador = "Varios";	  
  }
  
  if ($atencion['credito'] == 1){
	$tipoVenta = "Credito";  
  }else{
	$tipoVenta = "Contado";  
  }
  
  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cobro de Mesa</title>
<link rel="stylesheet" href="reporte.css" type="text/css" />
</head>

<body>
<div class="contornor1">
<table width="100%" border="0">
  <tr>
    <td colspan="2">&nbsp;</td>
    <td width="37%">&nbsp;</td>
    <td width="12%">&nbsp;</td>
    <td width="15%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6" align="center" class="textotipo1">DISCOTECA BUFFALO</td>
  </tr>
  <tr>
    <td colspan="6" align="center" class="textotipo2">CIERRE DE MESA</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <td colspan="2" align="right" class="textotipo3">CLIENTE:</td>
    <td colspan="2"><?php echo $nombreTrabajador;?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="8%" align="right" >&nbsp;</td>
    <td width="26%" align="right" class="textocabecera2">Fecha:</td>
    <td class="textocabecera2"><?php echo date("d/m/Y");?></td>
    <td class="textocabecera2" align="right">Hora:</td>
    <td class="textocabecera2"><?php echo date("h:i:s");?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="textotipo2">&nbsp;</td>
    <td align="right" class="textocabecera3">Nº de Venta:</td>
    <td class="textocabecera3"><?php echo $idatencion;?></td>
    <td align="right" class="textocabecera3">Tipo:</td>
    <td class="textocabecera3"><?php echo $tipoVenta;?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="right"></td>
    <td colspan="2"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6" align="right">
    <table width="80%" border="0" align="center"  cellspacing="1">
  <tr>
    <td width="18%" align="center" class="textoCabeceraAbajo">Cant.</td>
    <td width="40%" align="center" class="textoCabeceraAbajo">Producto</td>
    <td width="22%" align="center" class="textoCabeceraAbajo">P/U</td>
    <td width="20%" align="center" class="textoCabeceraAbajo">P/Total</td>
  </tr>
  <?php
   $sql = "select left(c.nombre,25)as 'nombre',dc.precio,dc.cantidad from detalleatencion dc,
combinacion c where dc.idcombinacion=c.idcombinacion 
and dc.idatencion=$idatencion and dc.estado=1;";
   $producto = $db->consulta($sql);
   $total = 0;
   while($data = mysql_fetch_array($producto)){
	  $totalU = $data['cantidad'] * $data['precio'];
	  $total = $total + $totalU;
	echo "
	   <tr>
    <td class='textotipo2' align='center'>$data[cantidad]</td>
    <td class='textotipo2'>$data[nombre]</td>
    <td class='textotipo2' align='center'>".number_format($data['precio'],2)."</td>
    <td class='textotipo2' align='center'>".number_format($totalU,2)."</td>
  </tr>
	";   
   }

  ?>
  

  <tr>
    <td>&nbsp;</td>
    <td colspan="2" align="right" class="textotipo3">TOTAL A ENTREGAR: </td>
    <td class="total"><?php echo number_format($total,2);?></td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td colspan="2" align="right" class="textotipo3">DESCUENTO: </td>
    <td class="totalCuadro"><?php echo number_format($atencion['descuento'],2);?></td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td colspan="2" align="right" class="textotipo3">EFECTIVO BS.: </td>
    <td class="totalCuadro"><?php echo number_format($atencion['efectivo'],2);?></td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td colspan="2" align="right" class="textotipo3">CORTESIA/VALE: </td>
    <td class="totalCuadro"><?php echo number_format($atencion['cortesia'],2);?></td>
  </tr>
</table>

    
    
    </td>
    </tr>
  <tr>
    <td colspan="3" align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="right">
    <table width="80%" border="0" align="center">
  <tr>
    <td class="firma">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><?php echo $_SESSION['nombretrestaurante'];?></td>
  </tr>
</table>

    
    </td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right" class="textotipo1"></td>
    <td class="textotipo2"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

</div>
</body>
</html>