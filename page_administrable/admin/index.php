<?php 
    session_start(); 
	include("conexion.php");
	$db = new MySQL();
//	$mensaje = isset($_GET['msj']) ? "Acceso Denegado" : "";
	
	 if (isset($_POST['clave']) && isset($_POST['user'])) {
		 $claveInicial = md5($_POST['clave']);
		 $clave1 = crc32($claveInicial);
		 $clave2 = crypt($clave1, "xmas");
		 $clave = sha1("xmas".$clave2);
	     $id = $_POST['user'];
	     $sql = sprintf("select u.* from usuario u where u.login=%s and u.estado=1 and u.clave=%s COLLATE utf8_bin limit 1;",
	     filtroSeguridad($id,"text"),filtroSeguridad($clave,"text"));         
	     $cantidad = $db->getnumRow($sql);
	     $data = $db->arrayConsulta($sql);
	     if ($cantidad > 0) {
		     $dato = $db->arrayConsulta($sql);	  
			 $_SESSION['userID'] = $data['idusuario'];
			 $_SESSION['userName'] = $data['nombre'];
			 header("Location: admingeneral.php");
			 exit();
	     }  
	     header("Location: index.php?msj=Error");
	 }
	
	 function filtroSeguridad($valor, $tipo){
	     if (PHP_VERSION < 6) {
			$valor = get_magic_quotes_gpc() ? stripslashes($valor) : $valor;
		 }
		 $valor = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($valor) : mysql_escape_string($valor);
		 switch ($tipo) {
		     case "text":
			     $valor = ($valor != "") ? "'" . $valor . "'" : "NULL";
			 break;        
		 }
		 return $valor;
	 }
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Administrador</title>
<link rel="stylesheet" type="text/css" href="styles/style_admin.css" />
<script>

    var $$ = function(id){
        return document.getElementById(id);	 
    }

	var limpiarDatos = function(id) {
		var input = document.formulario.clave;
		
		if (id == "user") {
			if ($$("clave").value == "" ){
			    $$("clave").value = "Ingrese contraseña.";
				input.setAttribute("type", "text");
			}			
			
		} else {
			if ($$("user").value == "" )
			    $$("user").value = "Ingrese usuario.";
			    input.setAttribute("type", "password");	
		}		
		
		if ( $$(id).value == "Ingrese usuario." || $$(id).value == "Ingrese contraseña.") {
			$$(id).value = "";
		} else {
		    $$(id).focus();
		}
	}

</script>

</head>

<body>
<div class="contenedor">
<div class="contenPrincipal">
<div class="ver_header"> </div>
<div class="subContenedor">
<form id="formulario" name="formulario" method="post" action="index.php">
<table width="601" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="24">&nbsp;</td>
    <td width="17">&nbsp;</td>
    <td width="211">&nbsp;</td>
    <td width="27">&nbsp;</td>
    <td width="212">&nbsp;</td>
    <td width="110">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="text" id="user" name="user" value="Ingrese usuario." class="caja_user" onfocus="limpiarDatos(this.id)"/>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="text" id="clave" name="clave" value="Ingrese contraseña." onfocus="limpiarDatos(this.id)" class="caja_pass"/></td>
    <td><input type="submit" value="Login" class="buton"/></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  </table>
</form>
</div>
<div class="ver_footer"><div class="title">ADMINISTRACION</div></div>
</div>

</div>

</body>
</html>