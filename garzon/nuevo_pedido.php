<?php
    session_start();
    if (!isset($_SESSION['idusuariorestaurante'])) {
        header("Location: index.php");	  
    }
    include("../conexion.php");
    $db = new MySQL();
    if (isset($_GET['atencion'])) {
        $idatencion = $_GET['atencion'];
        $idnroatencion = $_GET['pedido'];
    }
    $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
    $tc = $db->getCampo('dolarcompra',$sql);  
  
    $sql = "select *from configuracionrestaurante;";
    $configuracion = $db->arrayConsulta($sql);  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Restaurante</title>
<link rel="stylesheet" href="restaurante.css" type="text/css"/>
<link href="../autocompletar/estilo1.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<script src="Npedido.js"></script>
<script type="text/javascript" src="js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="js/html2canvas.js"></script>
<script type="text/javascript" src="js/jquery.plugin.html2canvas.js"></script>
<script src="../lib/Jtable.js"></script>
<script src="../autocompletar/funciones.js"></script>
<script src="../js/jquery-1.5.1.min.js"></script>
<script src="../js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="../js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script>

$(document).ready(function()
 {	 
	$('#fechaatencion').datepicker({
    showOn: 'button',
    buttonImage: '../css/images/calendar.gif',
    buttonImageOnly: true,
    dateFormat: 'dd/mm/yy' });
 });
 

</script>



</head>

<body>
<applet name="jzebra" code="jzebra.PrintApplet.class" archive="./jzebra.jar" width="0px" height="0px">
      <param name="printer" value="zebra">
</applet><br />

<div id="overlay" class="overlays"></div>
<div id="gif" class="gifLoader"></div>
<div id="modal_mensajes" class="modal_mensajes">
  <div class="modal_cabecera">
     <div id="modal_tituloCabecera" class="modal_tituloCabecera">Advertencia</div>
     <div class="modal_cerrar">
     <img src="../iconos/borrar2.gif" width="12" height="12" style="cursor:pointer" onclick="closeMensaje()"></div>
  </div>
  <div class="modal_icono_modal"><img src="../iconos/alerta.png" width="24" height="24"></div>
  <div id="modal_contenido" class="modal_contenido">No Existen Pagos Realizados</div>
  <div id="btAccion" class="modal_boton2">
  <input type="button" value="Aceptar" class="boton_modal" onclick="setPedidoAnulado()"/></div>
  <div class="modal_boton1"><input type="button" value="Cancelar" class="boton_modal" onclick="closeMensaje()"/></div>
</div>


 <div class="contendedor">
  
   <div class="tela_izq"></div>
   <div class="tela_cierreizq"></div>
   <div class="tela_der"></div>
   <div class="tela_cierreder"></div> 
   <div class="derechosReservados">Copyright © Consultora Guez – Diseñado y Desarrollado
   </div>
   <div class="header"><div class="gradient7"><h1><span></span>SCAV</h1></div>  </div>
   <div class="subTitulo">Software Contable de Administración y Ventas.</div>
   
   <table width="90%" border="0" align="center">
  <tr>
    <td width="21%">&nbsp;</td>
    <td width="79%"></td>
  </tr>
  <tr>
    <td width="21%">
    <div class="menu1">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="36%">&nbsp;</td>
    <td width="64%">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><div class="tituloMenu"><< <?php echo ucfirst($_SESSION['sucursalrestaaurante']); ?> >></div></td>
    </tr>
  <tr>
    <td height="336" colspan="2" >
    <div class="contenedorMenu">
     <div id="opcion1" onclick="location.href='nuevo_atencion.php'"><div class="sombraButon"></div><div id="textoOpcion">Inicio</div></div>
     <div class="separador"></div>
     <div class="contenedorProductos">
      <div class="cabeceraProductos"><div class="tenedor"></div><div class="textocabeceraProductos">Menú</div>  </div>
      <div class="ingresarProducto">
      <table width="100%" border="0">
  <tr>
    <td class="textoIngresoProducto">Buscar:</td>
    <td>
    <input type="text" name="producto" id="producto" style="width:80%" onkeyup="consultarProductos(this.value)" autocomplete="off"/></td>
  </tr>
