<?php
    include("conexion.php");
    $db = new MySQL();
    session_start();
    
	if (!isset($_SESSION['userID'])) {
       header("Location: index.php");	
	}
	
    function filtro($cadena)
    {
        return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
    }
 
    if (isset($_POST['transaccion'])) {  
	    $claveUser = md5($_POST['clave']);
        $claveUser2 = crc32($claveUser);
        $claveUser3 = crypt($claveUser2, "xmas");
        $claveFinal = sha1("xmas".$claveUser3);       	 
	    if($_POST['transaccion'] == "insertar") {
	        $sql = "insert into usuario(nombre,login,clave,estado,idplantilla) values('".filtro($_POST['nombre'])."',
			'".filtro($_POST['login'])."','".filtro($claveFinal)."',1,$_SESSION[IDplantilla]);"; 	        
	    } else {              
            $sql = "update usuario set nombre='".filtro($_POST['nombre'])."',login='".filtro($_POST['login'])
			."',clave='".filtro($claveFinal)."' where idusuario=$_POST[idtransaccion]";			   	  
        }  
        $db->consulta($sql);  
        header("Location: nuevo_usuario.php?estadoT=v");
    }
 
    if (isset($_GET['nro'])) {
        $transaccion = "modificar";
        $datosG = $db->arrayConsulta("select * from usuario where idusuario=$_GET[nro]");
    } else {
        $transaccion = "insertar";
    }
 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Panel Administrativo</title>
<link rel="stylesheet" type="text/css" href="styles/style.css" />
</head>

<body>
<div class="head"> 
<div class="acopleCabecera">
  <div class="logeo">
  <a href="cerrar.php">
    <div class="imagenLogeo"></div>
    <div class="textoLogeo">Salir</div>
  </a>
  </div>  

   <div class="tituloPrincipal">Panel Administrativo</div>
   <div class="tituloSecundario">Gestión y Configuración de Paginas Web.    </div>
    <div class="tituloUsuario">Bienvenido<span style="color:#666"> <?php echo $_SESSION['userName'];?> </span></div>
   
   <div class="panelOpciones">
     <div class="opcionesPanel">            
            <div class="opcion1"><a href="admingeneral.php">
            <div class="icono"><img src="img/icon_dashboard.png" width="48" height="48"/></div>
            <div class="titulo">Inicio</div> </a> </div>
            <div class="opcion2"><a href="nuevo_usuario.php">
            <div class="icono"><img src="img/icon_users.png" width="48" height="48"/></div>
            <div class="titulo">Usuario</div></a>  </div>            
     </div>
   </div>
   </div>
   
   
 </div>
<div class="contem">
   <div class="titleFrom">
      <div class="imagenFrom"><img  src="img/icon_dashboard_small.gif" width="26" height="26"/> </div>
      <div class="nombreFrom">Usuario</div>
   </div>
   <div class="titleCaso">
      
      <div class="optionSubMenu" onclick="location.href='nuevo_usuario.php'">Nuevo Usuario</div>
      <div class="optionSubMenu2" onclick="location.href='listar_usuario.php'">Listar Usuario</div>
      
   </div>
   <form id="formulario" name="formulario" method="post" action="nuevo_usuario.php">
   <div class="contemDataFrom">
      
     
      
      <table width="90%" border="0" align="center">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">
    <?php 
	if (isset($_GET['estadoT']) && $_GET['estadoT'] == "v") {
	    $clase = "block";	
	} else {
	    $clase = "none";	
	}
	
	?>
    <div class="valid_box" style="display:<?php echo $clase;?>">
        Sus Datos Fueron Guardados Correctamente.
     </div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="18%">&nbsp;</td>
    <td width="18%">&nbsp;</td>
    <td width="45%"><input type="hidden" name="transaccion" id="transaccion" value="<?php echo $transaccion;?>"/></td>
    <td width="6%"><input type="hidden" name="idtransaccion" id="idtransaccion" value="<?php echo $_GET['nro'];?>" /></td>
    <td width="6%">&nbsp;</td>
    <td width="7%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Nombre Usuario:</td>
    <td><input type="text" name="nombre" id="nombre" class="field" value="<?php echo $datosG['nombre']?>"/></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Login:</td>
    <td><input type="text" name="login" id="login" class="field" value="<?php echo $datosG['login']?>"/></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Password:</td>
    <td><input type="password" name="clave" id="clave" class="field"/></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

   <div class="boton1"><input type="submit" class="boton" value="Guardar"/></div>
   <div class="boton2"><input type="reset" class="boton" value="Cancelar"/></div>
   </div>
   </form>
   
</div>


<div class="pie">
    <div class="acoplePie">
    <div class="autor">Copyright © Consultora Guez – Diseñado y Desarrollado</div>
    </div>
</div>

</body>
</html>