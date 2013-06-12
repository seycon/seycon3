<?php
  session_start();
  
  include("../conexion.php");
  $db = new MySQL();
  $fechaReporte = $db->GetFormatofecha($_GET["fecha"],'/');
  $idusuario = $_GET['idusuario'];
  $entregado = $_GET['entregado'];
  $faltante = $_GET['faltante'];
  
  $sql = "select *from usuariorestaurante where idusuario=$idusuario;";
  $datos = $db->arrayConsulta($sql);
  
   
  if ($datos['tipo'] == "fijo"){
	 $sql = "select left(concat(t.nombre,' ',t.apellido),20)as 'nombre' from trabajador t where t.idtrabajador=$datos[idtrabajador];"; 
  }else{
	 $sql = "select left(concat(t.nombre,' ',t.apellido),20)as 'nombre' from personalapoyo t where t.idpersonalapoyo=$datos[idtrabajador];"; 
  }
 	 $garzon = $db->arrayConsulta($sql); 
   $sql = "select max(nroatencion) as 'nro' from detalleatencion d,atencion a where  d.idatencion=a.idatencion 
   and date(a.fecha)='$fechaReporte' and a.idusuariorestaurante=$idusuario;";  
   $pedidos = $db->arrayConsulta($sql);
   
   $pedidos = ($pedidos['nro'] == "") ? 0 : $pedidos['nro'];
   
   $turno = ($datos['turno'] == "AM") ? "Día" : "Noche";
   
   $sql = "select sum(cortesia) as 'total' from atencion where date(fecha)='$fechaReporte' and 
   idusuariorestaurante=$idusuario group by idusuariorestaurante;";
   $cortesia = $db->arrayConsulta($sql);
   $cortesia = ($cortesia['total'] == "") ? 0 : $cortesia['total'];
  
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
    <td width="36%">&nbsp;</td>
    <td width="15%">&nbsp;</td>
    <td width="18%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6" align="center" class="textotipo1">REPORTE POR GARZON</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="right" class="textotipo1">Garzon:</td>
    <td colspan="2" class="textotipo2"><?php echo $garzon['nombre'];?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="6%" align="right" >&nbsp;</td>
    <td width="23%" align="right" class="textocabecera2">Fecha: </td>
    <td class="textocabecera2"><?php echo date("d/m/Y");?></td>
    <td align="right" class="textocabecera2">Turno:</td>
    <td class="textocabecera2"><?php echo $turno;?></td>
    <td>&nbsp;</td>
  </tr>
  
    <tr>
    <td width="6%" align="right" >&nbsp;</td>
    <td  align="right" class="textocabecera3"></td>
    <td colspan="2" align="right" class="textocabecera3">Nº Pedidos:</td>
    <td class="textocabecera3"><?php echo $pedidos;?></td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="6" align="right">
      <table width="80%" border="0" align="center" cellspacing="1">
        <tr>
          <td width="18%" align="center" class="textoCabeceraAbajo">Cant.</td>
          <td width="40%" align="center" class="textoCabeceraAbajo">Descripcion</td>
          <td width="22%" align="center" class="textoCabeceraAbajo">P/U</td>
          <td width="20%" align="center" class="textoCabeceraAbajo">P/Total</td>
          </tr>
        <?php
       $sql = "select left(c.nombre,25)as 'nombre',dc.precio,dc.cantidad from detalleatencion dc,atencion a,
       combinacion c where dc.idcombinacion=c.idcombinacion and dc.idatencion=a.idatencion and a.idusuariorestaurante=$idusuario and credito=0 and 
	   dc.estado=1 and date(a.fecha)='$fechaReporte';";
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
          <td align="right" colspan="2" class="textotipo2">TOTAL VENTA:</td>
          <td class="total" align="center"><?php echo number_format($total,2);?></td>
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
    <td colspan="3" align="left" class="textotipo1">ANULADOS</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" align="right"><table width="80%" border="0" align="center">
      <tr>
        <td width="18%" align="center" class="textocabecera1">Cant.</td>
        <td width="40%" align="center" class="textocabecera1">Descripcion</td>
        <td width="22%" align="center" class="textocabecera1">Pedido</td>
       </tr>
      <?php
       $sql = "select left(c.nombre,25)as 'nombre',dc.nroatencion,dc.cantidad from detalleatencion dc,atencion a,
       combinacion c where dc.idcombinacion=c.idcombinacion and dc.idatencion=a.idatencion and a.idusuariorestaurante=$idusuario and dc.estado=0 
	   and date(a.fecha)='$fechaReporte';";
   $producto = $db->consulta($sql);
   $total = 0;
   while($data = mysql_fetch_array($producto)){
	  $totalU = $data['cantidad'] * $data['precio'];
	  $total = $total + $totalU;
	echo "
	   <tr>
    <td class='textotipo2' align='center'>$data[cantidad]</td>
    <td class='textotipo2'>$data[nombre]</td>
    <td class='textotipo2' align='center'>$data[nroatencion]</td>
  </tr>
	";   
   }

  ?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
          </tr>
    </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="textotipo1">AJUSTES</td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" align="right"><table width="80%" border="0" align="center">
      <tr>
        <td colspan="2" align="right" class="textotipo2">Vale/Cortesia:(-)</td>
        <td class="totalCuadro"><?php echo  number_format($cortesia,2);?></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="right" class="textotipo2">Anuladas:(-)</td>
        <td class="totalCuadro">0.00</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td ></td>
        <td height="3" colspan="3" class="linea1"></td>
        </tr>
      <tr>
        <td colspan="2" align="right" class="textotipo2">Entrego Bs.:</td>
        <td class="totalCuadro"><?php echo number_format($entregado,2);?></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="54%" colspan="2" align="right" class="textotipo2">Faltante Bs.:</td>
        <td width="39%" class="totalCuadro"><?php echo number_format($faltante,2);?></td>
        <td width="7%">&nbsp;</td>
      </tr>
    </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="right">&nbsp;</td>
    <td>&nbsp;</td>
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
    <td colspan="3" align="right">&nbsp;</td>
    <td align="right" class="textotipo2">Hora:</td>
    <td class="textotipo2"><?php echo date("h:i:s");?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="right">&nbsp;</td>
    <td align="right" class="textotipo2"></td>
    <td class="textotipo2"></td>
    <td>&nbsp;</td>
  </tr>
</table>

</div>
</body>
</html>