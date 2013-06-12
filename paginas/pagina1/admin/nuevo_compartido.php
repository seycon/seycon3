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

       $hora = time();
	   $nombre = $_FILES['imagen']['name'];	   
	   $src = "";
	   if ($nombre != "" ) {
		   copy($_FILES['imagen']['tmp_name'],"../file/$hora$nombre");
		   $src = "file/$hora$nombre";  
	   }
	   $hora = time();
	   $nombre = $_FILES['imagen1']['name'];	   
	   $src1 = "";
	   if ($nombre != "" ) {
		   copy($_FILES['imagen1']['tmp_name'],"../file/$hora$nombre");
		   $src1 = "file/$hora$nombre";  
	   }
	   $hora = time();
	   $nombre = $_FILES['imagen2']['name'];	   
	   $src2 = "";
	   if ($nombre != "" ) {
		   copy($_FILES['imagen2']['tmp_name'],"../file/$hora$nombre");
		   $src2 = "file/$hora$nombre";  
	   }
	   $hora = time();
	   $nombre = $_FILES['imagen3']['name'];	   
	   $src3 = "";
	   if ($nombre != "" ) {
		   copy($_FILES['imagen3']['tmp_name'],"../file/$hora$nombre");
		   $src3 = "file/$hora$nombre";  
	   }
	   $hora = time();
	   $nombre = $_FILES['imagen4']['name'];	   
	   $src4 = "";
	   if ($nombre != "" ) {
		   copy($_FILES['imagen4']['tmp_name'],"../file/$hora$nombre");
		   $src4 = "file/$hora$nombre";  
	   }
	   $hora = time();
	   $nombre = $_FILES['imagen5']['name'];	   
	   $src5 = "";
	   if ($nombre != "" ) {
		   copy($_FILES['imagen5']['tmp_name'],"../file/$hora$nombre");
		   $src5 = "file/$hora$nombre";  
	   }
	   
	    if($_POST['transaccion'] == "insertar") {					   	
		   $sql = "insert into compartido(fecha,titulo,imagen,imagen1,imagen2,imagen3,imagen4,imagen5
		   ,descripcion,tipo,estado,idplantilla) values 
		   (now(),'$_POST[titulo]','$src','$src1','$src2','$src3','$src4','$src5',
		   '$_POST[descripcion]','$_POST[tipo]',1,'$_SESSION[IDplantilla]')";	
	    } else { 
		  $imagen = "";  
		   if ($src != "") {
			  $imagen = ",imagen='$src'"; 
		   }
		   if ($src1 != "") {
			  $imagen .= ",imagen1='$src1'"; 
		   }
		   if ($src2 != "") {
			  $imagen .= ",imagen2='$src2'"; 
		   }
		   if ($src3 != "") {
			  $imagen .= ",imagen3='$src3'"; 
		   }
		   if ($src4 != "") {
			  $imagen .= ",imagen4='$src4'"; 
		   }
		   if ($src5 != "") {
			  $imagen .= ",imagen5='$src5'"; 
		   }
		   
		   $sql = "update compartido set fecha=now(),titulo='$_POST[titulo]',descripcion='$_POST[descripcion]',
		   tipo='$_POST[tipo]',idplantilla='$_SESSION[IDplantilla]' $imagen where idcompartido=$_POST[idtransaccion]"; 	   	  
        }  
        $db->consulta($sql);  
        header("Location: nuevo_compartido.php?estadoT=v");
    }
 
    if (isset($_GET['nro'])) {
        $transaccion = "modificar";
        $datosG = $db->arrayConsulta("select * from compartido where idcompartido=$_GET[nro]");
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
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jquery.filestyle.js" type="text/javascript"></script>
<script type="text/javascript" src="js/nicEdit.js"></script>
<script type="text/javascript">
	bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
	
$(document).ready(function()
{	
	
	$("input[type=file]").filestyle({ 
     image: "img/file.png",
     imageheight : 21,
     imagewidth : 80,
     width : 130
   });
   
   
  });
	
</script>
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
    <div class="tituloUsuario">Bienvenido<span style="color:#666"> <?php echo $_SESSION['userName'];?></span></div>
   
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
<div class="contem2">
   <div class="titleFrom">
      <div class="imagenFrom"><img  src="img/icon_dashboard_small.gif" width="26" height="26"/> </div>
      <div class="nombreFrom">Usuario</div>
   </div>
   <div class="titleCaso2" style="top:-11px;">

      
      <div class="optionSubMenu" style="
       background:  -moz-linear-gradient(top, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0) 100%);
       background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(0,0,0,0.65)), color-stop(100%,rgba(0,0,0,0)));
      "><a href="listar_menu.php" class="link"> Listar Menú</a></div>
      <div class="optionSubMenu2" style=""><a href="nuevo_menu.php" class="link"> Nuevo Menú</a></div>
      <div class="optionSubMenu3" style="left:270px;"><a href="nuevo_estilo.php" class="link"> Color de Pagina</a></div>
      <div class="optionSubMenu4" style="left:405px;
      background: -moz-linear-gradient(top, rgba(76,76,76,1) 0%, rgba(89,89,89,1) 12%, 
      rgba(102,102,102,1) 25%, rgba(71,71,71,1) 39%, rgba(44,44,44,1) 50%, rgba(0,0,0,1) 51%, rgba(17,17,17,1) 60%, 
      rgba(43,43,43,1) 76%, rgba(28,28,28,1) 91%, rgba(19,19,19,1) 100%);
      background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(76,76,76,1)), 
      color-stop(12%,rgba(89,89,89,1)), color-stop(25%,rgba(102,102,102,1)), color-stop(39%,rgba(71,71,71,1)), 
      color-stop(50%,rgba(44,44,44,1)), color-stop(51%,rgba(0,0,0,1)), color-stop(60%,rgba(17,17,17,1)), 
      color-stop(76%,rgba(43,43,43,1)), color-stop(91%,rgba(28,28,28,1)), color-stop(100%,rgba(19,19,19,1)));
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#4c4c4c', endColorstr='#131313',GradientType=0 );
      "><a href="nuevo_compartido.php" class="link"> Compartido</a></div>
      <div class="optionSubMenu4" style="left:540px;"><a href="listar_compartido.php" class="link">Listar Compartido</a></div>
      
   </div>
   <form id="formulario" name="formulario" method="post" action="nuevo_compartido.php" enctype="multipart/form-data">
   <div class="contemDataFrom2" style="top:5px;">
      
     
      
      <table width="90%" border="0" align="center">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="5">
    <?php 
	if (isset($_GET['estadoT']) && $_GET['estadoT'] == "v") {
	    $clase = "block";	
	} else {
	    $clase = "none";	
	}
	
	?>
    <div class="valid_box2" style="display:<?php echo $clase;?>">
        Sus Datos Fueron Guardados Correctamente.
     </div>
     
         
     </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="3%">&nbsp;</td>
    <td width="15%">&nbsp;</td>
    <td colspan="2"><input type="hidden" name="transaccion" id="transaccion" value="<?php echo $transaccion;?>"/></td>
    <td width="35%">&nbsp;</td>
    <td width="3%"><input type="hidden" name="idtransaccion" id="idtransaccion" value="<?php echo $_GET['nro'];?>" /></td>
    <td width="1%">&nbsp;</td>
    <td width="1%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Titulo:</td>
    <td colspan="3"><input type="text" name="titulo" id="titulo" class="field" value="<?php echo $datosG['titulo'];?>"/></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Imagen Principal:</td>
    <td><input type="file" name="imagen" id="imagen" class="file_1"/></td>
    <td align="right">Imagen 1:</td>
    <td><input type="file" name="imagen1" id="imagen1" class="file_1"/></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Imagen 2:</td>
    <td width="32%"><input type="file" name="imagen2" id="imagen2" class="file_1"/></td>
    <td width="10%" align="right">Imagen 3:</td>
    <td><input type="file" name="imagen3" id="imagen3" class="file_1"/></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Imagen 4:</td>
    <td><input type="file" name="imagen4" id="imagen4" class="file_1"/></td>
    <td align="right">Imagen 5:</td>
    <td><input type="file" name="imagen5" id="imagen5" class="file_1"/></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right" valign="top">Contenido:</td>
    <td colspan="3"><textarea id="descripcion" name="descripcion" style="width:500px;height:200px;"><?php echo $datosG['descripcion'];?></textarea>
      </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">Tipo:</td>
    <td colspan="3"><select id="tipo" name="tipo" class="field">
    <?php

	 $vector = array("Privado","Publico");
 	 $ids = array("privado","publico");
	 $i = 0;
	 foreach($vector as $valor) {
		$atributo = "";
		if ($ids[$i] == $datosG['tipo']) { 
		    $atributo = "selected='selected'"; 
		}
		echo "<option value='$ids[$i]' $atributo>$valor</option>";
		$i++;
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
    <td colspan="4">  
    
    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
  
   <div class="boton1M"><input type="submit" class="boton" value="Guardar"/></div>      
   <div class="boton2M"><input type="reset" class="boton" value="Cancelar"/></div>
   </div>
   </form>
   
</div>
<br />
<br />
<div class="pie">
  <div class="acoplePie">
    <div class="autor">Copyright © Consultora Guez – Diseñado y Desarrollado</div>
  </div>  
</div>

</body>
</html>