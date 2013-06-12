<?php
ob_start();
include('../../conexion.php');
include('../../aumentaComa.php');
include("../../MPDF53/mpdf.php");
$db = new MySQL();

$idalmacen = $_POST['idalmacen'];
$formapago = $_POST['formapago'];
$fechaini  = $db->GetFormatofecha($_POST['fechainicio'],'/');
$fechafin  = $db->GetFormatofecha($_POST['fechafin'],'/');

  if ($idalmacen != 0){
	  $Calmacen = " and ip.idalmacen=$idalmacen ";
  }
  if ($fechaini != ''){
	  $Cfechainicio = " and fecha>='$fechaini' ";
  }
  if ($fechafin != ''){
	  $Cfechafin = " and fecha<='$fechafin' ";
  }
  
  $sql = "select *from empresa";
  $empresa = $db->arrayConsulta($sql);
  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="inventario.css" type="text/css" />
<title>Ingreso de Productos</title>
</head>

<body>
<table width="731" border="0" align="center">
  <tr>
    <td width="200" align="center" class="titulos"><img  src="../../<?php echo $empresa['imagen']?>" width="200" height="70"/></td>
    <td width="521" align="center" class="titulos">INGRESO DE PRODUCTOS POR NOTAS</td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="734" border="0" align="center" cellspacing="0" cellpadding="0" class="tabla">
  <tr>
    <td width="59"  class="tituloTabla">Nº</td>
    <td width="375" class="tituloTabla">Producto</td>
    <td width="97"  class="tituloTabla">Cantidad</td>
    <td width="87"  class="tituloTabla">Precio</td>
    <td width="94"  class="tituloTabla_final">Total</td>
  </tr> 
</table>

<table width="734" cellspacing="0" cellpadding="0" align="center" class="tablaDatos">
<?php
 $sql = "SELECT ip.idingresoprod, p.nombre, di.cantidad, round(p.precio,2), round(di.total,2)
FROM ingresoproducto ip, detalleingresoproducto di, producto p
WHERE ip.idingresoprod = di.idingresoprod
AND p.idproducto = di.idproducto
AND ip.estado =1 ".$Calmacen." and ip.formadepago='$formapago' ".$Cfechainicio." ".$Cfechafin;
  
 $dato = $db->consulta($sql);
 
 while($producto = mysql_fetch_array($dato)){   
 echo " <tr>
    <td width='59'  align='center' class='tablacontenidoLateral'>$producto[idingresoprod]</td>
    <td width='375' class='tablacontenido_1'>$producto[nombre]</td>
    <td width='97'  class='tablacontenido_1' align='center'>$producto[cantidad]</td>
    <td width='87'  class='tablacontenido_1' align='center'>".convertir($producto[3])."</td>
    <td width='94'  class='tablacontenido_1' align='center'>".convertir($producto[4])."</td>
      </tr>";  
 }
  
?>  
 </table>
 
 <div style="position:absolute; border:1px solid #000; width:95%; height:95%; top:20px; left:20px;"></div>
 
<p>&nbsp;</p>
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