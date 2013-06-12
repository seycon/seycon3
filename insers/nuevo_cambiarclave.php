<?php
    session_start();
    include("../conexion.php");
    $db = new MySQL();
    if (!isset($_SESSION['nombretrestaurante'])) {
        header("Location: index.php");	
    }
 
    if (isset($_POST['transaccion'])) {
		$claveUser = md5($_POST['clave']);
        $claveUser2 = crc32($claveUser);
        $claveUser3 = crypt($claveUser2, "xmas");
        $claveFinal = sha1("xmas".$claveUser3);
		
        if($_POST['transaccion'] == "insertar") {	  
	        $sql = "update usuariorestaurante set clave='$claveFinal' where idusuario=$_SESSION[idusuariorestaurante]";  
            $db->consulta($sql);
        }
        header("Location: nuevo_cambiarclave.php");
    } 
    $transaccion = "insertar";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Restaurante</title>
<link rel="stylesheet" href="restaurante.css" type="text/css"/>
<link rel="stylesheet" href="../css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<script src="../js/jquery-1.5.1.min.js"></script>
<script src="../js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="../js/jquery.validate.js"></script>
<script>
 var setValorCheck = function(id){
	if ($$(id).checked){
	  $$(id).value = "1";	
	}else{
	  $$(id).value = "0";	
	}
 }
 
 $(document).ready(function()
 {	 
	$("#formulario").validate({});	
 });
</script>
</head>

<body>
 <div class="contendedor">
 
 
   <div class="tela_izq"></div>
   <div class="tela_cierreizq"></div>
   <div class="tela_der"></div>
   <div class="tela_cierreder"></div> 
   <div class="derechosReservados">Copyright © Consultora Guez – Diseñado y Desarrollado
   </div>
   <div class="header"><div class="gradient7"><h1><span></span>Discoteca</h1></div>  </div>
   <div class="subTitulo">Nuestros Servicios al Alcance del Cliente.</div>
   
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
    <td height="336" colspan="2">
    <div class="contenedorMenu">
     <div id="opcion1" onclick="location.href = 'nuevo_atencion.php'"><div class="sombraButon"></div><div id="textoOpcion">Inicio</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href = 'nuevo_cambiarclave.php'"><div class="sombraButon"></div><div id="textoOpcion">Cambiar Contraseña</div></div>
    </div>
    </td>
    </tr>
  <tr>
    <td height="21" align="center" class="letra" colspan="2"></td>
  </tr>
</table>
    <div class="contenedorUser"><div class="imgUser"></div><div class="nombreUser"><?php echo $_SESSION['nombretrestaurante'];?></div></div>
    
    </div>
    </td>
    <td width="79%">
      <div class="menu2">
       <div class="tituloTransaccion">
         <div class="textoTituloTransaccion">Cambiar Contraseña</div></div>
          <div class="separador"></div>
            </br>
            
       <form id="formulario" name="formulario" method="post" action="nuevo_cambiarclave.php">     
       <table width="92%" border="0" align="center">
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="hidden" id="transaccion" name="transaccion" value="<?php echo $transaccion;?>"/></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">&nbsp;</td>
    <td class="letra">Nueva Contraseña</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right" class="letra">Contraseña:<span class="rojo">*</span></td>
    <td><input type="password" name="clave" id="clave" class="required" minlength="6"/></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td width="3%"></td>
    <td width="4%"></td>
    <td width="28%" align="right"></td>
    <td width="37%">&nbsp;</td>
    <td width="15%"><input type="submit" value="Guardar" id="botonrestaurante"/></td>
    <td width="13%"><input type="reset" value="Cancelar" id="botonrestaurante"/></td>
  </tr>
</table>

</form>
 <div class="contenedorCerrar"><div class="imagenCerrar"></div><div id="textoCerrar" onclick="location.href='cerrar.php'">Cerrar</div></div>
      </div>
    </td>
  </tr>
</table>

   
 </div>
</body>
</html>