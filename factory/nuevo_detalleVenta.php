<?php
session_start(); 
include("../conexion.php");
$db =new MySQL();

 if (!isset($_SESSION['idusuarioF'])){
  header("Location: index.php");	
 }
 
 $idatencion = $_GET['atencion'];
 $idpedido = $_GET['pedido'];
 $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
 $tc = $db->getCampo('dolarcompra',$sql); 
 
 $sql = "select *from notaventaF where idatencion=$idatencion";
 $datosNota = $db->arrayConsulta($sql);
 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Factory</title>
<link rel="stylesheet" href="estilo_principal.css" type="text/css"/>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<script type="text/javascript"  src="Nventa.js"></script>
<script src="../lib/Jtable.js"></script>
<link href="../autocompletar/estilo1.css" rel="stylesheet" type="text/css" />
<script src="../autocompletar/funciones.js"></script>
</head>

<body>
<div class="datosUsuario">
<table width="100%" border="0">
  <tr>
    <td width="495" height="17px"></td>
    <td width="172" align="right" class="textoUserNew2">FECHA</td>
    <td width="100" class="textoUserNew"><?php echo date('d/m/Y');?></td>
    <td width="17" class="user"></td>
    <td width="145" class="textoUserNew" align="left"><?php echo $_SESSION['nombreusuarioF'];?></td>
    <td width="21" class="userClose">&nbsp;</td>
    <td width="323" class="textoUserNew"><a style="color:#FFF" href="cerrar.php">Cerrar Sesión</a></td>
  </tr>
</table>
</div>
<div class="cuerpo">
	<div class="cabecera"><div class="cabeceraInterior"></div></div>    
    <div class="menuNew">
    <div class="tituloNew"><div class="titleNew"> <div class="textoInternoPrincipal">MENU BISTRON</div>  </div>  </div>
    <div class="optionNew2" onclick="location.href='inicio_restaurante.php'"><div class="textoInterno">Inicio</div></div>
    <?php
	function generarMenu($menu){
	  for ($i=0;$i<count($menu);$i++){
		 $clase = "optionNew"; 
		 if ($menu[$i]['titulo'] == "Reportes")
		  $clase =  "optionNew2"; 
		$url = $menu[$i]['url']."";
		echo '<div class='.$clase.' onclick="location.href=&#039'.$url.'&#039"><div class="textoInterno">'.$menu[$i]['titulo'].'</div></div>';
	  }
	}
	generarMenu($_SESSION['menuFactory']);
	?>
    </div>  
    <div class="contenNew">
    <table width="100%" height="100%" border="0">
  <tr>
    <td width="19%"><div class="contenMenu">
    <table width="100%" border="0">
  <tr>
    <td width="6%"></td>
    <td width="94%" class="menuInterno" onclick="location.href='nuevo_ventas.php'">Atención de  Mesas</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td ></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td ></td>
  </tr>
  <tr>
    <td height="328" colspan="2" valign="top">      
      
      <div class="contenedorProductos">
        <div class="cabeceraProductos"><div class="textocabeceraProductos">Menu</div>  </div>
        <div class="ingresarProducto">
          <table width="100%" border="0">
            <tr>
              <td class="textoIngresoProducto">Buscar:</td>
              <td><input type="text" name="producto" id="producto" style="width:100%" onkeyup="consultarProductos(this.value)" autocomplete="off"/></td>
              </tr>
            </table>        
          </div>
        <div class="listaProductos">
          <ul id="productos">
            <?php		   
		   $sql = "select idcombinacion,left(nombre,17) as 'nombre1',total as 'precio',nombre from combinacion where nombre like '%' and estado=1;";
	       $producto = $db->consulta($sql);
	       while($dato = mysql_fetch_array($producto)){
		     echo "<li onclick='openVentanaPedido(&quot;$dato[nombre]&quot;,&quot;$dato[precio]&quot;,&quot;$dato[idcombinacion]&quot;)'>$dato[nombre1]</li>";		  
	       }
		 ?>
            </ul>
          </div>
        
      </div>    </td>
    </tr>
