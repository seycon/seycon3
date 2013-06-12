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
<link rel="stylesheet" href="../css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<script src="../js/jquery-1.5.1.min.js"></script>
<script src="../js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="../js/jquery.validate.js"></script>
<script type="text/javascript"  src="evento.js"></script>

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
    <td class="menuInterno" onclick="location.href='nuevo_turno.php'">Registro Turnos</td>
  </tr>
</table>

    </div></td>
    <td width="81%">
    <div class="contenDataNew">
    <div class="contenPrincipalNew">

      <div class="contenido_detalle">      
        <div class="divForm">REGISTRO DE TURNOS</div>
      
                 
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
                        <td colspan="4" align="center" valign="top" scope="col" >
                        
                        <table width="100%" border="0" height="268" class="divisionContenido">
                            <tr>
                              <td colspan="7" align="center" class="textoContenidoTitle1">&nbsp;</td>
                            </tr>
                            <tr>
                              <td ></td>
                              <td colspan="2" class="textoContenidoTitle1">
                              <table width="100%"  style="border:1px solid #FFF;">
                                <tr>
                                  <td colspan="2" align="center" >TURNO AM</td>
                                </tr>
                                <tr>
                                  <td width="51%" align="center">Desde (hh:mm:ss)</td>
                                  <td width="49%" align="center">Hasta (hh:mm:ss)</td>
                                </tr>
                              </table>
                              
                              </td>
                              <td width="12%">&nbsp;</td>
                              <td colspan="2" class="textoContenidoTitle1"><table width="98%"  style="border:1px solid #FFF;">
                                <tr>
                                  <td colspan="2" align="center" class="te" >TURNO PM</td>
                                </tr>
                                <tr>
                                  <td width="51%" align="center">Desde (hh:mm:ss)</td>
                                  <td width="49%" align="center">Hasta (hh:mm:ss)</td>
                                </tr>
                              </table></td>
                              <td width="13%">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="right" class="textoContenido">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td colspan="2" style="text-align: left;">&nbsp;</td>
                              <td style="text-align: left;">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="right" class="textoContenido">&nbsp;</td>
                              <td width="15%" align="center" ><input type="text" name="aminicio" id="aminicio" style ="width:80px;" /></td>
                              <td width="14%" align="center"><input type="text" name="aminicio2" id="aminicio2" style="width:80px;" /></td>
                              <td style="text-align: left;">&nbsp;</td>
                              <td width="13%" align="center"><input type="text" name="aminicio3" id="aminicio3" style="width:80px;" /></td>
                              <td width="13%" align="center"><input type="text" name="aminicio4" id="aminicio4" style="width:80px;" /></td>
                              <td style="text-align: left;">&nbsp;</td>
                            </tr>
                            <tr>
                              <td width="20%" align="right" class="textoContenido">&nbsp;</td>
                              <td colspan="3" style="text-align: left;">&nbsp;</td>
                              <td colspan="3" style="text-align: left;">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="right" class="textoContenido">&nbsp;</td>
                              <td colspan="3" style="text-align: left;">&nbsp;</td>
                              <td colspan="3" style="text-align: left;">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="right">&nbsp;</td>
                              <td colspan="3">&nbsp;</td>
                              <td colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="right">&nbsp;</td>
                              <td colspan="3">&nbsp;</td>
                              <td colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="23" align="right">&nbsp;</td>
                              <td colspan="3">&nbsp;</td>
                              <td colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="21" align="right">&nbsp;</td>
                              <td colspan="3">&nbsp;</td>
                              <td colspan="3">&nbsp;</td>
                            </tr>
                      </table></td>
                    </tr>
                    <tr>
                        <td width="34%" style="text-align: right;">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td width="22%" align="right" style="text-align:right;">
                        
                        
                        </td>
                        <td width="22%" align="right" class="textoContenido">&nbsp;</td>
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