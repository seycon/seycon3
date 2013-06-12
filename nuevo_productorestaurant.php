<?php include_once('bdlocal.php');  

tieneacceso('productorestaurant');

if($_POST['producto']!=''){


$destino='';
$ahora=time();
$archivo = $_FILES['foto']['name'];
$archivo1 = $_FILES['imagen']['name'];
if ($archivo != '') {
$destino =  "files/$ahora".$archivo;
copy($_FILES['foto']['tmp_name'],$destino);
}
if ($archivo1 != '') {
$destino =  "files/$ahora".$archivo1;
copy($_FILES['imagen']['tmp_name'],$destino);
}



mysql_query("INSERT INTO productorestaurant
(idrestaurant,producto,precioventa,preciocosto,precioventafinal,descripcion,preparacion,tiempodeentrega,observaciones,estado) VALUES (NULL,'".$_POST['producto']."','".$_POST['precioventa']."','".$_POST['preciocosto']."','".$_POST['precioventafinal']."','".$_POST['descripcion']."','".$_POST['preparacion']."','".$_POST['tiempodeentrega']."','".$_POST['observaciones']."','".$_POST['estado']."');");
header("Location: listar_productorestaurant.php");}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templateinventario.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<title>Sistema Empresarial y Contable – Seycon 3.0</title>
<script type="text/javascript" src="js/submenus.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Sistema Empresarial y Contable – Seycon 2011</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->



<!-- TinyMCE -- aumentar un simbolo de mayor para activar el editor de texto avanzado TinyMCE
<script language='javascript' type='text/javascript' src='jscripts/tiny_mce/tiny_mce.js'></script>
<script language='javascript' type='text/javascript' src='conftiny.js'></script>
<!-- /TinyMCE -->
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link rel="stylesheet" href="css/estilos.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/jquery.ui.core.js"></script>
<script src="js/ui/jquery.ui.widget.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script src="js/jquery.validate.js"></script>

<script>	$(function() {	$( '#tabs' ).tabs();	});  </script>

<script>
$(document).ready(function()
{
$("#tiempodeentrega").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'yy/mm/dd'
});

$("#formValidado").validate({});
});
</script>

<script>
  function checkclick(id){ if (document.getElementById(id).checked) document.getElementById(id).value=1; else document.getElementById(id).value=0;}
</script>


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


<form id='formValidado' name='formValidado' method='post' action='nuevo_productorestaurant.php' enctype='multipart/form-data'>
<table width='70%' border='0' align='center' cellpadding='4' cellspacing='3' class="contenedorPrincipal">
<tr>
<td colspan='5' align='center' >
<div class="tituloFondo">
      <div class="tituloSobreponeFondo">
        PRODUCTO RESTAURANT
      </div>
    </div>
</td>
</tr>
<tr>
<td colspan='2' ><strong class='titulostablas'>Nuevo Producto Restaurant:</strong></td>
<td></td>
<td width="77" align='right'><strong>N&deg;:</strong></td>   
<td>&nbsp;</td>
</tr>
<tr>
<td colspan='5'> <hr /></td>
</tr>
<tr>
<td colspan='4'>
<input name='enviar' type='submit' class='botongeneral' id='enviar' value='Guardar' />
<input name='cancelar' type='button' class='botongeneral' id='cancelar' value='Cancelar' onclick="location.href='listar_productorestaurant.php'"/>
<input name='imprimir' type='button' class='botongeneral' id='imprimir' value='Imprimir' onclick="location.href='imprimir_productorestaurant.php'"/>
</td>
<td width='72' align='center'></td>
</tr>
<tr>
  <td align='right' valign='top'>&nbsp;</td>
  <td valign='top'>&nbsp;</td>
  <td  align='right' valign='top'>&nbsp;</td>
  <td align="right" valign='top'>Estado:</td>
  <td align='left'><input type='checkbox' onclick='checkclick(this.id)'  value='1'  checked='checked' id="estado" name="estado" class="" size="32" /></td>
</tr>
<tr>
<td  align='right' valign='top'>Producto<span class='rojo'></span>:</td>
<td width='154' valign='top'> 	 <input type='text' id="producto" name="producto"  class="" size="20" />
</td>
<td   align='right' valign='top'>Precio de Costo<span class='rojo'></span>:</td>
<td colspan="2" valign='top'><input type='text' id="preciocosto" name="preciocosto"  class="number" size="20" />
</td>
</tr>
<tr>
<td align='right' valign='top'>Precio de Venta<span class='rojo'></span>:</td>
<td width='154' valign='top'><input type='text' id="precioventa" name="precioventa"  class="number" size="20" />
</td>
<td  align='right' valign='top'>Precio de Venta Final<span class='rojo'></span>:</td>
<td colspan="2" valign='top'><input type='text' id="precioventafinal" name="precioventafinal"  class="number" size="20" />
</td>
</tr>
<tr>
<td colspan='5' ><div align='left' class='masagua'> Los campos con <span class='rojo'>(*) </span>son requeridos:</div></td>
</tr>




<tr>
<td colspan='5' >
<div id='tabs'>
<ul  style='height:40px;'>
<li><a href='#tabs-1'>Producto Restaurant</a></li>
<li></li>
</ul>
<div id='tabs-1'>
<table width='400' border='0' align='center'>
<tr>
<td width="117"  align="right">Tiempo de Entrega:</td>
<td width="136"  align="left"><input type='text' id="tiempodeentrega" name="tiempodeentrega" class="date" size="15" /></td>
<td width='175'>
<td width='78'>
<tr>
  <td colspan="2" align="left">Descripción:</td>
  <td width='175'>Preparación:  
  <td width='78'>  
<tr>
  <td colspan="2" align="right"><textarea name="descripcion" id="descripcion" cols="40" rows="8"></textarea></td>
  <td colspan="2"><textarea name="preparacion" id="preparacion" cols="40" rows="8"></textarea>
<tr>
  <td colspan="2" align="right">&nbsp;</td>
  <td width='175'>  
  <td width='78'>  
<tr>
  <td colspan="2" align="left">Observaciones:</td>
  <td width='175'>
  <td width='78'>  
<tr>
<td colspan="2" align="right"><textarea name="observaciones" id="observaciones" cols="40" rows="8"></textarea></td>
<td colspan="2">
<tr>
<td colspan="2" align="right">&nbsp;</td>
<td width='175'>&nbsp;</td>
<td width='78'>&nbsp;</td>
</tr>
</table>
</div>
<div id='tabs-2'>
 <!--Otros Datos-->
</div>
</div> 
</td>
</tr>
</table>
</form>


<br />
<br />



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