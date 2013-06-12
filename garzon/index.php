<?php 
    session_start(); 
	//$_SESSION['BDname'] = "bdkiwis";
	//$_SESSION['BDname'] = "jorge_bdinsers";
	$_SESSION['BDname'] = "jorge_bdinsersdiscoteca";
	include("../conexion.php");
	$db = new MySQL();
	$mensaje = isset($_GET['msj']) ? "Acceso Denegado" : "";
	
	 if (isset($_POST['clave']) && isset($_POST['usuario'])) {
		 $claveInicial = md5($_POST['clave']);
		 $clave1 = crc32($claveInicial);
		 $clave2 = crypt($clave1, "xmas");
		 $clave = sha1("xmas".$clave2);
	     $id = $_POST['usuario'];
	     $sql = sprintf("select u.idusuario,u.idtrabajador,u.tipo,left(s.nombrecomercial,18)as 'sucursal',s.idsucursal 
 	     from usuariorestaurante u,sucursal s where s.idsucursal=u.idsucursal and u.login=%s and u.clave=%s and u.estado=1;",
	     filtroSeguridad($id,"text"),filtroSeguridad($clave,"text"));
	     $cantidad = $db->getnumRow($sql);
	     $data = $db->arrayConsulta($sql);
	     if ($cantidad > 0) {
		     if ($data['tipo'] == "fijo"){
		         $sql = "select left(concat(nombre,' ',apellido) ,15)as 'nombre' from trabajador"
				     ." where idtrabajador='$data[idtrabajador]'";	
		     }else{
		         $sql = "select left(concat(nombre,' ',apellido),15)as 'nombre' from personalapoyo "
				     ." where idpersonalapoyo='$data[idtrabajador]';"; 	
		     }
			 $dato = $db->arrayConsulta($sql);	  
			 $_SESSION['nombretrestaurante'] = $dato['nombre'];
			 $_SESSION['idusuariorestaurante'] = $data['idusuario'];
			 $_SESSION['sucursalrestaaurante'] = $data['sucursal'];
			 $_SESSION['IDsucursalrestaaurante'] = $data['idsucursal'];
			 header("Location: nuevo_atencion.php");
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
<title>Inicio</title>
<link rel="stylesheet" href="restaurante.css" type="text/css"/>
<script src="Nrestaurante.js"></script>
</head>

<body class="fondoinicio">
<div class="fondoinicio">


 <div id="modal" class="modalInicio"></div>
 <div id="modalInterior" class="modalInicioInterior">
 <div class="headerInteriorPrincipal"><div class="tituloVentanaclave">Ingreso Restaurante</div></div>
  
  <form id="formulario" name="formulario" method="post" action="index.php">
  
  <div class="posicionCloseSub" onclick="closeVentanaClave();"></div>
  <table width="100%" border="0">
  <tr>
    <td width="9%">&nbsp;</td>
    <td width="34%">&nbsp;</td>
    <td width="41%">&nbsp;</td>
    <td width="16%">&nbsp;</td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td align="right" class="textoClave">Usuario:</td>
    <td><input type="text" name="usuario" id="usuario"  /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right" class="textoClave">Contraseña:</td>
    <td><input type="password" name="clave" id="clave"  /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input type="hidden" id="idtrabajador" name="idtrabajador"/></td>
    <td>&nbsp;</td>
    <td><div id="errorClave" class="errorClave"><?php echo $mensaje;?></div></td>
    <td>&nbsp;</td>
  </tr> 
</table>
  <div class="posbotonInicio"><input type="submit"  value="Ingresar" id="botonrestaurante" /></div>
 </div>
 </form>
</div>
</body>
</html>