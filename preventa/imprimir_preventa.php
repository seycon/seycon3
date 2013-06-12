<?php
	session_start();
	if (!isset($_SESSION['softLogeoadmin'])) {
        header("Location: ../index.php");	
	}
	ob_start();
	include("../MPDF53/mpdf.php");
	include("../conexion.php");
	include('../reportes/literal.php');
	$db = new MySQL();
    $titulo = "PRE VENTA";

	$logo = $_GET['logo'];
	$sql = "select imagen,left(nombrecomercial,13)as 'nombrecomercial',nit from empresa;";
	$datoGeneral = $db->arrayConsulta($sql);
	$idpreventa = $_GET['idpreventa'];

	$sql = "select p.fecha,p.fechaentrega,p.modalidad,p.moneda,p.tipocambio,p.diascredito 
	 ,left(c.nombre,20)as 'cliente',left(r.nombre,20)as 'ruta',left(s.nombrecomercial,20)as 'nombrecomercial'  
	 ,left(concat(t.nombre,' ',t.apellido),40)as 'usuario',p.glosa  
	   from preventa p,cliente c,ruta r,almacen a,sucursal s,usuario u,trabajador t 
	 where p.idcliente=c.idcliente 
	 and c.ruta=r.idruta 
	 and p.idusuario=u.idusuario 
	 and u.idtrabajador=t.idtrabajador 
	 and a.idalmacen=p.idalmacen 
	 and a.sucursal=s.idsucursal 
	 and p.idpreventa=$idpreventa;";
	$preventa = $db->arrayConsulta($sql);
	

	function datosGenerales($dato, $db)
	{
	  $fecha = $db->GetFormatofecha($dato['fecha'], "-");
	  $fechaentrega = $db->GetFormatofecha($dato['fechaentrega'], "-");	
	  $credito = ($dato['modalidad'] == "efectivo") ? "" : " - ".$dato['diascredito']." Días";
	  echo "<table width='100%' border='0'>
	  <tr>
		<td width='18%' class='session2_titulos'>Cliente:</td>
		<td width='40%' class='session2_titulosDatos'>$dato[cliente]</td>
		<td width='23%' class='session2_titulos'>Fecha:</td>
		<td width='19%' class='session2_titulosDatos'>$fecha</td>
	  </tr>
	  <tr>
		<td class='session2_titulos'>Fecha Entrega:</td>
		<td class='session2_titulosDatos'>$fechaentrega</td>
		<td class='session2_titulos'>Moneda:</td>
		<td class='session2_titulosDatos'>$dato[moneda]</td>
	  </tr>
	  <tr>
		<td class='session2_titulos'>Ruta:</td>
		<td class='session2_titulosDatos'>$dato[ruta]</td>
		<td class='session2_titulos'>T.C.:</td>
		<td class='session2_titulosDatos'>$dato[tipocambio]</td>
	  </tr>
	  <tr>
		<td class='session2_titulos'>Modalidad:</td>
		<td class='session2_titulosDatos'>$dato[modalidad]$credito</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	</table>";
	}

    function nextPage()
	{
	   for ($m = 1; $m < 55; $m++) {
		   echo "<br />";
	   } 
	}   
   
	function insertarFila($num, $dato, $total)
	{	 	
	 $subTotal = $dato['cantidad'] * $dato['precio'];
	 $total = $total + $subTotal;
	  echo " <tr>
		<td class='session3_datos1'>$num</td>
		<td class='session3_datos1_1' align='left'>$dato[producto]</td>
		<td class='session3_datos1_1'>$dato[cantidad]</td>
		<td class='session3_datos1_1'>$dato[unidadmedida]</td>
		<td class='session3_datos1_1'>".number_format($dato['precio'], 2)."</td>
		<td class='session3_datos1_2'>".number_format($subTotal, 2)."</td>
	  </tr>";	
	  return $total;
	}
	
	function insertarTotal($total)
	{
	  echo "<tr>
		<td class='session3_contornoSuperior'>&nbsp;</td>
		<td class='session3_contornoSuperior'>&nbsp;</td>
		<td class='session3_contornoSuperior'>&nbsp;</td>
		<td class='session3_contornoSuperior'>&nbsp;</td>
		<td class='session3_textoTotal1'>Total:</td>
		<td class='session3_subtotal'>".number_format($total, 2)."</td>
	  </tr>";	
	}
	
	function insertarGlosa($dato)
	{
	 echo "<table width='100%' border='0'>
	  <tr>
		<td width='10%'>&nbsp;</td>
		<td width='90%'>&nbsp;</td>
	  </tr>
	  <tr>
		<td class='session3_tituloFinales'>GLOSA:</td>
		<td class='session3_glosa'>$dato[glosa]</td>
	  </tr>
	</table>";	
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="preventa.css" type="text/css" />
<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
<title><?php echo $titulo;?></title>
</head>
<body>

<?php

	$sql = "select p.idproducto,left(p.nombre,25)as 'producto',dp.cantidad,dp.precio
                  ,dp.unidadmedida from detallepreventa dp,preventa pv,producto p
				  where pv.idpreventa = dp.idpreventa and dp.idproducto=p.idproducto 
				  and pv.idpreventa=$idpreventa; ";
	$detallePreventa = $db->consulta($sql);	
	$totalCotizacion = 0;	
	$cant = $db->getnumrow($sql);
	$numero = 0;
	$tope = 40;
   	while($numero < $cant) {
?>

  <div style=" position : absolute;left:5%; top:20px;"></div>
  <div class="borde"></div>
  <div class="session1_numTransaccion">
   <table width="100%" border="0">
     <tr><td align="center" height="3"></td></tr> 
     <tr><td class="session1_titulo_num"><?php echo "Nº $idpreventa"; ?></td></tr> 
   </table>
  </div>
  <div class="session1_sucursal">
    <table width="100%" border="0">
      <tr><td align="center" class="session1_tituloSucursal"><?php 	  
          echo strtoupper($preventa['nombrecomercial']); ?></td></tr>
    </table>
  </div>
  <div class="session1_logotipo">
  <?php if ($logo == 'true'){ echo  "<img src='../$datoGeneral[imagen]' width='200' height='70'/>";} ?>
  </div>
  <div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1"><?php echo $titulo;?></td></tr> 
   </table>
  </div>

  <div class="session2_datosPersonales">
  <?php datosGenerales($preventa, $db); ?>
  </div>

  <div class="session3_datos">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center"> 
        <tr>
          <td width="5%" class="session3_titulosCabecera2">Nº</td>
          <td width="50%" class="session3_titulosCabecera">Descripción</td>
          <td width="11%" class="session3_titulosCabecera">Cantidad</td>
          <td width="11%" class="session3_titulosCabecera">U.M.</td>
          <td width="11%" class="session3_titulosCabecera">P/U</td>
          <td width="12%" class="session3_titulosCabecera">Total</td>
        </tr> 
       <?php   
     	 $numCotizacion = 0;	
         while($dato = mysql_fetch_array($detallePreventa)) {
			 $numero++;
             $numCotizacion++;
             $totalCotizacion = insertarFila($numCotizacion,$dato,$totalCotizacion);	
			 if ($numCotizacion == $tope) {
		        break;  
	         }   
         }
         insertarTotal($totalCotizacion); 
       ?>  
      </table>
      <?php
         insertarGlosa($preventa);
      ?>
  </div>


 <div class="session4_pieFirma"> 
   <table width="93%" border="0" align="center">
    <tr>
      <td width="173">&nbsp;</td>
      <td width="231" class="session4_bordeFirma"></td>
      <td width="97">&nbsp;</td>
      <td width="193" class="session4_bordeFirma"></td>
      <td width="97">&nbsp;</td>
      <td width="191" class="session4_bordeFirma"></td>
      <td width="246">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center" style="font-weight:bold">Elaborado Por</td>
      <td>&nbsp;</td>
      <td align="center" style="font-weight:bold">Vo.Bo.</td>
      <td>&nbsp;</td>
      <td align="center" style="font-weight:bold">Cliente</td>
      <td>&nbsp;</td>
    </tr>
   </table> 
 </div>
 
 <div class="session4_pie"> 
  <table width="93%" border="0" align="center">
  <tr>
    <td width="120" align="right">Elaborado por:</td>
    <td width="324"><?php echo $preventa['usuario'];?></td>
    <td width="93">&nbsp;</td>
    <td width="189">&nbsp;</td>
    <td width="201">&nbsp;</td>
    <td width="170" >Impreso: <?php echo date("d/m/Y");?></td>
    <td width="130">Hora: <?php echo date("H:i:s");?></td>
  </tr>
  </table>
 </div> 

<?php

      if ($numero < $cant) {
	       nextPage();
	   }

	}
	
?>

</body>
</html>
<?php
	$mpdf=new mPDF('utf-8','Letter'); 
	$content = ob_get_clean();
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit;
?>