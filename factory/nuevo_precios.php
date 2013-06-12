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
	$sql = "delete from preciosF where idcupcakes=1 or idcupcakes=2 or idcupcakes=3 or idcupcakes=4 or idcupcakes=5 or idcupcakes=6";
	$db->consulta($sql);
	
   if ($_POST['transaccion'] == "insertar"){
	 $nro = 1;
	 for ($j=1;$j<=3;$j++){
	   for ($i=1;$i<=4;$i++){ 
		$rango = ($i>2) ?"40-100" : "1-39";
		$tipo = ($i%2 == 1)? "Grandes" : "Mini";	  
		if ($_POST[$nro] != ""){ 
		 $sql = "insert into preciosF(idprecio,monto,rango,tipo,idcupcakes) values(null,'".$_POST[$nro]."','$rango','$tipo',$j)";
		 $db->consulta($sql);	  
		}
		$nro++;
	   }
	 }
	 if ($_POST['ts1'] != ""){
	  $sql = "insert into preciosF(idprecio,monto,rango,tipo,idcupcakes) values(null,'".$_POST['ts1']."','1-39','Grandes',4)";
      $db->consulta($sql);	  
	 }
	 if ($_POST['ft1'] != ""){
	  $sql = "insert into preciosF(idprecio,monto,rango,tipo,idcupcakes) values(null,'".$_POST['ft1']."','1-39','Grandes',5)";
      $db->consulta($sql);	  
	 }
	 if ($_POST['ft2'] != ""){
	  $sql = "insert into preciosF(idprecio,monto,rango,tipo,idcupcakes) values(null,'".$_POST['ft2']."','1-39','Mini',5)";
      $db->consulta($sql);
	 }
	 if ($_POST['gb1'] != ""){
	  $sql = "insert into preciosF(idprecio,monto,rango,tipo,idcupcakes) values(null,'".$_POST['gb1']."','1-39','Grandes',6)";
      $db->consulta($sql);	  
	 }
	 
   }   
header("Location: nuevo_precios.php");
}

$transaccion = "insertar";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Factory</title>
<link rel="stylesheet" href="../css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<script src="../js/jquery-1.5.1.min.js"></script>
<script src="../js/jquery-ui-1.8.13.custom.min.js"></script>
<link rel="stylesheet" href="estilo_principal.css" type="text/css"/>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<script src="../js/jquery.validate.js"></script>
<script type="text/javascript"  src="evento.js"></script>
<script>

