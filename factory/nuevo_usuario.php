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

if (isset($_POST['transaccion'])){
   if ($_POST['transaccion'] == "insertar"){
     $sql = "INSERT INTO usuarioF(idusuario,idtrabajador,tipo,login,clave,estado) VALUES (NULL,'".filtro($_POST['trabajador'])."','".filtro($_POST['tipo'])
	 ."','".filtro($_POST['login'])."','".filtro($_POST['clave'])."','".filtro($_POST['estado'])."');";    
	 $db->consulta($sql);
	 $codigo = $db->getMaxCampo("idusuario","usuarioF");
	 if ($_POST['privilegio'] != ""){
	 $privilegios = array();	 
	 $privilegios = explode(",",$_POST['privilegio']);
	 for ($i=0;$i<count($privilegios);$i++){
		$sql = "insert into detalleprivilegioF values(null,'".$privilegios[$i]."','$codigo')"; 
		$db->consulta($sql);
	 }	 
	 }
   } 
  if ($_POST['transaccion'] == "modificar"){
	  $sql = "UPDATE usuarioF SET idtrabajador='".filtro($_POST['trabajador'])."',tipo='".filtro($_POST['tipo'])."',login='".filtro($_POST['login'])
	  ."',clave='".filtro($_POST['clave'])."',estado='".filtro($_POST['estado'])."'  WHERE idusuario= '".$_POST['idusuario']."';";	
	  $db->consulta($sql);
   	  $codigo = $_POST['idusuario'];
	  $sql = "delete from detalleprivilegioF where idusuario=$codigo";
	  $db->consulta($sql);
	  if ($_POST['privilegio'] != ""){
 	  $privilegios = array();	  
	  $privilegios = explode(",",$_POST['privilegio']);
	  for ($i=0;$i<count($privilegios);$i++){
		$sql = "insert into detalleprivilegioF values(null,'".$privilegios[$i]."','$codigo')"; 
		$db->consulta($sql);
	  }	 
	  }
  }
header("Location: nuevo_usuario.php");
}

