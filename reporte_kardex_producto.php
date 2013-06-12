<?php 
include("conexion.php");
$db = new MySQL();

tieneacceso('servicio');
$transaccion = "insertar";

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
<script src="js/jquery.validate.js"></script>
<script src="autocompletar/FuncionesUtiles.js"></script>
<script src="grupo/Ngrupo.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>

<script>	$(function() {	$( '#tabs' ).tabs();	});  </script>


<script>
$(document).ready(function()
{
$("#fechainicio").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});
$("#fechafin").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});

$("#formValidado").validate({});
});
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
<table style="width:75%;top:15px;margin: 0 auto;position:relative;" border="0">
 <tr>
 <td>

<form id='formValidado' name='formValidado' method='post' action='' enctype='multipart/form-data'>
<table width='77%' border='0' align='center' cellpadding='4' cellspacing='3' class="contenedorPrincipal">
<tr >
<td colspan='5' align='center' >
<div class="tituloFondo">
  <div class="tituloSobreponeFondo">
     KARDEX POR PRODUCTO</div>
</div>
</td>
</tr>
<tr>
<td colspan='2' >&nbsp;</td>
<td width="180">&nbsp;</td>
<td align='right'>&nbsp;</td>   
<td>&nbsp;</td>
</tr>
<tr>
<td colspan='5'> <hr /></td>
</tr>
<tr>
<td colspan='4'>
<input name='enviar' type='button' class='botonseycon' id='enviar' value='Guardar' onclick="enviarDetalle();"/></td>
<td width='146' align='center'></td>
</tr>
<tr>
<td width='44' align='right' valign='top'>&nbsp;</td>
<td colspan="2" valign='top'>&nbsp;</td>
<td width='63' valign='top'  align='right'>&nbsp;</td>
<td width='146' align='left' valign='top' >&nbsp;</td>
 </tr>
<tr>
  <td align='right' valign='top'>&nbsp;</td>
  <td valign='top'>Producto:</td>
  <td valign='top'>
  <select id="almacen" >
    <option value="0" selected="selected" style="width:120px;">-- Seleccione --</option>
    <?php
	  $producto = "select idproducto,left(nombre,25) from producto where estado = 1;";
	  $db->imprimirCombo($producto);
	?>
  </select>
  </td>
  <td valign='top'>Desde:</td>
  <td align='left'><input type='text' id="fechainicio" name="fechainicio" class="date" size="12" /></td>
</tr>
<tr>
  <td align='right' valign='top'>&nbsp;</td>
  <td valign='top'>Sucursal:</td>
  <td valign='top'><select name="sucursal" id="sucursal" style="width:180px;">
   <option value="0" selected="selected">-- Seleccione --</option>   
    <?php
      $sql = "select idsucursal,left(nombrecomercial,25)as 'nombrecomercial' from sucursal where estado=1;";
      $db->imprimirCombo($sql);
    ?>     
  </select></td>
  <td valign='top'>Hasta:</td>
  <td align='left'><input type='text' id="fechafin" name="fechafin" class="date" size="12" /></td>
</tr>
<tr>
  <td align='right' valign='top'>&nbsp;</td>
  <td valign='top'>Almacen:</td>
  <td valign='top'>
  <select id="almacen" >
     <option value="0" selected="selected" style="width:120px;">-- Seleccione --</option>
     <?php
	   $almacen = "select idalmacen, left(nombre,20) from almacen where estado=1";
	   $db->imprimirCombo($almacen);
	 ?>
  </select>
  </td>
  <td valign='top'>&nbsp;</td>
  <td align='center'></td>
</tr>
<tr>
  <td align='right' valign='top'>&nbsp;</td>
  <td width="88" valign='top'>&nbsp;</td>
  <td valign='top'>&nbsp;</td>
  <td valign='top'>&nbsp;</td>
  <td align='center'></td>
</tr>
<tr>
  <td colspan="5" >&nbsp;</td>
  </tr>
<tr>
  <td colspan='5' >&nbsp;</td>
</tr>




<tr>
<td colspan='5' >
 
</td>
</tr>
</table>
</form>

</td></tr></table>

<br />
<br />
<script>

</script>



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