</table>

    
    </div></td>
    <td width="81%">
    <div class="contenDataNew">
    <div class="contenPrincipalNew">
    
    <div class="contenido_detalle">
    <div class="divForm">VENTA DE PRODUCTOS</div>
      <table width="100%" border="0">
        <tr>
          <td width="14%" align="right"><input type="hidden" id="idpedido" name="idpedido" value="<?php echo $idpedido;?>"/>            <input type="hidden" id="idatencion" name="idatencion" value="<?php echo $idatencion;?>"/></td>
          <td width="17%" align="left">&nbsp;</td>
          <td width="14%" align="right">&nbsp;</td>
          <td width="27%" align="left">&nbsp;</td>
          <td width="7%" align="left">&nbsp;</td>
          <td width="21%" align="left">&nbsp;</td>
        </tr>
        <tr>
          <td align="right" class="textoContenido">Recibido:</td>
          <td align="left"><select name="receptor" id="receptor"  onchange="cambiarDependencias();" style="width:130px; background:#FFF;border:solid 1px #999;">
            <?php
					   $selec = $datosNota['tipopersona']; 
					   $tipo = array("cliente","proveedor","trabajador","otros");
					   for ($i=0;$i<count($tipo);$i++){
						  $atributo = ""; 
						  if ($selec == $tipo[$i]){
						  $atributo = "selected='selected'";	
						  }
						  echo "<option value='$tipo[$i]' $atributo>".ucfirst($tipo[$i])."</option>";
					   }	
	                 ?>
          </select></td>
          <td align="right" class="textoContenido">Recibimos de<span class="rojo">*</span>:</td>
          <td align="left"><input style="width:80%" type="text" id="texto" onclick="this.select()" onkeyup="tipoBusqueda(event);" 
                  value="<?php if (isset($datosNota['nombrepersona'])){
	                 echo $datosNota['nombrepersona'];
	               }
	              ?>" autocomplete="off"/>
                  <div id="cliente" class="divresultado"></div>
            <input type="hidden" id="idpersonarecibida" value="<?php echo $datosNota['idpersona'];?>" /></td>
          <td align="left" class="textoContenido">Venta:</td>
          <td align="left"><select name="tiponota" id="tiponota" onchange="limpiarDetalle();" style="width:130px;background:#FFF;border:solid 1px #999;">
            <?php
			 $selec = $datosNota['tiponota']; 
			 $tipo = array("Contado","Credito");
			 for ($i=0;$i<count($tipo);$i++){
				$atributo = ""; 
				if ($selec == $tipo[$i]){
				$atributo = "selected='selected'";	
				}
				echo "<option value='$tipo[$i]' $atributo>$tipo[$i]</option>";
			 }	
			?>
          </select></td>
        </tr>
        
  </table>

            
                 <div class="listado1">      
                 <table align="center" width="100%">
                 <thead>
                 <tr style="background-image: url(../fondo.jpg); color:#000; ">

                    <td width="10">&nbsp;</td>
                    <th width="50"><div align="center">Nº</div></th>
                    <th align="center" width="223">Producto</th>
                    <th width="117" align="center">Precio Unitario</th>
                    <th width="70" align="center">Cantidad</th>
                    <th width="82" align="center">Total</th>
                    <th style="display:none;" width="82">Id</th>
            	 </tr>
                 </thead>
                 <tbody id="detallepedidoProductos">              
                   <?php
					 $sql = "select da.iddetallenotaf,left(c.nombre,20)as 'nombre',da.cantidad,da.precio,da.nroatencion from detallenotaF da,combinacion c,notaventaF n where 
					 da.idcombinacion=c.idcombinacion and da.idnotaventa=n.idnotaventa and n.idatencion=$idatencion and da.estado=1 order by da.iddetallenotaf;";
					 $pedido = $db->consulta($sql);
					 $i = 0;
					 $totalPedido = 0;
					 while ($dato = mysql_fetch_array($pedido)){ 
					 $i++;
					 $precio = $dato['precio'];
					 $cantidad = $dato['cantidad'];
					 $total = $precio * $cantidad;
					 $totalPedido = $totalPedido + $total;
					 echo " <tr >
					  <td align='center'><img src='../css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' style='cursor:pointer' /></td>
					  <td align='center' class='celdacuaderno'>$dato[nroatencion]</td>
					  <td align='left' class='celdacuaderno'>$dato[nombre]</td>
					  <td align='center' class='celdacuaderno'>".number_format($precio,2)."</td>
					  <td align='center' class='celdacuaderno'>".number_format($cantidad,2)."</td>
					  <td align='center' class='celdacuaderno'>".number_format($total,2)."</td>   
					  <td style='display:none'>$dato[iddetallenotaf]</td>
					</tr>";
  
				   }
				  ?>      
                 
                 
                </tbody>             
          </table>
          </div>
          
          <table width="100%" border="0">
          <tr>
            <td width="8%">&nbsp;</td>
            <td width="12%" align="right" >
            <input type="button" id="cobrar" name="cobrar" class="opcion_contenido" style="width:90px;" value="Cerrar Mesa" onclick="insertarCobranza()"/></td>
            <td width="14%" align="right" class="textoContenido">
            <input type="button" id="cobrar2" name="cobrar2" class="opcion_contenido" value="Imprimir" onclick="getReporte1()"/>
            </td>
            <td width="41%" align="right" class="textoContenido">Total:</td>
            <td width="19%"><input type="text" id="totalNota" name="totalNota" disabled="disabled" value="0"/></td>
            <td width="6%">&nbsp;</td>
          </tr>
          </table>         
          </div>
    
    
    
    
    </div>
    </div>
    
    </td>
  </tr>
</table>

    
  </div>
  <div class="pie">© 2012 Consultora Guez. All rights reserved.</div>     
    
    
</div>


<div id="modal2" class="modal2"></div>
 <div id="modalInterior2" class="modalInterior2">
 <div class="headerInterior"><div class="tituloVentanaclave">Pedido</div></div>  
 <div class="posicionCloseSub" onclick="closeVentanaPedido();"><img src="../iconos/borrar2.gif" width="12" height="12"></div>
  <br />
  <table width="100%" border="0">
  <tr>
    <td>&nbsp;</td>
    <td align="right" class="textoClave">&nbsp;</td>
    <td>&nbsp;</td>
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
    <td><input type="hidden" id="idcombinacion" name="idcombinacion" /></td>
    <td align="right" class="textoClave">Cantidad:</td>
    <td><input type="text" name="cantidadproducto" id="cantidadproducto" size="13" onkeyup="event_ventaproducto(event)"/></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td align="right" class="textoClave"></td>
    <td><div class="msgError" id="mensaje"></div></td>
    <td></td>
  </tr>
</table>
  <div class="posboton1"><input type="button"  value="Guardar" class="opcion_contenido" onclick="insertarPedido()" /></div>
  <div class="posboton2"><input type="button"  value="Cancelar" class="opcion_contenido" onclick="closeVentanaPedido();"/></div>
 </div>





</body>
</html>
<script>
 cargarTotales(<?php echo $totalPedido;?>);
</script>
