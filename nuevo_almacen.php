<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
	include_once('conexion.php');
	$db = new MySQL();
	
	if (!isset($_SESSION['softLogeoadmin'])) {
	    header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	$fileAcceso = $db->privilegiosFile($estructura['Inventario'],'Almacén','nuevo_almacen.php','listar_almacen.php');
	if ($fileAcceso['Acceso'] == "No") {
	  header("Location: cerrar.php");	
	}
	
	function filtro($cadena)
	{
	  return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
	}
	
	if (isset($_POST['transaccion'])) {	
		if ($_POST['transaccion'] == "insertar") {
		  $sql = "INSERT INTO almacen
		(idalmacen,nombre,ubicacion,sucursal,observaciones,estado,idusuario) VALUES (NULL,'"
		.filtro($_POST['nombre'])."','".filtro($_POST['ubicacion'])."','".
		filtro($_POST['sucursal'])."','".filtro($_POST['observaciones'])
		."','1',$_SESSION[id_usuario]);";
		  mysql_query($sql);
		}
		
		if ($_POST['transaccion'] == "modificar") {
			$sql = "UPDATE almacen SET nombre='".filtro($_POST['nombre'])."', ubicacion='".filtro($_POST['ubicacion'])
			."', sucursal='".filtro($_POST['sucursal'])."', observaciones='"
			.filtro($_POST['observaciones'])."',idusuario=$_SESSION[id_usuario]
			  WHERE idalmacen= '".$_POST['idalmacen']."';";
			mysql_query($sql);
		}
		header("Location: nuevo_almacen.php#t1");
	}
	
	$transaccion = "insertar";
	if (isset($_GET['sw'])) {
	  $transaccion = "modificar";	
	  $sql = "SELECT * FROM almacen WHERE idalmacen= ".$_GET['idalmacen'];
	  $datoAlmacen = $db->arrayConsulta($sql);  
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templateinventario.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<title>Sistema Empresarial y Contable – Seycon 3.0</title>
<script type="text/javascript" src="js/submenus.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Sistema Empresarial y Contable – Seycon 3.0</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<link rel="stylesheet" href="css/form.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script async="async" src="js/jquery.validate.js"></script>
<script>
  $(document).ready(function()
  {  
    $("#formValidado").validate({});
  });

   function checkclick(id) { 
    if (document.getElementById(id).checked) {
      document.getElementById(id).value = 1;
	} else {
	  document.getElementById(id).value = 0;
	}
   }
  
   //teclas de atajo
   document.onkeydown = function(e) {
	 tecla = (window.event) ? event.keyCode : e.which;
	 
	 if (tecla == 115) { //F4
	   if ($$("cancelar") != null)
		 location.href='listar_almacen.php#t1';
	 }	   
	  
	 if(tecla == 113) { //F2
	   $$("enviar").click();
			
	  }
   }
   
   var $$ = function(id){
	  return document.getElementById(id); 
   }
  
</script>

<style type="text/css">
	.bordeContenido {  
	 border: 1px solid #CCC;	
	}
</style>
<!-- InstanceEndEditable -->
<script>
 $(document).ready(function(){ 
	$("ul.submenu").parent().append("<span></span>"); 	
	$("ul.menu li span").click(function() { 
		$(this).parent().find("ul.submenu").slideDown('fast').show(); 
		$(this).parent().hover(function() {
		}, function(){	
			$(this).parent().find("ul.submenu").slideUp('slow'); 
		});
 
		}).hover(function() { 
			$(this).addClass("subhover"); 
		}, function(){	
			$(this).removeClass("subhover"); 
	});
	
	$("ul.menuH li span").click(function() { 		
		$(this).parent().find("ul.submenu").slideDown('fast').show();  
		$(this).parent().hover(function() {
		}, function(){	
			$(this).parent().find("ul.submenu").slideUp('slow'); 
		});
 
		}).hover(function() { 
			$(this).addClass("subhover"); 
		}, function(){
			$(this).removeClass("subhover"); 
	});
 
});
</script>
<link href="SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" /> 
</head> 
<body >
<div class="franjaCabecera">
<div class="franjaInicial"></div>
<div class="alineadorFrontalSeycon">
<?php
	  function setCabeceraTemplate($titulo) {
		  $cadenaNit = $_SESSION['nit'];
		  if (strlen($cadenaNit) > 15)	{			  
			  $cadenaNit = substr($cadenaNit,0,15);
		  }
		  $cadena = $_SESSION['nombreEmpresa'];
		  if (strlen($cadena) > 35) {				  
			  $cadena = substr($cadena,0,35);
		  }		
		  echo "
			  <div class='headerPrincipal'>
			   <div class='logoEmpresa'></div>			  
				  <div class='tituloEmpresa'>$titulo</div>
				  <div class='nitEmpresa'> 
				   $cadena-$cadenaNit
				  </div>
			  </div>
		  ";
	  }
	  
	  function setMenuTemplate($tituloP, $modulo) {
		 if ($modulo != "Administracion") 
		 echo "<a href='#'>$tituloP</a>"; 
		 $estructura = $_SESSION['estructura'];
		 $menus = $estructura[$modulo];
  	     echo  "<ul class='submenu'>"; 
		 if ($menus != "") {
		   for ($i = 0; $i < count($menus); $i++) {
			   $titulo = $menus[$i]['Menu']; 
			   echo "<li><a href='redireccion.php?mod=$modulo&opt=$titulo'>".$titulo."</a></li>";
		   }		   
		 } 
		 if ($modulo == "Administracion")
		     echo "<li><a href='cerrar.php'>Salir</a></li>";
		 echo "</ul>";
	  }
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td > 
    <?php setCabeceraTemplate("Sistema Empresarial y Contable");?>
    </td>
  </tr>
  <tr>
    <td >
     <div class="menu2"></div>
    </td>
  </tr>
</table>
  <div class="contenedorMenuFrontal">
   <ul class="menu"> 
      <li><?php setMenuTemplate("Inventario", "Inventario");?></li> 
      <li><?php setMenuTemplate("Recursos", "Recursos");?></li>
      <li><?php setMenuTemplate("Activos", "Activo");?></li> 
      <li><?php setMenuTemplate("Ventas", "Ventas");?></li> 
      <li><?php setMenuTemplate("Contabilidad", "Contabilidad");?></li> 
      <li><?php setMenuTemplate("Agenda", "Agenda");?></li>  
    </ul> 
    <div class="usuarioSistema">
      <div class="borde1Usuario"></div>
      <div class="borde2Usuario">
         <div class="sessionHerramienta">
         <ul class="menuH"> 
           <li>
		   <div class="imgHerramienta"></div>
		   <?php setMenuTemplate("Administracion", "Administracion");?></li>               
         </ul>
         </div>
         <div class="nombreUsuario">
		  <?php
          $cadena = $_SESSION['nombre_usuario'];
          $cadena = (strlen($cadena) > 15) ? $cadena = substr($cadena,0,15) : $cadena;
          echo ucfirst($cadena);				
          ?></div>
      </div>
    </div> 
         
    </div>       
   </div>  
</div>
<div class="container">
  <!-- InstanceBeginEditable name="Regioneditable" -->
<div class="cabeceraFormulario">

<div class="menuTituloFormulario"> Inventario > Almacén </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Inventario'];
   $privilegios = $db->getOpciones($menus, "Almacén"); 
   $option = "";
   for ($i = 0; $i < count($privilegios); $i++) {	
	   $link = "location.href='".$privilegios[$i]["Enlace"]."'";
	   $option = "<div class='privilegioMenu' onclick=$link>".$privilegios[$i]['Texto']."</div>". $option;
   } 
   echo $option;
 ?>
</div>
</div>
<br />

<table style="width:75%;top:38px;margin: 0 auto;position:relative;" border="0">
 <tr>
 <td>
<div class="contenedorPrincipal">
<form id='formValidado' name='formValidado' method='post' action='nuevo_almacen.php' enctype='multipart/form-data' style="width:100%;">
<div class="contemHeaderTop">
    <table cellpadding='0' cellspacing='0' width='100%'>
      <tr class='cabeceraInicialListar'> 
        <td height="92" colspan='2' >&nbsp;&nbsp;
        <input name='enviar' type='submit' class='botonNegro' id='enviar' value='Guardar [F2]' />
        <?php 
        if ($fileAcceso['File'] == "Si"){
         echo '&nbsp;&nbsp; <input name="cancelar" type="button" class="botonNegro" 
         id="cancelar" value="Cancelar [F4]" onClick="location.href=&#039listar_almacen.php#t1&#039"/>';	
        }
        ?>
     
     </td>
    <td><input type="hidden"  id="transaccion" name="transaccion" value="<?php echo $transaccion;?>" />
    <input type="hidden" id="idalmacen" name="idalmacen" value="<?php echo $datoAlmacen['idalmacen'];?>" /></td>
    <td colspan="3" align='right'><table width="356" border="0">
      <tr>
        <td width="204" align="right"><strong>Transacción N&deg; </strong></td>
        <td width="142">
        <?php 
        if (isset($_GET['idalmacen'])) {
            echo $_GET['idalmacen'];
        } else {
            echo $db->getNextID("idalmacen","almacen");
        }
        ?>
    </td>
      </tr>
      <tr>
        <td colspan="2" align="center">Los campos con <span class='rojo'>(*) </span>son requeridos.</td>
      
      </tr>
    </table>
    </td> 
      </tr>
      <tr><td colspan="6"></td> </tr>
    </table>
</div>

<table border='0' width='100%' align='center' cellpadding='4' cellspacing='3' >
<tr>
<td colspan='6' align='center' ></td>
</tr>
<tr>
  <td width="693" colspan='4'></td>
  <td width="1" colspan="2" rowspan='2' align='center'>&nbsp;</td>
</tr>
<tr>
  <td colspan='4'></td>
</tr>
<tr>
  <td colspan='6' ></td>
</tr>
<tr>
<td colspan='6' >

<table width="90%" border="0" align="center" class="bordeContenido">
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td width="11%">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="22%" align="right">Nombre<span class='rojo'>*</span>:</td>
      <td width="23%"><input type='text' id="nombre" name="nombre"  class="required" size="30" 
value="<?php echo $datoAlmacen['nombre'];?>" /></td>
      <td width="7%">&nbsp;</td>
      <td align="right">Sucursal<span class='rojo'></span>:</td>
      <td width="34%"><select name="sucursal" id="sucursal" style="width:180px;">
        <option value="0" selected="selected">-- Seleccione --</option>
        <?php
   $sql = "select idsucursal,left(nombrecomercial,25)as 'nombrecomercial' from sucursal where estado=1;";
   $db->imprimirCombo($sql,$datoAlmacen['sucursal']);
   ?>
      </select></td>
      <td width="3%">&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Ubicación<span class='rojo'>*</span>:</td>
      <td><input type='text' id="ubicacion" name="ubicacion"  class="required" size="30" value="<?php echo $datoAlmacen['ubicacion'];?>" /></td>
      <td align="right"></td>
      <td align="right">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right" valign="top">Observaciones:</td>
      <td colspan="4"><textarea name="observaciones" id="observaciones" cols="60" rows="5"><?php echo $datoAlmacen['observaciones'];?></textarea></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</td>
</tr>
</table>
</form>
</div>
</td></tr></table>
<!-- InstanceEndEditable -->  
  <!-- end .footer -->
</div>
 <div class="footerAdm">
  <div class="logo1"><div class="img_logo1"></div></div>
  <div class="logo2"><div class="img_logo2"></div></div>
  <div class="logo3"><div class="img_logo3"></div></div>
  <div class="textoPie1">Seycon 3.0 - Diseñado y Desarrollado por:  Jorge G. Eguez Soliz </div>
  <div class="textoPie2">Copyright &copy; Consultora Guez S.R.L</div>
 </div>
</body>
<!-- InstanceEnd --></html>