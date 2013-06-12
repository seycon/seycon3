<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
	include_once('conexion.php');  
	$db = new MySQL();
	
	if (!isset($_SESSION['softLogeoadmin'])) {
	    header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	if (!$db->tieneAccesoFile($estructura['Administracion'],'Parámetros Sistema','nuevo_configuracionPrecios.php')){
	    header("Location: cerrar.php");	
	}

	function filtro($cadena)
	{
	    return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
	}
	
	if (isset($_POST['idconfiguracion'])) {
		$sql = "select *from configuracionprecios";
		$n = $db->getnumRow($sql);			
		if ($n == 0) {
		   $sql = "INSERT INTO configuracionprecios values(null,'".filtro($_POST['textoprecio1'])."','"
		   .filtro($_POST['textoprecio2'])."','".filtro($_POST['textoprecio3'])."','".
		   filtro($_POST['textoprecio4'])."');";
		} else {
			$sql = "update configuracionprecios set textoprecio1='".filtro($_POST['textoprecio1'])
			."',textoprecio2='".filtro($_POST['textoprecio2'])."',
			textoprecio3='".filtro($_POST['textoprecio3'])."',textoprecio4='".filtro($_POST['textoprecio4'])."';";
		}		
		mysql_query($sql);
		header("Location: nuevo_configuracionPrecios.php?msj#t3");	
	}	
	$sql = "select *from configuracionprecios";
	$datoPrecios = $db->arrayConsulta($sql);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templateadministracion.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<title>Sistema Empresarial y Contable – Seycon 3.0</title>
<script type="text/javascript" src="js/submenus.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<link rel="stylesheet" href="css/form.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script async="async" src="js/jquery.validate.js"></script>


<script>
$(document).ready(function()
{
$("#fecha").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});

$("#formValidado").validate({});
});

 var leerURL = function(){
	 var param =  location.search;
	 if (param.length>0){
	 	 document.getElementById("mensajeRespuesta").innerHTML = "Sus Datos Fueron Guardados Correctamente."; 
	 }
 }
  
 document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;

   if(tecla == 113){ //F2
    $$("enviar").click();
	// document.formValidado.submit();  
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

<div class="menuTituloFormulario"> Administración > Parámetros Sistema </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Administracion'];
   $privilegios = $db->getOpciones($menus, "Parámetros Sistema"); 
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
<form id='formValidado' name='formValidado' method='post' action='nuevo_configuracionPrecios.php' enctype='multipart/form-data'>
<div class="contemHeaderTop">
  <table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;
    <input name='enviar' type='submit' class='botonNegro' id='enviar' value='Guardar [F2]' />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </td>
    <td><input type="hidden"  name="idconfiguracion" id="idconfiguracion" value="1"/></td>
<td colspan="3" align='right'><table width="356" border="0">
  <tr>
   <td width="142" colspan="2" align="center"><strong> Configuración de Precios</strong></td>
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
<table width='100%' border='0' align='center' cellpadding='4' cellspacing='3' >
<tr>
<td colspan='9' align='center' ></td>
</tr>
<tr>
<td colspan='7' >
<div id="mensajeRespuesta" class="mensajeRespuesta"></div></td>
<td></td>
</tr>
<tr>
<td colspan="2">  
<td width="125" align="right">&nbsp;</td>
<td width="125" align="right" valign="middle">&nbsp;</td>
<td colspan="3" align="left" valign="middle">&nbsp;</td>
<td width='229' rowspan='5' align='center'></td>
</tr>
<tr>
  <td colspan='8' ><table width="90%" border="0" align="center" class="bordeContenido">
    <tr>
      <td width="40%">&nbsp;</td>
      <td width="45%">&nbsp;</td>
      <td width="5%">&nbsp;</td>
      <td width="10%">&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Precio 1<span class="rojo">*</span>:</td>
      <td><input type='text' id="textoprecio1" name="textoprecio1" class="required"  
      style="width:50%" value= '<?php echo $datoPrecios['textoprecio1']?>'/></td>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Precio 2 : </td>
      <td><input type='text' id="textoprecio2" name="textoprecio2"   
      style="width:50%"  value= '<?php echo $datoPrecios['textoprecio2']?>'/></td>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Precio 3 : </td>
      <td><input type='text' id="textoprecio3"  name="textoprecio3"  
      style="width:50%" value='<?php echo $datoPrecios['textoprecio3']?>'/></td>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Precio 4 :</td>
      <td><input type='text' id="textoprecio4"  name="textoprecio4"  
      style="width:50%" value='<?php echo $datoPrecios['textoprecio4']?>'/></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table></td>
</tr>
<tr>
<td colspan='8' >

</td>
</tr>
</table>
</form>
</div>
</td></tr></table>
<script>
    leerURL();
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