$transaccion = "insertar";
if(isset($_GET['nro'])){
  $transaccion = "modificar";	
  $sql = "SELECT * FROM usuarioF WHERE idusuario = ".filtro($_GET['nro']);
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
<script type="text/javascript"  src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript"  src="evento.js"></script>
<script>
 var setEstado = function(){
	if ($$("estado").checked){
	 $$("estado").value = "1";	
	}
    $$("estado").value = "0";
 }
 
 var enviarFormulario = function(){
	var datos = ["cobrarmesa","pedidoespecial","ventaproducto","solicitarproducto","registrarusuario","reportes"]; 
	var privilegio = new Array();
	var j=0;
	for (var i=0;i<datos.length;i++){
	  if ($$(datos[i]).checked){
		 privilegio[j] = $$(datos[i]).value;
		 j++; 
	  }
	}
	$$("privilegio").value = privilegio;
	$$("form_usuario").submit();
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
    <td width="94%" class="menuInterno" >CONFIGURACION</td>
  </tr>
  <tr>
    <td></td>
    <td class="menuInterno" onclick="location.href='nuevo_usuario.php'">Registro Usuario</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="menuInterno" onclick="location.href='listar_usuario.php'">Listar Usuario</td>
  </tr>
 <tr>
    <td>&nbsp;</td>
    <td class="menuInterno" onclick="location.href='nuevo_precios.php'">Registro Precios</td>
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
        <div class="divForm">REGISTRO DE USUARIO</div>
      
                 
             <form id="form_usuario" action="nuevo_usuario.php" method="post">
               
              <div style="text-align: center; width:100%; height: 380px;">
                 <table width="80%" align="center">
                    <tr>
                      <td colspan="4" scope="col" style="height: 30px; text-shadow: 2px 2px 2px #fff; color: #000;" >
                      <input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
                      <input type="hidden"  id="privilegio" name="privilegio" />
                      <input type="hidden"  id="idusuario" name="idusuario" value="<?php echo $_GET['nro'];?>" />
                      </td>
                    </tr>
                    <tr>
                        <td width="34%" colspan="2" align="center" valign="top" scope="col" >
                        
                        <table width="100%" border="0" height="273" class="divisionContenido">
                            <tr>
                              <td colspan="2" align="center" class="textoContenidoTitle1">DATOS DE USUARIO</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="right" class="textoContenido">Trabajador:</td>
                              <td style="text-align: left;">
                              <select name="trabajador" id="trabajador" style="width:130px">
                                <option value="" >-- Seleccione --</option>
                                <?php
                            $sql = "select idtrabajador,left(concat(nombre,' ',apellido),20)as 'nombre' from trabajador where estado=1;";
                            $db->imprimirCombo($sql,$datoT['idtrabajador']);      
                            ?>
                              </select>
                              </td>
                            </tr>
                            <tr>
                              <td align="right" class="textoContenido">Tipo de Trabajador:</td>
                              <td style="text-align: left;">
                              <select name="tipo" style="width:130px">
                                <?php
							 $selec = $datoT['tipo']; 
							 $tipo = array("Administrador","Cajero","Garzón");
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
                            </tr>
                            <tr>
                              <td width="45%" align="right" class="textoContenido">Login:</td>
                              <td width="55%" style="text-align: left;">
                              <input type="text" name="login" value="<?php echo $datoT['login'];?>" style="width:125px" />
                              </td>
                            </tr>
                            <tr>
                              <td align="right" class="textoContenido">Contraseña:</td>
                              <td style="text-align: left;">
                                <input type="password" name="clave" value="<?php echo $datoT['login'];?>" style="width:125px"/>
                              </td>
                            </tr>
                            <tr>
                              <td align="right">&nbsp;</td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="right">&nbsp;</td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="right">&nbsp;</td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="21" align="right">&nbsp;</td>
                              <td>&nbsp;</td>
                            </tr>
                      </table></td>
                        <td colspan="2" align="center" valign="top" scope="col" >
                        
                        
                        <table width="80%" height="273" align="center" border="0" class="divisionContenido">
                          <tr>
                            <td colspan="2" align="center" class="textoContenidoTitle1">PRIVILEGIOS</td>
                          </tr>
                          <tr>
                            <td align="right">&nbsp;</td>
                            <td>
							<?php  
							$sql = "select *from detalleprivilegioF where idusuario='$_GET[nro]'";
							$dato = $db->consulta($sql);
							$sPrivilegio = array();
							while ($data = mysql_fetch_array($dato)){
							  $sPrivilegio[$data['idprivilegios']]="1";	
							}						
							?></td>
                          </tr>
                          <tr>
                            <td align="right"  class="textoContenido">Cobrar Mesa</td>
                            <td style="text-align: left;">
                            <?php
							$atributo = "";
							if (isset($sPrivilegio["1"]))
							 $atributo = "checked='checked'";
							?>
                              <input type="checkbox" name="cobrarmesa" <?php echo $atributo;?> id="cobrarmesa" value="1" />
                            </td>
                          </tr>
                          <tr>
                            <td align="right"  class="textoContenido">Pedido Especial</td>
                            <td style="text-align: left;">
                            <?php
							$atributo = "";
							if (isset($sPrivilegio["2"]))
							 $atributo = "checked='checked'";
							?>
                              <input type="checkbox" name="pedidoespecial" <?php echo $atributo;?> id="pedidoespecial" value="2"/>
                            </td>
                          </tr>
                          <tr>
                            <td align="right"  class="textoContenido">Venta Productos</td>
                            <td style="text-align: left;"> 
                            <?php
							$atributo = "";
							if (isset($sPrivilegio["3"]))
							 $atributo = "checked='checked'";
							?>
                              <input type="checkbox" name="ventaproducto" <?php echo $atributo;?> id="ventaproducto" value="3"/>
                            </td>
                          </tr>
                          <tr>
                            <td width="56%" align="right"  class="textoContenido">Solicitar Producto</td>
                            <td width="44%" style="text-align: left;">
                            <?php
							$atributo = "";
							if (isset($sPrivilegio["4"]))
							 $atributo = "checked='checked'";
							?>
                              <input type="checkbox" name="solicitarproducto" <?php echo $atributo;?> id="solicitarproducto" value="4"/>
                            </td>
                          </tr>
                          <tr>
                            <td align="right"  class="textoContenido">Registrar Usuario</td>
                            <td style="text-align: left;">
                            <?php
							$atributo = "";
							if (isset($sPrivilegio["5"]))
							 $atributo = "checked='checked'";
							?>
                              <input type="checkbox" name="registrarusuario" <?php echo $atributo;?> id="registrarusuario" value="5"/>
                            </td>
                          </tr>
                          <tr>
                            <td align="right"  class="textoContenido">Reportes</td>
                            <td style="text-align: left;">
                            <?php
							$atributo = "";
							if (isset($sPrivilegio["6"]))
							 $atributo = "checked='checked'";
							?>
                              <input type="checkbox" name="reportes" <?php echo $atributo;?> id="reportes" value="6"/>
                            </td>
                          </tr>
                          <tr>
                            <td align="right">&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="35" align="right">&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td width="22%" align="right" style="text-align:right;">
                        
                        
                        </td>
                        <td width="22%" align="right" class="textoContenido">Activo<input type="checkbox" name="estado" onclick="setEstado()" checked="checked" value="1"/></td>
                    </tr>
                    <tr>
                      <td style="text-align: right;">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td align="right" style="text-align:right;"></td>
                      <td align="right" class="textoContenido">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="4" >
                      
                      <table width="100%" border="0">
                        <tr>
                          <td> 
                          <div style="float: left; width:100%; height: 25px;">              
                          <input type="reset" class="opcion_contenido" value="Cancelar"/>
                          <input type="button" class="opcion_contenido" id="add_usuario" onclick="enviarFormulario()" value="Guardar"/>                
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