$(document).ready(function()
{

$("#form_datos").validate({});
});
 

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
    <td class="menuInterno" onclick="location.href='listar_usuario.php'">Registro Precios</td>
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
        <div class="divForm">PRECIOS OFICIALES DE CUPCAKE FACTORY</div>
      
                 
             <form id="form_datos" action="nuevo_precios.php" method="post">
               
              <div style="text-align: center; width:100%; height: 380px;">
                 <table width="86%" align="center">
                    <tr>
                      <td colspan="4" scope="col" style="height: 30px; text-shadow: 2px 2px 2px #fff; color: #000;" >
                      <input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />                      
                      </td>
                    </tr>
                    <tr>
                        <td colspan="4" align="center" valign="top" scope="col" >
                        
                        <table width="100%" border="0"  class="divisionContenido">
                            <tr>
                              <td align="center" class="textoContenidoTitle1">&nbsp;</td>
                              <td colspan="2" align="center" class="textoContenidoTitle1">
                              <table width="100%"  style="border:1px solid #FFF;">
                                <tr>
                                  <td colspan="2" align="center">Cant. de 1 a 39 unidades</td>
                                </tr>
                                <tr>
                                  <td width="51%" align="center">Grandes</td>
                                  <td width="49%" align="center">Mini</td>
                                </tr>
                              </table></td>
                              <td colspan="2" align="center" class="textoContenidoTitle1">
                              <table width="100%" border="0" style="border:1px solid #FFF;">
                                <tr>
                                  <td colspan="2" align="center">Cant. de 40 a 100 unidades</td>
                                </tr>
                                <tr>
                                  <td width="51%" align="center">Grandes</td>
                                  <td width="49%" align="center">Mini</td>
                                </tr>
                              </table></td>
                              <td align="center" class="textoContenidoTitle1">&nbsp;</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td width="13%" align="center" class="textoContenido2">Bs.</td>
                              <td width="12%" class="textoContenido2">Bs.</td>
                              <td width="14%" class="textoContenido2">Bs.</td>
                              <td width="13%" class="textoContenido2">Bs.</td>
                              <td width="4%" class="textoContenido2">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="right" class="textoContenido">Cupcakes solo con crema:</td>
                              <?php
							   $sql = "select *from preciosF where idcupcakes=1;";
							   $dato = $db->consulta($sql);
							   $fila = array();
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
							  ?>
                              
                              <td align="center"><input name="1" type="text" class="number" id="1" value="<?php echo $fila['1'];?>" size="10" /></td>
                              <td align="center"><input name="2" type="text" class="number" id="2" value="<?php echo $fila['2'];?>" size="10"/></td>
                              <td align="center"><input name="3" type="text" class="number" id="3" value="<?php echo $fila['3'];?>" size="10"/></td>
                              <td align="center"><input name="4" type="text" class="number" id="4" value="<?php echo $fila['4'];?>" size="10"/></td>
                              <td align="center">&nbsp;</td>
                            </tr>                            
                            <?php
							   $sql = "select *from preciosF where idcupcakes=2;";
							   $dato = $db->consulta($sql);
							   $fila = array();
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
							  ?>
                            <tr>
                              <td align="right" class="textoContenido">Cupcakes con crema y figura de fondant:</td>
                              <td align="center"><input name="5" type="text" class="number" id="5" value="<?php echo $fila['1'];?>" size="10"/></td>
                              <td align="center"><input name="6" type="text" class="number" id="6" value="<?php echo $fila['2'];?>" size="10"/></td>
                              <td align="center"><input name="7" type="text" class="number" id="7" value="<?php echo $fila['3'];?>" size="10"/></td>
                              <td align="center"><input name="8" type="text" class="number" id="8" value="<?php echo $fila['4'];?>" size="10"/></td>
                              <td align="center">&nbsp;</td>
                            </tr>
                            <?php
							   $sql = "select *from preciosF where idcupcakes=3;";
							   $dato = $db->consulta($sql);
							   $fila = array();
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
							  ?>
                            <tr>
                              <td width="44%" align="right" class="textoContenido">Cupcakes forrados en fondant:</td>
                              <td align="center"><input name="9" type="text" class="number" id="9" value="<?php echo $fila['1'];?>" size="10"/></td>
                              <td align="center"><input name="10" type="text" class="number" id="10" value="<?php echo $fila['2'];?>" size="10"/></td>
                              <td align="center"><input name="11" type="text" class="number" id="11" value="<?php echo $fila['3'];?>" size="10"/></td>
                              <td align="center"><input name="12" type="text" class="number" id="12" value="<?php echo $fila['4'];?>" size="10"/></td>
                              <td style="text-align: left;">&nbsp;</td>
                            </tr>
                            <?php
							   $sql = "select *from preciosF where idcupcakes=4;";
							   $dato = $db->arrayConsulta($sql);							  						  
							 ?>
                            <tr>
                              <td align="right" class="textoContenido">Tortas simples forradas:</td>
                              <td align="center"><input name="ts1" type="text" class="number" id="ts1" value="<?php echo $dato['monto'];?>" size="10"/></td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                            </tr>
                            <?php
							   $sql = "select *from preciosF where idcupcakes=5;";
							   $dato = $db->consulta($sql);
							   $fila = array();
							   while ($data = mysql_fetch_array($dato)){
								   if ($data['tipo'] == "Grandes")
								    $fila['1'] = $data['monto'];
									if ($data['tipo'] == "Mini")
								    $fila['2'] = $data['monto'];									
							   }							  
							  ?>
                            <tr>
                              <td align="right" class="textoContenido">Figuras tridimensionales:</td>
                              <td align="center"><input name="ft1" type="text" class="number" id="ft1" value="<?php echo $fila['1'];?>" size="10"/></td>
                              <td align="center"><input name="ft2" type="text" class="number" id="ft2" value="<?php echo $fila['2'];?>" size="10"/></td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                            </tr>
                             <?php
							   $sql = "select *from preciosF where idcupcakes=6;";
							   $dato = $db->arrayConsulta($sql);							  						  
							 ?>
                            <tr>
                              <td align="right" class="textoContenido">Garantia de Base de Vidrio:</td>
                              <td align="center"><input name="gb1" type="text" class="number" id="gb1" value="<?php echo $dato['monto'];?>" size="10"/></td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="right" class="textoContenido">&nbsp;</td>
                              <td align="center">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="right" class="textoContenido">&nbsp;</td>
                              <td align="center">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="right" class="textoContenido">&nbsp;</td>
                              <td align="center">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                            </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td width="24%" style="text-align: right;">&nbsp;</td>
                      <td width="30%">&nbsp;</td>
                      <td width="20%" align="right" style="text-align:right;"></td>
                      <td width="26%" align="right" class="textoContenido">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right;">
                        
                        <table width="105%" border="0">
                        <tr>
                          <td> 
                          <div style="float: left; width:100%; height: 25px;">              
                          <input type="reset" class="opcion_contenido" value="Cancelar"/>
                          <input type="submit" class="opcion_contenido" id="add_usuario"  value="Guardar"/>                
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