</table>

      
      </div>
      <div class="listaProductos">
       <ul id="productos">
         <?php		   
		     $sql = "select c.idcombinacion,left(c.nombre,22) as 'nombre1',c.total as 'precio',c.nombre,t.nombre as 'tipo'
			  from combinacion c,tipocombinacion t 
		     where c.idtipocombinacion=t.idtipocombinacion and c.nombre like '%' and c.estado=1 limit 9;";
	         $producto = $db->consulta($sql);
	         while ($dato = mysql_fetch_array($producto)) {
		         $item = "<li onclick='openVentanaPedido(&quot;$dato[nombre]&quot;,&quot;$dato[precio]&quot;,&quot;"
				     ."$dato[idcombinacion]&quot;,&quot;$dato[tipo]&quot;)'>".ucfirst($dato['nombre1'])."</li>";		  
				 echo $item;	 
			 }
		 ?>
       </ul>
      </div>
     
     </div>
    
    
    </div>
    
    
    
    </td>
    </tr>
  <tr>
    <td height="21" align="center" class="letra" colspan="2">&nbsp;</td>
  </tr>
</table>

    <div class="contenedorUser"><div class="imgUser"></div><div class="nombreUser"><?php echo $_SESSION['nombretrestaurante'];?></div></div>
    </div>
    </td>
    <td width="79%">
      <div class="menu2">
       <div class="tituloTransaccion"><div class="textoTituloTransaccion">Atención de Mesas - Nº Venta [<?php echo $idatencion;?>]</div></div>
          <div class="separador"></div>
            </br>
       <table width="92%" border="0" align="center">
    <tr>
    <td height="235">
    <div class="contenedorCuaderno">
     <div class="espiradeCuaderno"></div>
     <br />
     <div class="contenidoDetallepedido">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr >
    <td width="4%" ></td>
    <td width="9%" class="celdacuadernoCabecera" >Nº Pedido</td>
    <td width="40%" class="celdacuadernoCabecera">Producto</td>
    <td width="18%" class="celdacuadernoCabecera">Cantidad</td>
    <?php 
	    if ($configuracion['pantallapu'] == 1) echo '<td width="16%" class="celdacuadernoCabecera">Precio</td>'; 
	    if ($configuracion['pantallapt'] == 1) echo '<td width="17%" class="celdacuadernoCabecera" >Total</td>'; 
	?>   
    
    <td width="4%" style="display:none"></td>
  </tr>
  <tbody id="detallepedidoProductos">
   <?php
       $sql = "select da.iddetalleatencion,left(c.nombre,20)as 'nombre',da.cantidad,da.precio,da.nroatencion
       from detalleatencion da,combinacion c where 
       da.idcombinacion=c.idcombinacion and idatencion=$idatencion and da.estado=1 order by da.iddetalleatencion;";
       $pedido = $db->consulta($sql);
       $i = 0;
       $totalPedido = 0;
       while ($dato = mysql_fetch_array($pedido)) { 
           $i++;
           $precio = $dato['precio'];
           $cantidad = $dato['cantidad'];
           $total = $precio * $cantidad;
           $totalPedido = $totalPedido + $total;
   echo " <tr >
    <td align='center'><img src='../css/images/borrar.gif' title='Anular' alt='borrar'
	 onclick='eliminarFila(this)' style='cursor:pointer' /></td>
    <td align='center' class='celdacuaderno'>$dato[nroatencion]</td>
    <td align='left' class='celdacuaderno'>$dato[nombre]</td>
    <td align='center' class='celdacuaderno'>".number_format($cantidad,2)."</td>";
	if ($configuracion['pantallapu'] == 1) echo  "<td align='center' class='celdacuaderno'>".number_format($precio,2)."</td>";
	if ($configuracion['pantallapt'] == 1) echo "<td align='center' class='celdacuaderno'>".number_format($total,2)."</td>";    
	echo "<td style='display:none'>$dato[iddetalleatencion]</td>
  </tr>";
  
   }
   ?>
  </tbody>

