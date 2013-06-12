<?php
 session_start(); 
 include("../conexion.php");
 
 if (!isset($_SESSION['id_usuario'])){
  header("Location: index.php");	
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
      <td>
        <div class="contenDataNew2">
          <div class="contenPrincipalNew2">
            <div class="imgInicio"></div>
            <!-- Ingrese el contenido del Formulario  -->   
            
            </div>    
        </div>      </td>
    </tr>
</table>    
  </div>   
  <div class="pie">© 2012 Consultora Guez. All rights reserved.</div>
</div>
</body>
</html>