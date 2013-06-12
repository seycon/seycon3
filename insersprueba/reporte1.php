<?php
  session_start();
  if (!isset($_SESSION['nombretrestaurante']) || !isset($_GET['idatencion']) || !isset($_GET['nroatencion'])){
    header("Location: index.php");	  
  }
  include("../conexion.php");
  $db = new MySQL();
  $idatencion = $_GET['idatencion'];
  $nroatencion = $_GET['nroatencion'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reporte Comanda</title>
<link rel="stylesheet" href="reporte.css" type="text/css" />
</head>

<body>
<div class="contornor1">
<table width="100%" border="0">
  <tr>
    <td width="34%">&nbsp;</td>
    <td width="24%">&nbsp;</td>
    <td width="17%">&nbsp;</td>
    <td width="17%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" align="center" class="textotipo1">DISCOTECA - BUFFALO</td>
  </tr>
  <tr>
    <td colspan="5" align="center" class="textotipo1"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2" class="textotipo1" align="center">Nº Venta:<?php echo " ". $idatencion;?></td>
    <td class="textotipo2"></td>
  </tr>
  <tr>
    <td align="right" class="textotipo1">Cliente:</td>
    <td class="textotipo2">Varios</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="textotipo2">Fecha: </td>
    <td class="textotipo2"><?php echo date("d/m/Y");?></td>
    <td align="right" class="textotipo2"></td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" align="right">
    <table width="80%" border="0" align="center" cellspacing="0">
  <tr>
    <td width="18%" align="center" class="textocabecera1">Cant.</td>
    <td width="40%" align="center" class="textocabecera1">Producto</td>
    <td width="22%" align="center" class="textocabecera1">P/U</td>
    <td width="20%" align="center" class="textocabecera1">P/Total</td>
  </tr>
  <?php
   $sql = "select left(c.nombre,25)as 'nombre',dc.precio,dc.cantidad from detalleatencion dc,
combinacion c where dc.idcombinacion=c.idcombinacion 
and dc.idatencion=$idatencion and nroatencion=$nroatencion and dc.estado=1;";
   $producto = $db->consulta($sql);
   $total = 0;
   while($data = mysql_fetch_array($producto)){
	  $totalU = $data['cantidad'] * $data['precio'];
	  $total = $total + $totalU;
	echo "
	   <tr>
    <td class='textotipo2' align='center'>$data[cantidad]</td>
    <td class='textotipo2'>$data[nombre]</td>
    <td class='textotipo2' align='center'>$data[precio]</td>
    <td class='textotipo2' align='center'>".number_format($totalU,2)."</td>
  </tr>
	";   
   }

  ?>
  

  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="total"><?php echo number_format($total,2);?></td>
  </tr>
</table>

    
    
    </td>
    </tr>
  <tr>
    <td colspan="2" align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="right">
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
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right" class="textotipo1">Pedido:</td>
    <td class="textotipo2"><?php echo $nroatencion;?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right" class="textotipo1">Hora:</td>
    <td><?php echo date("h:i:s");?></td>
    <td>&nbsp;</td>
  </tr>
</table>

</div>
</body>
</html>