</table>
    </div>
     
    </div>
    </td>
    </tr>
  <tr>
    <td><table width="100%" border="0">
      <tr>
        <td width="19%" align="center"><input type="text" value="Cobrar" id="botonrestaurante" onclick="openVentanaClave()"/></td>
        <td width="16%"><input type="text" value="Imprimir" id="botonrestaurante" onclick="getDatosVenta()"/></td>
        <td width="24%"><input type="hidden" id="idatencion" name="idatencion" value="<?php echo $idatencion;?>"/>
        <input type="hidden" id="idnroatencion" name="idnroatencion" value="<?php echo $idnroatencion;?>"/>  
        <input type="hidden" id="idpantallapu" name="idpantallapu" value="<?php echo $configuracion['pantallapu'];?>"/>
        <input type="hidden" id="idpantallapt" name="idpantallapt" value="<?php echo $configuracion['pantallapt'];?>"/>
        <input type="hidden" id="imprimircm" name="imprimircm" value="<?php echo $configuracion['impresioncm'];?>"/>
        </td>
        <td width="23%" align="right" class="textoCuaderno"><?php if ($configuracion['pantallatv'] == 1) echo 'Total:'; ?></td>
        <td width="18%" >
        <?php $estilo = ($configuracion['pantallatv'] == 1) ? 'totalCuaderno' : 'totalCuaderno2' ?>       
        <div id="totalPedido" class="<?php echo $estilo;?>"></div></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right" class="textoCuaderno">&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" align="left" class="letra">Para "Imprimir y Salir" Presione.
        <span class="rojo"> [F8]</span> </td>
        <td align="right" class="textoCuaderno">&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
    </table></td>
    </tr>
</table>      
      
          <div class="contenedorCerrar"><div class="imagenCerrar"></div><div id="textoCerrar" onclick="location.href='cerrar.php'">Cerrar</div></div>
      </div>
    </td>
  </tr>
</table>

   
   
   
   
 <div id="modal1" class="modal1"></div>
 <div id="modalInterior1" class="modalInterior1">
 <div class="headerInterior"><div class="tituloVentanaclave">Cobranza de Mesa</div></div>  
  <div class="posicionCloseSub" onclick="closeVentanaClave();"><img src="../iconos/borrar2.gif" width="12" height="12"></div>
  <br />
  <br />
  <table width="100%" border="0" cellspacing="0">
  <tr>
    <td width="4%">&nbsp;</td>
    <td width="28%" align="right" class="textoClave">Cliente:</td>
    <td width="30%">        
        <input type="text" disabled="disabled"  id="dato" autocomplete="off" onkeyup="autocompletar(event,this.id)" onfocus="this.select()" style="width:130px;"/><br>
        <div  id="resultados"  class="divresultado" style="width:170px;"></div>
        <input type="hidden" id="codidproducto" />
        <div id="mensajeE4" class="msjError">Trabajador Invalido</div>
        </td>
    <td width="9%"><div id="autoL1" class="autoLoading"></div></td>
    <td width="22%" class="textoClave" align="right">Credito
      <input type="checkbox" id="credito" name="credito" checked="checked" onclick="limpiarIngreso()"/></td>
    
  </tr>
  
  
  <tr>
    <td width="4%"></td>
    <td width="28%" align="right" class="textoClave">Descuento:</td>
    <td><input type="text" name="descuento" id="descuento" disabled="disabled" size="13" value="0"/></td>
    <td>&nbsp;</td>
    <td class="textoClave" align="right">
    SOCIO<input type="checkbox" id="cksocio" disabled="disabled"  name="cksocio" /></td>
    <td width="7%"> <input type="hidden" id="tipotrabajador" name="tipotrabajador" /></td>
  </tr>
  
  <?php
    $tipo = "block";
  
     if ($configuracion['pantallatv'] == 0)  {
	     $tipo = "none";
	 }
  ?>
  <tr>
		  <td></td>
		  <td align='right' class='textoClave' style="display:<?php echo $tipo;?>">Total Venta:</td>
		  <td colspan='3'>
		  <input type='text' name='totalgeneral' id='totalgeneral' size='13' disabled='disabled' style="display:<?php echo $tipo;?>"/>
		  </td>
		  <td>&nbsp;</td>
		</tr>
  
  <tr >
    <td >&nbsp;</td>
    <td style="border-bottom:1px solid;"></td>
    <td style="border-bottom:1px solid;" colspan="3"></td>
    <td><input type="hidden" id="impresionFV" name="impresionFV" value="<?php echo $configuracion['impresionfv']; ?>" /></td>  
  </tr>
  <?php if ($configuracion['impresionfv'] == 1) {?>
    <tr >
    <td >&nbsp;</td>
    <td align="right">Fecha:</td>
    <td  colspan="3"><input type="text" name="fechaatencion" size="10" id="fechaatencion" class="date" 
    value="<?php echo date("d/m/Y"); ?>"/><div id="msjfecha" class="msjError">Numero Invalido</div>
    </td>
    <td>&nbsp;</td>
  </tr>
  <?php }?>
  <tr>
    <td></td>
    <td class="textoClave" align="right"></td>
    <td colspan="3">
    <input type="text" name="efectivobs" id="efectivobs" size="13" onkeyup="getCambio()" onkeypress="return soloNumeros(event)" 
    value="0" style="display:none"/><div id="mensajeE1" class="msjError">Numero Invalido</div>
    </td>
    <td></td>
  </tr>
  <tr>
    <td><input type="hidden" id="tipocambio" name="tipocambio" value="<?php echo $tc;?>" /></td>
    <td class="textoClave" align="right">Cortesia/Vale:</td>
    <td colspan="3"><input type="text" name="cortesia" id="cortesia" onkeypress="return soloNumeros(event)"
     size="13" onkeyup="getCambio()" value="0"/> <div id="mensajeE2" class="msjError">Numero Invalido</div></td>
    <td ></td>
  </tr>
  <tr>
    <td></td>
    <td class="textoClave" align="right"></td>
    <td colspan="3"><input type="text" name="cambio" id="cambio" disabled="disabled" size="13" style="display:none;"/>
    <div id="mensajeE3" class="msjError">Numero Invalido</div>
    </td>
    <td></td>
  </tr> 
