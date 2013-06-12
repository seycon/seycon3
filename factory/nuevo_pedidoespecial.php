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
  $sql = "SELECT * FROM pedidoespecialF WHERE idpedido = ".filtro($_GET['nro']);
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
<script type="text/javascript"  src="Npedidoespecial.js"></script>
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
    <td width="94%" class="menuInterno" onclick="location.href='nuevo_pedidoespecial.php'">Nuevo Registro</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="menuInterno" onclick="location.href='listar_pedidoespecial.php'">Listar Registro</td>
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
        <div class="divForm">REGISTRO PEDIDO ESPECIAL</div>
      
                 
             <form id="form_usuario" action="nuevo_pedidoespecial.php" method="post">
               
              <div style="position:relative;margin:0 auto; width:100%;height:380px;">
                 <table width="90%" style="position:relative;left:20px;">
                    <tr>
                        <td width="78%" colspan="4" align="center" valign="top" scope="col" ><table width="106%" border="0" height="292" class="divisionContenido">
                            <tr>
                              <td width="19%" height="5" align="right" ></td>
                              <td width="21%" ></td>
                              <td colspan="3" ></td>
                              <td width="7%" ></td>
                            </tr>
                            <tr>
                              <td height="24" align="right" class="textoContenido">Señor(es):</td>
                              <td style="text-align: left;"><input name="nombre" type="text" id="nombre" value="<?php echo $datoT['nombre'];?>" /></td>
                              <td width="21%" align="right" class="textoContenido" style="text-align: right;">Dia de Entrega:</td>
                              <td colspan="2" align="right" class="textoContenido" style="text-align:left;">
                              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td width="70%"><span class="textoContenido" style="text-align:left;">
                                    <input name="dia" type="text" id="dia" value="<?php echo $datoT['dia'];?>" />
                                  </span></td>
                                  <td width="30%"><span class="textoContenido" style="text-align:left;"><span class="textoContenido" style="text-align:right;">Activo<span class="textoContenido" style="text-align: right;"><span style="text-align: left;">
                                    <input type="checkbox" id="estado" name="estado" onclick="setEstado()" checked="checked" value="1"/>
                                  </span></span></span></span></td>
                                </tr>
                              </table></td>
                              <td colspan="2" align="right" class="textoContenido" style="text-align: right;">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="24" align="right" class="textoContenido"><span class="textoContenido" style="text-align: right;">Telefono:</span></td>
                              <td style="text-align: left;"><input name="telefono" type="text" id="telefono" value="<?php echo $datoT['telefono'];?>" /></td>
                              <td align="right" class="textoContenido" style="text-align: right;">
                              <span class="textoContenido" style="text-align: right;">Hora de Entrega:</span></td>
                              <td colspan="2" align="right" class="textoContenido" style="text-align:left;">
                                <input name="hora" type="text" id="hora" value="<?php echo $datoT['hora'];?>" />
                              </td>
                              <td colspan="2" style="text-align: left;">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="24" align="right" class="textoContenido">Pirotines:</td>
                              <td style="text-align: left;"><input name="pirotines" type="text" id="pirotines" value="<?php echo $datoT['pirotines'];?>" /></td>
                              <td align="right" class="textoContenido" style="text-align: right;">Masa:</td>
                              <td colspan="2" align="right" class="textoContenido" style="text-align:left;">
                                <input name="masa" type="text" id="masa" value="<?php echo $datoT['masa'];?>" />
                              </td>
                              <td colspan="2" class="textoContenido" style="text-align: right;">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="24" align="right" class="textoContenido">Crema:</td>
                              <td style="text-align: left;"><input name="crema" type="text" id="crema" value="<?php echo $datoT['crema'];?>" /></td>
                              <td align="right" class="textoContenido" style="text-align: right;">Relleno:</td>
                              <td colspan="2" align="right" class="textoContenido" style="text-align:left;">
                                <input name="relleno" type="text" id="relleno" value="<?php echo $datoT['relleno'];?>" />
                              </td>
                              <td colspan="2" style="text-align: left;">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="5" colspan="7" align="right" style="border-top:1px solid #FFF;"></td>
                            </tr>
                            <tr>
                              <td height="24" colspan="7" align="right" class="textoContenido">
                              
                              <div class="submenu">
                              <table width="104%" border="0">
                                <tr>
                                  <td width="11%" align="right" style="color:#000">Producto:</td>
                                  <td width="15%" style="text-align: left;">
                                    <select name="producto" id="producto" style="width:130px">
                                      <?php
								  $sql = "select idcupcakes,nombre from cupcakesF;";
								  $db->imprimirCombo($sql,'');      
                                ?>
                                    </select>
                                 </td>
                                  <td width="7%" align="right" style="color:#000">Tipo:</td>
                                  <td width="8%">
                                  <select name="tipo" id="tipo" style="width:70px">
                                      <?php
								       $selec = ''; 
									   $tipo = array("Grandes","Mini");
									   for ($i=0;$i<count($tipo);$i++){
										  $atributo = ""; 
										  if ($selec == $tipo[$i]){
										  $atributo = "selected='selected'";	
										  }
										  echo "<option value='$tipo[$i]' $atributo>$tipo[$i]</option>";
									   }	   
                                     ?>
                                    </select>
                                  </td>
                                  <td width="12%" align="right" class="textoContenido" style="text-align: right;color:#000">Cantidad:</td>
                                  <td width="15%" align="right">
                                    <input name="cantidad" type="text" id="cantidad" size="15" onkeyup="eventoText(event)" value="" />
                                  </td>
                                  <td width="12%"><div id="mensaje" style="color:#000;text-align:left;visibility:hidden;">Invalido</div></td>
                                  <td width="20%" align="left">
                                    <input type="button" name="Agregar" class="opcion_buttton" value="Agregar[Enter]" onclick="insertarNewItem('detallePedido');"/>
                                  </td>
                                </tr>
                              </table>
                              </div>
                              
                              </td>
                            </tr>
                            <tr>
                              <td height="61" colspan="7" align="right" valign="top">
                             
                               <div class="tablaPedido">
                                <table width="100%" border="0" >
                                  <tr style="background-image: url(../iconos/fondo.jpg);">
                                    <td width="38" >&nbsp;</td>
                                    <th width="80">Nro.</th>
                                    <th width="432">Cupcakes</th>
                                    <th width="120">Tipo</th>
                                    <th width="120">Cantidad</th>
                                    <th width="120">Precio</th>
                                    <th width="148">Total</th>
                                    <th width="206" style="display:none;">idcupcakes</th>
                                  </tr>
                                  <tbody id="detallePedido">
                                 
                                  <?php
									$totalG = 0;
								   if (isset($_GET['nro'])){
									   $sql = "select c.idcupcakes,c.nombre,d.tipo,d.precio,d.cantidad from detallepedidoF d,cupcakesF c where 
									   d.idcupcakes=c.idcupcakes and d.idpedido=$_GET[nro] order by iddetallepedido asc";
									 $detalle = $db->consulta($sql);
									 $i = 0;
									   while($dato = mysql_fetch_array($detalle)){
										$i++; 
										$total = $dato['precio']* $dato['cantidad'];
										$totalG = $totalG + $total;
										echo "
										  <tr> 
											<td align='center'><img src='../css/images/borrar.gif' title='Anular' alt='borrar' onclick='eliminarFila(this)' /></td>
											<td align='center'>$i</td>
											<td >$dato[nombre]</td>
											<td align='center'>$dato[tipo]</td>
											<td align='center'>".number_format($dato['cantidad'],2)."</td> 
											<td align='center'>".number_format($dato['precio'],2)."</td>   
											<td align='center'>".number_format($total,2)."</td>  
											<td style='display:none'>$dato[idcupcakes]</td> 
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
                                  <td width="14%" align="right">A Cuenta:</td>
                                  <td width="18%" style="text-align: left;">
                                    <input name="acuenta" size="15" type="text" id="acuenta" value="<?php echo $datoT['acuenta'];?>" onkeyup="setSaldo();"/>
                                  </td>
                                  <td width="10%" align="right"><span class="textoContenido" style="text-align: right;">Saldo:</span></td>
                                  <td width="16%"><span class="textoContenido" style="text-align: right;"><span class="textoContenido" style="text-align: right;"><span style="text-align: left;">
                                    <input name="saldo" type="text" id="saldo" size="15" value="<?php echo ($datoT['total']-$datoT['acuenta']);?>" disabled="disabled"/>
                                  </span></span></span></td>
                                  <td width="25%" align="right"><span class="textoContenido" style="text-align: right;"><span class="textoContenido" style="text-align: right;"><span class="textoContenido" style="text-align: right;">Total Bs:</span></span></span></td>
                                  <td width="17%"  style="text-align: left;">
                                    <input name="total" type="text" id="total" size="15" disabled="disabled" value="<?php echo $datoT['total'];?>" />
                                  </td>
                                </tr>
                              </table></td>
                            </tr>
                            <tr>
                              <td height="24" colspan="5" align="right" class="textoContenido"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td width="15%" class="textoContenido" style="text-align: right;">Detalle:</td>
                                  <td width="85%" align="left"><input name="detalle" size="70" type="text" id="detalle" value="<?php echo $datoT['glosa'];?>" /></td>
                                </tr>
                              </table></td>
                              <td colspan="2" style="text-align: left;">
                              <input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
                              <input type="hidden"  id="idtransaccion" name="idtransaccion" value="<?php echo $_GET['nro'];?>" />
                              </td>
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
<?php
$preciosFactory = array();
for ($i=1;$i<=6;$i++){
	 $sql = "select *from preciosF where idcupcakes=$i;";
	 $dato = $db->consulta($sql);
	 $fila = array('1'=>0,'2'=>0,'3'=>0,'4'=>0);
	 $subPrecio = array();
	 while ($data = mysql_fetch_array($dato)){
		 if ($data['rango'] == "1-39" and $data['tipo'] == "Grandes")
		  $fila['1'] = $data['monto'];
		  if ($data['rango'] == "1-39" and $data['tipo'] == "Mini")
		  $fila['2'] = $data['monto'];
		  if ($data['rango'] == "40-100" and $data['tipo'] == "Grandes")
		  $fila['3'] = $data['monto'];
		  if ($data['rango'] == "40-100" and $data['tipo'] == "Mini")
		  $fila['4'] = $data['monto'];
	 }	 
	 $subPrecio['<39'] = array('Grandes'=>$fila['1'] ,'Mini'=>$fila['2']);
	 $subPrecio['>39'] = array('Grandes'=>$fila['3'] ,'Mini'=>$fila['4']);
	 $preciosFactory[$i] = $subPrecio;
}
?>
<script>
  preciosFactory = <?php echo json_encode($preciosFactory); ?>;   
  cargarTotales(<?php echo $totalG;?>);
</script>

