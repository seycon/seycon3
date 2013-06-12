<?php
  session_start();
  if (!isset($_SESSION['idusuarioF']) || !isset($_GET['idatencion'])){
    header("Location: index.php");	  
  }  
  include("../conexion.php");
  $db = new MySQL();
  $idatencion = $_GET['idatencion'];

  
  $sql = "select *from atencion where idatencion=$idatencion";
  $atencion = $db->arrayConsulta($sql);
  
  
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
    <td width="29%">&nbsp;</td>
    <td width="36%">&nbsp;</td>
    <td width="15%">&nbsp;</td>
    <td width="18%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" align="center" class="textotipo1">SUCURSAL - BISTRON</td>
  </tr>
  <tr>
    <td colspan="5" align="center" class="textotipo1">COBRO MESA</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="textotipo1">Nro Venta:</td>
    <td class="textotipo2">
	<?php 
	 $sql = "select idnotaventa from notaventaF where idatencion=$idatencion";
	 $result = $db->arrayConsulta($sql);
	 echo $result['idnotaventa'];
	?></td>
    <td align="right"></td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="textotipo2">Fecha: </td>
    <td><?php echo date("d/m/Y");?></td>
    <td align="right" class="textotipo2">Hora:</td>
    <td><?php echo date("h:i:s");?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" align="right">
    <table width="80%" border="0" align="center">
  <tr>
    <td width="18%" align="center" class="textotipo1">Cant.</td>
    <td width="42%" align="center" class="textotipo1">Producto</td>
    <td width="20%" align="center" class="textotipo1">P/U</td>
    <td width="20%" align="center" class="textotipo1">P/Total</td>
  </tr>
  <?php
   $sql = "select da.iddetallenotaf,left(c.nombre,25)as 'nombre',da.cantidad,da.precio,da.nroatencion from detallenotaF da,combinacion c,notaventaF n where 
					 da.idcombinacion=c.idcombinacion and da.idnotaventa=n.idnotaventa and n.idatencion=$idatencion  
					 and da.estado=1 order by da.iddetallenotaf;";
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
    <td align="center"><?php echo $_SESSION['nombreusuarioF'];?></td>
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
    <td align="right" class="textotipo1"></td>
    <td class="textotipo2"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

</div>
</body>
</html>