</table>
  <div class="posboton1"><input type="button"  value="Guardar" id="botonrestaurante" onclick="insertarCobranza()" /></div>
  <div class="posboton2"><input type="button"  value="Cancelar" id="botonrestaurante" onclick="closeVentanaClave();"/></div>
 </div>






 <div id="modal2" class="modal2"></div>
 <div id="modalInterior2" class="modalInterior2">
 <div class="headerInterior"><div class="tituloVentanaclave">Pedido</div></div>  
 <div class="posicionCloseSub" onclick="closeVentanaPedido();"><img src="../iconos/borrar2.gif" width="12" height="12"></div>
  <br />
  <table width="100%" border="0">
  <tr>
    <td>&nbsp;</td>
    <td align="right" class="textoClave">Disponible:</td>
    <td><input type="text" name="cdisponible" id="cdisponible" size="20" disabled="disabled" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="9%">&nbsp;</td>
    <td width="34%" align="right" class="textoClave">Producto:</td>
    <td width="41%"><input type="text" name="nombreproducto" id="nombreproducto" size="20" disabled="disabled" /></td>
    <td width="16%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right" class="textoClave">Precio:</td>
    <td><input type="text" name="precioproducto" id="precioproducto" size="13" disabled="disabled"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input type="hidden" id="idcombinacion" name="idcombinacion" />
    <input type="hidden" id="tipocombinacion" name="tipocombinacion" />
    </td>
    <td align="right" class="textoClave">Cantidad:</td>
    <td><input type="text" name="cantidadproducto" id="cantidadproducto" size="13" onkeyup="eventoIngresoCantidad(event)"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="left"><div id="msj_Subventana" class="tSubventana"></div></td>
    <td>&nbsp;</td>
  </tr>
</table>
  <div class="posboton1"><input type="button"  value="Guardar" id="botonrestaurante" onclick="insertarPedido()"/></div>
  <div class="posboton2"><input type="button"  value="Cancelar" id="botonrestaurante" onclick="closeVentanaPedido();"/></div>
 </div>
  
</div>
</body>
</html>
<script>
    cargarTotales(<?php echo $totalPedido;?>);
</script>
