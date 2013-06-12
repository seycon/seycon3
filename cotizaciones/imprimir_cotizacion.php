<?php
	ob_start();
	session_start();
	if (!isset($_SESSION['softLogeoadmin']) || !isset($_GET['idcotizacion'])) {
		header("Location: ../index.php");	
	}
	include("../MPDF53/mpdf.php");
	include("../conexion.php");
	include('../reportes/literal.php');
	$db = new MySQL();	
	
	$logo = $_GET['logo'];	
	$tituloGeneral = "COTIZACION";
	
	$sql = "select imagen,left(nombrecomercial,13)as 'nombrecomercial',nit from empresa;";
	$datoGeneral = $db->arrayConsulta($sql);
	
	$idcotizacion = $_GET['idcotizacion'];	
	$sql = "select t.nombrenit,t.telefono,t.nombrecontacto,t.nombre, 
	left(c.contacto,23)as 'contacto',c.tipocambio,c.moneda,date_format(c.fecha,'%d/%m/%Y')as 'fecha'
	,left(s.nombrecomercial,25)as 'nombrecomercial',left(cg.cargo,25)as 'cargo',
	c.validez,c.tiempoentrega,c.formapago,c.descuento,left(c.glosa,80)as 'glosa'
	,left(concat(b.nombre,' ',b.apellido),40)as 'usuario',c.idcarta,c.tipo,
	left(t.direccionoficina,80)as 'direccionoficina',c.validez,c.tiempoentrega as 'fechaentrega',
	c.formapago,c.tiempocredito,c.recargo  
	from cotizacion c,cliente t,trabajador b,usuario u,almacen a,sucursal s,cargo cg 
	where t.idcliente=c.idcliente
	and c.idalmacen=a.idalmacen 
	and a.sucursal=s.idsucursal 
	and c.idusuario=u.idusuario
	and u.idtrabajador=b.idtrabajador 
	and b.idcargo=cg.idcargo 
	and c.idcotizacion=$idcotizacion;";
	$cotizacion = $db->arrayConsulta($sql);
	
	if ($cotizacion['idcarta'] != "") {
		$sql = "select destinado,para,day(fecha)as 'dia',month(fecha)as 'mes',year(fecha)as 'anio',pie,
		left(referencia,50)as 'referencia',contenido,left(nombrefirma,35)as 'nombrefirma',left(cargo,35)as 'cargo'  
		from carta where idcarta=$cotizacion[idcarta];";
		$carta = $db->arrayConsulta($sql);
	}
	
	
	$numCotizacion = 0;	
	$totalCotizacion = 0;	
	
	function setcabecera()
	{
	   echo "<tr>
		  <td width='5%' class='session1_cabecera1'>N°</td>
		  <td width='16%' class='session1_cabecera1'>Imagen</td>
		  <td width='37%' class='session1_cabecera1'>Descripción</td>
		  <td width='15%' class='session1_cabecera1'>Cantidad</td>
		  <td width='13%' class='session1_cabecera1'>P/U</td>
		  <td width='14%' class='session1_cabecera2'>Total</td>
       </tr>";
	}
	
	function setDato($num, $tipo, $imagen, $descripcion, $cantidad, $precio, $importe)
	{
	  $clase1 = "";	
	  if ($tipo == "final") {
	      $clase1 = "border-bottom:1.5px solid";		
	  }	 
	  echo "<tr>
		   <td  class='session3_datosF1_1' height='80' style='$clase1' align='center'>&nbsp;$num</td>
		   <td  class='session3_datosF1_2' height='80' style='$clase1' align='center'>
		   <img src='../$imagen' alt='camara' width='80' height='80' />
		   </td>
		   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$descripcion</td>
		   <td  class='session3_datosF1_2' style='$clase1' align='center'>&nbsp;$cantidad</td>
		   <td  class='session3_datosF1_2' style='$clase1' align='center'>".number_format($precio, 2)."</td>
		   <td  class='session3_datosF1_3' style='$clase1' align='center'>".number_format($importe, 2)."</td>	  
	  </tr>";	
	}	
	
	function setTotalF($total)
	{
	    echo "
		<tr >
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
          <td >&nbsp;</td>
		  <td  class='session3_datosF2_Total' align='center'>".number_format($total, 2)."</td>
		</tr>";			
	}
	
	function setTotalServicio($total)
	{
	    echo "
		<tr >
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
          <td align='right' class='textoNegrita'>Total:</td>
		  <td  class='session3_datosF2_Total' align='center'>".number_format($total, 2)."</td>
		</tr>";			
	}
	
	function setCabeceraServicio()
	{
	   echo "<tr>
		  <td width='5%' class='session1_cabecera1'>N°</td>
		  <td width='53%' class='session1_cabecera1'>Descripción</td>
		  <td width='15%' class='session1_cabecera1'>Cantidad</td>
		  <td width='13%' class='session1_cabecera1'>P/U</td>
		  <td width='14%' class='session1_cabecera2'>Total</td>
       </tr>";
	}
	
	function setDatoServicio($num, $tipo, $descripcion, $cantidad, $precio, $importe)
	{
	  $clase1 = "";	
	  if ($tipo == "final") {
	      $clase1 = "border-bottom:1.5px solid";		
	  }	 
	  echo "<tr>
		   <td  class='session3_datosF1_1' style='$clase1' align='center'>&nbsp;$num</td>
		   <td  class='session3_datosF1_2' style='$clase1' align='left'>&nbsp;$descripcion</td>
		   <td  class='session3_datosF1_2' style='$clase1' align='center'>&nbsp;$cantidad</td>
		   <td  class='session3_datosF1_2' style='$clase1' align='center'>".number_format($precio, 2)."</td>
		   <td  class='session3_datosF1_3' style='$clase1' align='center'>".number_format($importe, 2)."</td>	  
	  </tr>";	
	}	
	
	function setPie($usuario)
	{
	    echo "
		  <table width='93%' border='0' align='center'>
		  <tr>
			<td width='120' align='right'>Elaborado por:</td>
			<td width='324'>$usuario</td>
			<td width='93'>&nbsp;</td>
			<td width='189'>&nbsp;</td>
			<td width='201'>&nbsp;</td>
			<td width='170' >Impreso: ".date('d/m/Y')."</td>
			<td width='130'>Hora: ".date("H:i:s")."</td>
		  </tr>
		  </table>
		";	
	}  	
 
	 function insertarFecha($ciudad, $dia, $mes, $anio, $db)
	 {
		$mes = strtolower($db->mes($mes));
		$mes = ucfirst($mes); 
		echo "<table width='100%' border='0'>
		<tr>
		  <td width='68%'>&nbsp;</td>
		  <td width='32%' class='texto'>$ciudad, $dia de $mes de $anio</td>
		</tr>
		</table>";   
	 }
	 
	 function insertarDirigido($persona, $nombrecontacto, $nombre)
	 {
		echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>
		   <tr><td class='textoNegrita'>$persona:</td></tr>
		   <tr><td valign='top' class='texto'>$nombrecontacto</td></tr>
   		   <tr><td valign='top' class='texto'>$nombre</td></tr>
		</table>"; 
	 }
	 
	 function insertarCodigo($codigo, $mes, $anio)
	 {
		echo "<table width='100%' border='0'>
		 <tr>
		  <td class='textoNegrita'>CITE: $codigo/$mes/$anio</td>
		 </tr>
		</table>"; 
	 }
	 
	 function insertarPresente()
	 {
		echo "<table width='100%' border='0'>
		 <tr>
		  <td class='textoNegrita'>Presente.-</td>
		 </tr>
		</table>"; 
	 }
	 
	 function insertarReferencia($contenido)
	 {
	  echo "<table width='100%' border='0' >
		<tr>
		 <td align='right' class='session2_textoTitulo'>$contenido</td>
		</tr>
		</table>";   
	 }
	 
	 function insertarDatos($datos)
	 {
	  echo " <table width='100%' border='0'>
		<tr><td height='450' valign='top' class='session2_datos_contenido'>$datos</td></tr>
	  </table>";   	   
	 }
	 
	 function insertarPiePresentacion($datos) 
	 {
	  echo " <table width='100%' border='0'>
		<tr><td valign='top' class='texto'>$datos</td></tr>
	  </table>";   	   
	 }
	 
	 
	 function insertarDatosFirma($representante,$cargo)
	 {
	  echo "<table width='100%' border='0'>
	   <tr>
		 <td width='30%'>&nbsp;</td>
		 <td width='40%' class='session3_datosFirma'>$representante <br> $cargo</td>
		 <td width='30%'>&nbsp;</td>
	   </tr>
	  </table>";   
	 }

	function nextPage()
	{
	   for ($m = 1; $m < 55; $m++) {
		   echo "<br />";
	   } 
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="cotizacion.css" type="text/css" />
<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
<title>Reporte de Cotizacion</title>
</head>
<body>

  <!-- PAGINA 1  {Carta}-->
  <?php if ($cotizacion['idcarta'] != "" && $cotizacion['idcarta'] != 0) { ?>
      <div style=" position : absolute;left:5%; top:20px;"></div>
      <div class='borde'></div>
      <div class='session1_logotipo'><?php 
      if ($logo == 'true') {
          echo  "<img src='../$datoGeneral[imagen]' width='200' height='70'/>";
      }
        ?></div>  
    
      <div class="session1_sucursal">
          <table width="100%" border="0">
            <tr><td align="center" class="session1_tituloSucursal">
            <?php echo strtoupper($cotizacion['nombrecomercial']); ?>
            </td></tr>
          </table>
      </div>
      <?php 
        $dias = explode("/", $cotizacion['fecha']);
      ?>
      <div class="session1_fecha"><?php insertarFecha('Santa Cruz', $dias[0], $dias[1], $dias[2], $db);?></div>
      <div class="session1_codigo"><?php insertarCodigo($idcotizacion, $dias[1], $dias[2])?></div>
      <div class="session1_dirigido"><?php insertarDirigido($carta['destinado'],
      $cotizacion['contacto'],$cotizacion['nombre'])?></div>
      <div class="session1_presente"><?php insertarPresente();?></div>
      <div class="session2_titulo"><?php insertarReferencia($carta['referencia']);?> </div>
      <div class="session2_datos"><?php insertarDatos($carta['contenido']); ?></div>
      <div class="session3_firma"><?php insertarDatosFirma($cotizacion['usuario'], $cotizacion['cargo']);?></div> 
      <div class="session3_piepresentacion"><?php insertarPiePresentacion($carta['pie']);?></div> 
  <?php 
      nextPage();
      }
  ?>
  
  
  <!-- PAGINA 2 {Producto}-->
  <?php if ($cotizacion['tipo'] == "productos") { ?>
  <?php
  $consulta = "select p.idproducto,p.imagen,left(p.descripcion,165)as 'descripcion',round(ds.precio,2)as 'precio'
			   ,ds.cantidad, round(ds.total,2)as 'total' from detallecotizacion ds,producto p
			   ,cotizacion s where ds.idcotizacion=s.idcotizacion and 
			   ds.idproducto=p.idproducto and s.idcotizacion=".$idcotizacion
			   ." order by ds.iddetallecotizacion";
  
  $tope = 10;
  $cant = $db->getnumrow($consulta);
  $numero = 0;
  $row = $db->consulta($consulta);
  $totalGeneral = 0;
  while ($numero < $cant) {	
  ?>
  <div style=" position : absolute;left:5%; top:20px;"></div>
  <div class='borde'></div>
  <div class='session1_logotipo'><?php 
  if ($logo == 'true') {
      echo  "<img src='../$datoGeneral[imagen]' width='200' height='70'/>";
  }
    ?></div>
  
  <div class="session1_contenedorTitulos">
     <table width="100%" border="0">
       <tr><td align="center" class="session1_titulo1"><?php echo $tituloGeneral;?></td></tr>  
       <tr><td align="center" class="session1_titulo2"></td></tr>    
       <tr><td align="center" class="session1_titulo2"></td></tr>  
    </table>
  </div>
  <div class="session1_sucursal">
      <table width="100%" border="0">
        <tr><td align="center" class="session1_tituloSucursal">
        <?php echo strtoupper($cotizacion['nombrecomercial']); ?>
        </td></tr>
      </table>
  </div>

  <div class="session3_datos">
     <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	  <?php
        setcabecera();
		$nota = "";
		$i = 0;
	  
		while ($data = mysql_fetch_array($row)) {
			$numero++;
			$i++;  
			$importe = $data['total'] ;
			$totalGeneral = $totalGeneral + $importe;
			$tipoFila = "normal";
			$src = "files/modelo_producto.png";
			if ($data['imagen'] != "") {
			    $src = $data['imagen'];	
			}
			
			if ($numero == $cant || $i == $tope) {
				$tipoFila = "final";  
			}
			setDato($i, $tipoFila, $src, $data['descripcion'], $data['cantidad'], $data['precio'], $importe);
			
			if ($i == $tope) 
				break;			
		}	
		
		setTotalF($totalGeneral);	
      ?>  
     </table>
     
     <?php       
       if (($numero == $cant && $i < 8) || $i == 0) {
	      $numero = $cant;	   
	?>  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td align="right" class="textoNegrita">&nbsp;</td>
        <td class="texto">&nbsp;</td>
        <td align="right" class="textoNegrita">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="texto">&nbsp;</td>
      </tr>
      <?php
	   $descuento = ($cotizacion['descuento'] / 100) * $totalGeneral;
	   $recargo = ($cotizacion['recargo'] / 100) * $totalGeneral;
	  ?>
      <tr>
        <td width="27%" align="right" class="textoNegrita">Validez de la Propuesta:</td>
        <td width="32%" class="texto"><?php echo $cotizacion['validez'];?></td>
        <td width="17%" align="right" class="textoNegrita">Descuento:</td>
        <td width="11%" class="session3_datosF2_Total1">
          <?php echo number_format($descuento,2);?>
          </td>
        <td width="13%" class="texto">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" class="textoNegrita">Fecha de Entrega:</td>
        <td class="texto"><?php echo $cotizacion['fechaentrega'];?></td>
        <td align="right" class="textoNegrita">Recargo:</td>
        <td class='session3_datosF2_Total'><?php echo number_format($recargo,2);?></td>
        <td class="texto">&nbsp;</td>
      </tr>
      <?php
        $diasCredito = "";
       if ($cotizacion['formapago'] == "credito") {
         $diasCredito = " / ".$cotizacion['tiempocredito']." Días";   
       }
       
       $totalOficial = $totalGeneral - $descuento + $recargo;
      ?>
      <tr>
        <td align="right" class="textoNegrita">Forma de Pago:</td>
        <td class="texto"><?php echo ucfirst($cotizacion['formapago']).$diasCredito;?></td>
        <td align="right" class="textoNegrita">Total Cotización:</td>
        <td class='session3_datosF2_Total'><?php echo number_format($totalOficial,2);?></td>
        <td class="texto">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" class="session1_subtitulo1">&nbsp;</td>
        <td class="session1_subtitulo1">&nbsp;</td>
        <td align="right" class="session1_subtitulo1">&nbsp;</td>
        <td class="session1_subtitulo1">&nbsp;</td>
        <td class="session1_subtitulo1">&nbsp;</td>
      </tr>
      </table>
      
      <table width="95%" border="0" align="center">
        <tr>
          <td class="texto"><span class="textoNegrita">Son:</span>
		  <?php echo NumerosALetras(round($totalOficial,2),$cotizacion['moneda']);?></td>
        </tr>
      </table>
      <?php if ($cotizacion['glosa'] != "") {?>
      <table width="95%" border="0" align="center">
        <tr>
          <td class="texto"><span class="textoNegrita">Glosa: </span><?php echo $cotizacion['glosa'];?></td>
        </tr>
      </table>
      <?php } ?>
    <?php       
       } else {
		  if ($numero == $cant) 
		    $numero--;
	   }
	?>  
     
  </div>
  <div class="session4_pie"><?php setPie($cotizacion['usuario']);?></div> 
  <?php       
       if ($numero < $cant) {
	       nextPage();
	   }
  ?>
	
    <?php       
       if ($numero == $cant && $i < 8) {
	?>   
     <div class="session3_subPie"> 
     <table width="93%" border="0" align="center">
      <tr>
        <td width="87">&nbsp;</td>
        <td width="184" class="negrita">............................................</td>
        <td width="75">&nbsp;</td>
        <td width="181" class="negrita">...........................................</td>
        <td width="60">&nbsp;</td>
        <td width="181" class="negrita">..........................................</td>
        <td width="156">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="center" style="font-weight:bold">Elaborado Por</td>
        <td>&nbsp;</td>
        <td align="center" style="font-weight:bold">V°. B°.</td>
        <td>&nbsp;</td>
        <td align="center" style="font-weight:bold">Cliente</td>
        <td>&nbsp;</td>
      </tr>
     </table> 
     </div>
       
	   
  <?php	
	   }
	}
  }
  ?> 




  <!-- PAGINA 3 {Servicio}-->
  <?php if ($cotizacion['tipo'] == "servicios") { ?>
  <?php
  $consulta = "select s.idservicio as 'idproducto',s.nombre,round(ds.precio,2)as 'precio'
			   ,ds.cantidad, round(ds.total,2)as 'total' from detallecotizacion ds,servicio s
			   ,cotizacion c where ds.idcotizacion=c.idcotizacion and 
			   ds.idproducto=s.idservicio and c.idcotizacion=".$idcotizacion
			   ." order by ds.iddetallecotizacion";
  
  $tope = 45;
  $cant = $db->getnumrow($consulta);
  $numero = 0;
  $row = $db->consulta($consulta);
  $totalGeneral = 0;
  while ($numero < $cant) {	
  ?>
  <div style=" position : absolute;left:5%; top:20px;"></div>
  <div class='borde'></div>
  <div class='session1_logotipo'><?php 
  if ($logo == 'true') {
      echo  "<img src='../$datoGeneral[imagen]' width='200' height='70'/>";
  }
    ?></div>
  
  <div class="session1_contenedorTitulos">
     <table width="100%" border="0">
       <tr><td align="center" class="session1_titulo1"><?php echo $tituloGeneral;?></td></tr>  
       <tr><td align="center" class="session1_titulo2"></td></tr>    
       <tr><td align="center" class="session1_titulo2"></td></tr>  
    </table>
  </div>
  <div class="session1_sucursal">
      <table width="100%" border="0">
        <tr><td align="center" class="session1_tituloSucursal">
        <?php echo strtoupper($cotizacion['nombrecomercial']); ?>
        </td></tr>
      </table>
  </div>

  <div class="session3_datos">
  
  <table width="100%" border="0">
  <tr>
    <td width="16%" align="right" class="textoNegrita">Empresa:</td>
    <td width="40%" class="texto"><?php echo $cotizacion['nombre'];?></td>
    <td width="13%" align="right" class="textoNegrita">Fecha:</td>
    <td width="31%" class="texto">
      <?php echo $cotizacion['fecha'];?>
      </td>
  </tr>
  <tr>
    <td align="right" class="textoNegrita">Contacto:</td>
    <td class="texto"><?php echo $cotizacion['contacto'];?></td>
    <td align="right" class="textoNegrita">Moneda:</td>
    <td class="texto"><?php echo $cotizacion['moneda'];?></td>
  </tr>
  <tr>
    <td align="right" valign="top" class="textoNegrita">Dirección:</td>
    <td class="texto"><?php echo $cotizacion['direccionoficina'];?></td>
    <td align="right" valign="top" class="textoNegrita">Teléfono:</td>
    <td class="texto"><?php echo $cotizacion['telefono'];?></td>
  </tr>
  <tr>
    <td align="right" class="session1_subtitulo1">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
    <td align="right" class="session1_subtitulo1">&nbsp;</td>
    <td class="session1_subtitulo1">&nbsp;</td>
  </tr>
  </table>  
   
     <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	  <?php
        setCabeceraServicio();
		$nota = "";
		$i = 0;
	  
		while ($data = mysql_fetch_array($row)) {
			$numero++;
			$i++;  
			$importe = $data['total'] ;
			$totalGeneral = $totalGeneral + $importe;
			$tipoFila = "normal";
						
			if ($numero == $cant || $i == $tope) {
				$tipoFila = "final";  
			}
			setDatoServicio($i, $tipoFila, $data['nombre'], $data['cantidad'], $data['precio'], $importe);
			
			if ($i == $tope) 
				break;			
		}	
		
		setTotalServicio($totalGeneral);	
      ?>  
     </table>
     
   <?php       
       if (($numero == $cant && $i < 40) || $i == 0) {
	      $numero = $cant;	   
	?>  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td align="right" class="textoNegrita">&nbsp;</td>
        <td class="texto">&nbsp;</td>
        <td align="right" class="textoNegrita">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="texto">&nbsp;</td>
      </tr>
      <?php
	   $descuento = ($cotizacion['descuento'] / 100) * $totalGeneral;
	   $recargo = ($cotizacion['recargo'] / 100) * $totalGeneral;
	  ?>
      <tr>
        <td width="27%" align="right" class="textoNegrita">Validez de la Propuesta:</td>
        <td width="32%" class="texto"><?php echo $cotizacion['validez'];?></td>
        <td width="17%" align="right" class="textoNegrita">Descuento:</td>
        <td width="11%" class="session3_datosF2_Total1">
          <?php echo number_format($descuento,2);?>
          </td>
        <td width="13%" class="texto">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" class="textoNegrita">Fecha de Entrega:</td>
        <td class="texto"><?php echo $cotizacion['fechaentrega'];?></td>
        <td align="right" class="textoNegrita">Recargo:</td>
        <td class='session3_datosF2_Total'><?php echo number_format($recargo,2);?></td>
        <td class="texto">&nbsp;</td>
      </tr>
      <?php
        $diasCredito = "";
       if ($cotizacion['formapago'] == "credito") {
         $diasCredito = " / ".$cotizacion['tiempocredito']." Días";   
       }
       
       $totalOficial = $totalGeneral - $descuento + $recargo;
      ?>
      <tr>
        <td align="right" class="textoNegrita">Forma de Pago:</td>
        <td class="texto"><?php echo ucfirst($cotizacion['formapago']).$diasCredito;?></td>
        <td align="right" class="textoNegrita">Total Cotización:</td>
        <td class='session3_datosF2_Total'><?php echo number_format($totalOficial,2);?></td>
        <td class="texto">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" class="session1_subtitulo1">&nbsp;</td>
        <td class="session1_subtitulo1">&nbsp;</td>
        <td align="right" class="session1_subtitulo1">&nbsp;</td>
        <td class="session1_subtitulo1">&nbsp;</td>
        <td class="session1_subtitulo1">&nbsp;</td>
      </tr>
      </table>
      <table width="95%" border="0" align="center">
        <tr>
          <td class="texto"><span class="textoNegrita">Son:</span>
		  <?php echo NumerosALetras(round($totalOficial,2),$cotizacion['moneda']);?></td>
        </tr>
      </table>
      <?php if ($cotizacion['glosa'] != "") {?>      
      <table width="95%" border="0" align="center">
        <tr>
          <td class="texto"><span class="textoNegrita">Glosa: </span><?php echo $cotizacion['glosa'];?></td>
        </tr>
      </table>
      <?php } ?>
    <?php       
       } else {
		  if ($numero == $cant) 
		    $numero--;
	   }
	?>  
      
         
</div>
  <div class="session4_pie"><?php setPie($cotizacion['usuario']);?></div> 
  <?php       
       if ($numero < $cant) {
	       nextPage();
	   }	  
  ?> 
  
  <?php       
       if ($numero == $cant && $i < 40) {
	?>   
     <div class="session3_subPie"> 
     <table width="93%" border="0" align="center">
      <tr>
        <td width="87">&nbsp;</td>
        <td width="184" class="negrita">............................................</td>
        <td width="75">&nbsp;</td>
        <td width="181" class="negrita">...........................................</td>
        <td width="60">&nbsp;</td>
        <td width="181" class="negrita">..........................................</td>
        <td width="156">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="center" style="font-weight:bold">Elaborado Por</td>
        <td>&nbsp;</td>
        <td align="center" style="font-weight:bold">V°. B°.</td>
        <td>&nbsp;</td>
        <td align="center" style="font-weight:bold">Cliente</td>
        <td>&nbsp;</td>
      </tr>
     </table> 
     </div>
 <?php 
   }
  } 
 } 
 ?>


</body>
</html>
<?php
    $header = "
	<table align='right' width='16%' >  
	<tr><td heigth='10'></td></tr>
	  <tr><td align='center' style='border:1px solid;font-size:11px;' bgcolor='#E6E6E6' >Nº $idcotizacion
	    Pag. {PAGENO}/{nb}</td></tr>
	</table>";
	$mpdf = new mPDF('utf-8','Letter'); 
	$content = ob_get_clean();
	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit;
?>