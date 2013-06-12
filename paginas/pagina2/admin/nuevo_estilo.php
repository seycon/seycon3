<?php
    include("conexion.php");
    $db = new MySQL();
    session_start();
    
	if (!isset($_SESSION['userID'])) {
       header("Location: index.php");	
	}
	
    if (isset($_POST['transaccion'])) {  	     
	    $sql = "update pagina set estilo='$_POST[estilo]' where idplantilla=$_SESSION[IDplantilla]";	    
        $db->consulta($sql);  
        header("Location: nuevo_estilo.php?estadoT=v");
    } 

	$transaccion = "modificar";
	$datosG = $db->arrayConsulta("select * from pagina where idplantilla=$_SESSION[IDplantilla] and numero=1");
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
      
      <div class="optionSubMenu" style="
       background: -moz-linear-gradient(top, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0) 100%);
       background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(0,0,0,0.65)), color-stop(100%,rgba(0,0,0,0)));
      "><a href="listar_menu.php" class="link"> Listar Menú</a></div>
      <div class="optionSubMenu2"><a href="nuevo_menu.php" class="link"> Nuevo Menú</a></div>
      <div class="optionSubMenu3" style="background: -moz-linear-gradient(top, rgba(76,76,76,1) 0%, rgba(89,89,89,1) 12%, 
      rgba(102,102,102,1) 25%, rgba(71,71,71,1) 39%, rgba(44,44,44,1) 50%, rgba(0,0,0,1) 51%, rgba(17,17,17,1) 60%, 
      rgba(43,43,43,1) 76%, rgba(28,28,28,1) 91%, rgba(19,19,19,1) 100%);
      background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(76,76,76,1)), 
      color-stop(12%,rgba(89,89,89,1)), color-stop(25%,rgba(102,102,102,1)), color-stop(39%,rgba(71,71,71,1)), 
      color-stop(50%,rgba(44,44,44,1)), color-stop(51%,rgba(0,0,0,1)), color-stop(60%,rgba(17,17,17,1)), 
      color-stop(76%,rgba(43,43,43,1)), color-stop(91%,rgba(28,28,28,1)), color-stop(100%,rgba(19,19,19,1)));
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#4c4c4c', endColorstr='#131313',GradientType=0 );
      left:270px;">
      <a href="nuevo_estilo.php" class="link"> Color de pagina</a></div>
      <div class="optionSubMenu4" style="left:405px;"><a href="nuevo_compartido.php" class="link"> Compartido</a></div>
      <div class="optionSubMenu4" style="left:540px;"><a href="listar_compartido.php" class="link">Listar Compartido</a></div>
      
   </div>
   <form id="formulario" name="formulario" method="post" action="nuevo_estilo.php">
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
    <td align="right">Color de Pagina:</td>
    <td>
    <select id="estilo" name="estilo" class="field">
    <?php
	if ($_SESSION['IDplantilla'] == 2) {
	 $vector = array("Negro","Blanco","Negro y Blanco");
 	 $ids = array("sPage1_1.css","sPage1_2.css","sPage1_3.css");
	 $i = 0;
	 foreach($vector as $valor) {
		$atributo = "";
		if ($ids[$i] == $datosG['estilo']) { 
		    $atributo = "selected='selected'"; 
		}
		echo "<option value='$ids[$i]' $atributo>$valor</option>";
		$i++;
	  }
	}	
	?>    
    </select>   
    
    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right"></td>
    <td></td>
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