<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
    session_start(); 

	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
	{
	  if (PHP_VERSION < 6) {
		$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
	  }
	
	  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
	
	  switch ($theType) {
		case "text":
		  $theValue = ($theValue != "") ? "'" . $theValue . "'" : "''";
		  break;    
		case "int":
		  $theValue = ($theValue != "") ? intval($theValue) : "NULL";
		  break;   
	  }
	  return $theValue;
	}


	if (($_POST['nombre'] != "") && ($_POST['password'] != "") && ($_POST['empresa'] != "")) {
		session_start();
		$loginUsername = $_POST['nombre'];
		$claveUser = md5($_POST['password']);
		$claveUser2 = crc32($claveUser);
		$claveUser3 = crypt($claveUser2, "xmas");
		$password = sha1("xmas".$claveUser3);  
		$_SESSION['BDname'] = $_POST['empresa'];
		$database_bdlocal = $_SESSION['BDname'];
		require_once('bdlocal.php');				
		mysql_select_db($database_bdlocal, $bdlocal);  
		$LoginRS__query=sprintf("SELECT u.idusuario, 
		  concat(t.nombre,' ',t.apellido) as nombreusuario, 
		  login, password, t.idsucursal FROM usuario u,trabajador t WHERE  
		  t.idtrabajador = u.idtrabajador and u.estado = 1 
		  and login=%s AND password=%s COLLATE utf8_bin limit 1",GetSQLValueString($loginUsername, "text")
		  , GetSQLValueString($password, "text"));      
		$sql = "select nombrecomercial,nit from empresa where estado=1";
	  
		$empresa = mysql_query($sql)or die(mysql_error());
		$empresa = mysql_fetch_array($empresa);   
		$LoginRS = mysql_query($LoginRS__query);
		$loginFoundUser = mysql_num_rows($LoginRS);
	   
	  if ($loginFoundUser > 0) {
		$a = mysql_fetch_array($LoginRS);
		
		if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
		$_SESSION['MM_Username'] = $loginUsername;
		$_SESSION['nombre_usuario'] = $a['nombreusuario'];
		$_SESSION['idsucursal'] = $a['idsucursal'];	
		$_SESSION['id_usuario'] = $a['idusuario'];	
		$_SESSION['nombreEmpresa'] = $empresa['nombrecomercial'];	      
		$_SESSION['nit'] = $empresa['nit'];
		$_SESSION['softLogeoadmin'] = "si";
		
		//Arma la Estructura de Privilegios de Usuario
		$estructura = array('Administracion'=>'', 'Inventario'=>'', 'Recursos'=>'', 'Activo'=>'', 'Ventas'=>''
		, 'Contabilidad'=>'', 'Agenda'=>'');
	
		$sql = "SELECT a.* FROM usuario u,detalleaccion d,accion a where 
		d.idaccion=a.idaccion and d.idgrupo=u.idgrupousuario and idusuario=$a[idusuario] ORDER BY a.idaccion;";
		$consulta = mysql_query($sql);
		$modulo = "";
		while ($data = mysql_fetch_array($consulta)) {
			
			if ($data['modulo'] != $modulo) {
				if ($modulo != "") {
					$menu['Submenu'] = $submenu;	
					array_push($principal,$menu);  
					$estructura["$modulo"] = $principal;  
				}
				$modulo = $data['modulo'];
				$seccion = "";
				$principal = array();
			}		
			
			if ($data['seccion'] != $seccion) {
				if ($seccion != "") {
					$menu['Submenu'] = $submenu;	
					array_push($principal,$menu);	
				}
				$submenu = array();
				$menu = array('Menu'=>"$data[seccion]",'Submenu'=>'','Modificar'=>'No','Eliminar'=>'No');
				$seccion = $data['seccion'];
			 }
			 
			 if ($data['accion'] == 'nuevo' || $data['accion'] == 'listar' || $data['accion'] == 'reporte') {
				 $option = array('Texto'=>"$data[texto]",'Enlace'=>"$data[url]"); 
				 array_push($submenu,$option);
			 }
			 
			 if ($data['accion'] == 'modificar') {
				 $menu['Modificar'] = 'Si'; 
			 }
		
			 if ($data['accion'] == 'eliminar') {
				 $menu['Eliminar'] = 'Si'; 
			 }
			
		}
		  $menu['Submenu'] = $submenu;	
		  array_push($principal, $menu);
		  $estructura["$modulo"] = $principal;
		  $_SESSION['estructura'] = $estructura;   
		  header("Location: index.php");
	  } else {
		  header("Location: login.php");
	  }
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sistema Empresarial y Contable - Seycon 3.0</title>
    <link rel="stylesheet" href="css/config.css" type="text/css"/>
</head>

<body>

<script>
  var dim = parseFloat((screen.height / 2) - 290).toFixed(0) ;
  document.write("<div class='contenedorPrincipal' style='top:"+ dim +"px;'>")
</script>

  <div class="contenedorAlineador">
  <div class="derechosLogin">Sistema Empresarial y Contable "Seycon 3.0" - Copyright © Consultora Guez S.R.L.</div>
  <div class="imgSeycon"></div>
    
    <div class="cuadroInterior">
      <div class="cuadroLogo"></div>    
        <div class="vIngresos">
        <form id="form" name="form" method="POST" action="login.php">
           <table width="297" border="0" cellpadding="0" cellspacing="0" >
           <tr>
                <th width="89" class="letraLogin">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Usuario</th>
                <td width="208"><input type="text"  class="login-inp" name="nombre" id="nombre"/></td>
           </tr>
           <tr>
           <th height="57" class="letraLogin">Contraseña</th>
           <td>
            <input type="password" name="password" id="password" value=""  onfocus="this.value=''" class="login-inp" />           
            </td>
           </tr>
           <tr>
             <th height="27" class="letraLogin" >&nbsp;&nbsp;&nbsp;&nbsp;Empresa</th>
             <td>
               <select id="empresa" name="empresa" class="login-inp" style="width:195px;">
                  <option value="bdseguridad">Proseg</option>
                  <!-- <option value="jorge_bdproseg">Proseg</option> -->
               </select>          
             
             </td>
           </tr>
           <tr>
                <th></th>
                <td valign="top"></td>
           </tr>
           <tr>
                <th height="57"></th>
                <td><input type="submit" class="submit-login"  value="Ingresar"/></td>
           </tr>
           </table>
        </form>
        </div>     
      </div>
    </div>  
</div>

</body>
</html>