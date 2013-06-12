<?php
session_start(); 
include("../conexion.php");
$db =new MySQL();

 if (!isset($_SESSION['idusuarioF'])){
  header("Location: index.php");	
 }

function filtro($cadena){
  return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
}


$transaccion = "insertar";
if(isset($_GET['nro'])){
  $transaccion = "modificar";	
  $sql = "SELECT * FROM solicitudF WHERE idsolicitud = ".filtro($_GET['nro']);
  $datoT = $db->arrayConsulta($sql);  
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Factory</title>
<link rel="stylesheet" href="estilo_principal.css" type="text/css"/>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<script src="../lib/Jtable.js"></script>
<script type="text/javascript"  src="Nsolicitud.js"></script>
<script>
 var setEstado = function(){
	if ($$("estado").checked){
	 $$("estado").value = "1";	
	}
    $$("estado").value = "0";
 }
</script>


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
    <td width="94%" class="menuInterno" onclick="location.href='nuevo_solicitud.php'">Nuevo Registro</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="menuInterno" onclick="location.href='listar_solicitud.php'">Listar Registro</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

    </div></td>
    <td width="81%">
    <div class="contenDataNew">
    <div class="contenPrincipalNew">

      <div class="contenido_detalle">      
        <div class="divForm">SOLICITUD DE PRODUCTOS</div>
      
                 
             <form id="form_usuario" action="nuevo_pedidoespecial.php" method="post">
               
              <div style="position:relative;margin:0 auto; width:98%;height:370px;">
                 <table width="90%" style="position:relative;left:20px;">
                    <tr>
                        <td width="78%" colspan="4" align="center" valign="top" scope="col" ><table width="106%" border="0" height="292" class="divisionContenido">
                            <tr>
                              <td width="19%" height="5" align="right" ></td>
                              <td width="23%" ></td>
                              <td colspan="3" ></td>
                              <td width="7%" ></td>
                            </tr>
                            <tr>
                              <td height="24" align="right" class="textoContenido">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td width="9%" align="right" class="textoContenido" style="text-align: right;">&nbsp;</td>
                              <td colspan="2" align="right" class="textoContenido" style="text-align:right;">Activo</td>
                              <td style="text-align: left;"><input type="checkbox" id="estado" name="estado" onclick="setEstado()" checked="checked" value="1"/></td>
                              <td width="3%" style="text-align: left;">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="5" colspan="7" align="right" style="border-top:1px solid #FFF;"></td>
                            </tr>
                            <tr>
                              <td height="24" colspan="7" align="right" class="textoContenido">
                              
                              <div class="submenu2">
                              <table width="104%" border="0">
                                <tr>
                                  <td width="10%" align="right" style="color:#000">Almacen:</td>
                                  <td width="19%" style="text-align: left;"><select name="almacen" id="almacen" style="width:110px" onchange="consultarProductos(this.value)">
                                  <option value="">-- Seleccione --</option>
                                    <?php
										$almacen = "select  left(sl.nombrecomercial,40),a.idalmacen, left(a.nombre,20) 
										from almacen a,sucursal sl  
										where a.sucursal=sl.idsucursal 										 
										and a.estado=1 order by sl.nombrecomercial;";		
										$db->imprimirComboGrupo($almacen,'','A- ',$datoT['idalmacen']);			
									 ?>
                                  </select></td>
                                  <td width="12%" align="right" style="color:#000">Producto:</td>
                                  <td><span style="text-align: left;">
                                    <select name="producto" id="producto" style="width:110px" onchange="consultarDatosProductos(this.value);">
                                      <option value="">-- Seleccione --</option>
                                      <?php
									  if (isset($datoT['idalmacen'])){
										$sql = "SELECT DISTINCT p.idproducto,left(p.nombre,25)as 'nombre' FROM ingresoproducto i, producto p, detalleingresoproducto d
										WHERE p.idproducto = d.idproducto AND d.idingresoprod = i.idingresoprod AND i.idalmacen =".filtro($datoT['idalmacen']).";"; 
										$consulta = $db->consulta($sql);
										$db->imprimirCombo($sql);
									  }
									  ?>
                                                                            
                                    </select>
                                  </span></td>
                                  <td width="10%" align="right" class="textoContenido" style="text-align: right;color:#000">Pedido:</td>
                                  <td width="10%" align="right">
                                    <input name="pedido" type="text" id="pedido" size="10" onkeyup="eventoText(event)" value="" />
                                  </td>
                                  <td width="9%"><div id="mensaje" style="color:#000;text-align:left;visibility:hidden;">Invalido</div></td>
                                  <td width="20%" align="left">
                                    <input type="button" name="Agregar" class="opcion_buttton" value="Agregar[Enter]" onclick="insertarNewItem('detallePedido');"/>
                                  </td>
                                </tr>
                                <tr>
                                  <td align="right" style="color:#000">Disponible:</td>
                                  <td style="text-align: left;"><input name="disponible" type="text" id="disponible" size="10" value="" disabled="disabled" /></td>
                                  <td align="right" style="color:#000">Stock Min.:</td>
                                  <td align="left"><input name="stock" type="text" id="stock" size="10" value="" disabled="disabled"/></td>
                                  <td align="right" class="textoContenido" style="text-align: right;color:#000">&nbsp;</td>
                                  <td align="right">&nbsp;</td>
                                  <td><span style="text-align: left;">
                                    <input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
                                    <input type="hidden"  id="idtransaccion" name="idtransaccion" value="<?php echo $_GET['nro'];?>" />
                                  </span></td>
                                  <td align="left">&nbsp;</td>
                                </tr>
                              </table>
                              </div>
                              
                              </td>
                            </tr>
                            <tr>
                              <td height="61" colspan="7" align="right" valign="top">
                             
                               <div class="tablaSolicitud">
                                <table width="100%" border="0" >
                                  <tr style="background-image: url(../iconos/fondo.jpg);">
                                    <td width="38" >&nbsp;</td>
                                    <th width="80">Nro.</th>
                                    <th width="432">Producto</th>
                                    <th width="120">Disponible</th>
                                    <th width="120">Stock Minimo</th>
                                    <th width="120">Pedido</th>
                                    <th width="206" style="display:none;">idproducto</th>
                                  </tr>
                                  <tbody id="detallePedido">
                                 
                                  <?php			
								  $total = 0;						
								   if (isset($_GET['nro'])){
									   $sql = "select p2.idproducto,p2.nombre,ds.pedido,p2.stockminimo, 
										(select sum(d.cantidadactual) as 'cantidad'  
										from ingresoproducto i,producto p,detalleingresoproducto d where 
										p.idproducto=d.idproducto and d.idingresoprod=i.idingresoprod and i.idalmacen=$datoT[idalmacen] 
										and p.idproducto=ds.idproducto group by p.idproducto) as 'disponible' from detallesolicitudF ds,producto p2 
										where ds.idsolicitud=$_GET[nro] and p2.idproducto=ds.idproducto order by ds.iddetallesolicitud;";
									   
									    $detalle = $db->consulta($sql);
									   $i = 0;
									   while($dato = mysql_fetch_array($detalle)){
										$i++; 
										$total = $total + $dato['pedido'];
										echo "
										  <tr> 
											<td align='center'><img src='../css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' /></td>
											<td align='center'>$i</td>
											<td >$dato[nombre]</td>
											<td align='center'>$dato[disponible]</td>
											<td align='center'>$dato[stockminimo]</td> 
											<td align='center'>$dato[pedido]</td>  											
											<td style='display:none'>$dato[idproducto]</td> 
										  </tr>";	 
									   }
								   }
								  ?>

                                  
                                   </tbody>                        
                                </table> 
                               </div>
                              
                              </td>
                            </tr>
                            <tr>
                              <td height="24" colspan="7" align="right" class="textoContenido"><table width="100%" border="0">
                                <tr>
                                  <td width="10%" align="right"><span class="textoContenido" style="text-align: right;">Detalle:</span></td>
                                  <td width="66%" style="text-align: left;"><input name="glosa" size="70" type="text" id="glosa" value="<?php echo $datoT['detalle'];?>" /></td>
                                  <td width="8%" align="right"><span class="textoContenido" style="text-align: right;"><span class="textoContenido" style="text-align: right;"><span class="textoContenido" style="text-align: right;">Total :</span></span></span></td>
                                  <td width="16%"  style="text-align: left;">
                                    <input name="total" type="text" id="total" size="15" disabled="disabled" value="<?php echo $datoT['total'];?>" />
                                  </td>
                                </tr>
                              </table></td>
                            </tr>
                      </table>
                      
                      
                      <table width="105%" border="0">
                        <tr>
                          <td> 
                          <div style="float: left; width:100%; height: 25px;">              
                          <input type="reset" class="opcion_contenido" value="Cancelar"/>
                          <input type="button" class="opcion_contenido"  value="Guardar" onclick="ejecutarTransaccion();"/>                
                          </div>  
                          </td>
                        </tr>
                      </table>
                      
                      
                      
                      
                      </td>
                    </tr>
                    </table>
</div>            
                  
             
              </form>
          
                  
    
    </div>
     
    </div>
    
    </td>
  </tr>
</table>  
  </div>
  <div class="pie">© 2012 Consultora Guez. All rights reserved.</div>    
</div>
</body>
</html>
<script>
  cargarTotales(<?php echo $total;?>);
</script>
