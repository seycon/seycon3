<?php
 session_start();
 include_once('conexion.php');  
 $db = new MySQL();
 if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: index.php");	
 }
 $estructura = $_SESSION['estructura'];
  if (!$db->tieneAccesoFile($estructura['Agenda'],'Reporte Financiero','nuevo_balancesumassaldos.php')){
   header("Location: cerrar.php");	
  }

 $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
 $tc = $db->getCampo('dolarcompra',$sql);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templateagenda.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<title>Sistema Empresarial y Contable – Seycon 3.0</title>
<script type="text/javascript" src="js/submenus.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Sistema Empresarial y Contable – Seycon 2011</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->



<!-- TinyMCEaumentar un simbolo de mayor para activar el editor de texto avanzado TinyMCE-->


<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link rel="stylesheet" href="css/estilos.css" type="text/css"/>
<link rel="stylesheet" href="cuentaporcobrar/cuentacobrar.css" type="text/css"/>
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
$("#fecha2").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});

$("#fecha3").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});

$("#formValidado").validate({});
});
</script>

<script>  

 var $$ = function(id) {
	return document.getElementById(id); 
 }
  
 var viewReporte = function() {
	window.open('balance/reporte_sumasSaldos.php?desde='+$$('fecha2').value+'&hasta='+$$("fecha3").value,'target:_blank');	
 }
    
</script>






<style type="text/css">
#formValidado table tr td #tabs ul li table {
	font-size: 1pc;
}
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
  
  <div id="overlay_vendido" class="overlays"></div> 
  <div id="overlay" class="overlays"></div>
  <div id="modal_mensajes" class="modal_mensajes">
  <div class="modal_cabecera">
     <div id="modal_tituloCabecera" class="modal_tituloCabecera">Advertencia</div>
     <div class="modal_cerrar"><img src="iconos/borrar2.gif" width="12" height="12" style="cursor:pointer" onclick="closeMensaje()"></div>
  </div>
  <div class="modal_icono_modal"><img src="iconos/alerta.png" width="24" height="24"></div>
  <div id="modal_contenido" class="modal_contenido">No Existen Pagos Realizados</div>
  <div class="modal_boton1"><input type="button" value="Aceptar" class="boton_modal" onclick="closeMensaje()"/></div>
</div>
  
  
 
  
  
<div class="cabeceraFormulario">

<div class="menuTituloFormulario"> Agenda > Reporte Financiero</div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Agenda'];
   $privilegios = $db->getOpciones($menus, "Reporte Financiero"); 
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
<form id='formValidado' name='formValidado' method='post' action='' enctype='multipart/form-data'>
<table width='100%' border='0' align='center' cellpadding='4' cellspacing='3' >
<tr >
<td colspan='6' align='center' >

<table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;&nbsp;&nbsp;
    <input name='enviar2' type='button' onclick="viewReporte();" class='botonseycon' id='enviar2' value='Consultar' />
    
 
 </td>
<td width="37%"></td>
<td width="43%" colspan="3" align='center'>
<table width="315" border="0">
          <tr>
            <td width="204" align="center" colspan="2">Balance de Sumas y Saldos</td>
          </tr>
          <tr>
            <td colspan="2" align="center">Los campos con <span class='rojo'>(*) </span>son requeridos.</td>
            <input type="hidden" id="tc" value="<?php echo $tc?>" />
          </tr>
        </table>

    

</td> 
  </tr>
  <tr><td colspan="6"></td> </tr>
</table>


</td>
</tr>
<tr>
  <td width="600" colspan='2' >&nbsp;</td>
  <td width="128">&nbsp;</td>
  <td colspan="2" align='right'>&nbsp;</td>
  <td width="103">&nbsp;</td>
</tr>
<tr>
  <td colspan='6' >
    
  <table width="608" border="0" align="center" class="bordeContenido">
    <tr>
      <td width="254" align="right"></td>
      <td width="136"></td>
      </tr>
    <tr>
      <td align='right' valign='middle'>&nbsp;</td>
      <td valign='top'>&nbsp;</td>
      <td  align='right' valign='middle'>&nbsp;</td>
      <td align="left" valign='top'>&nbsp;</td>
      <td align="left" valign='middle'>&nbsp;</td>
      <td align='left'></td>
      </tr>
    <tr>
      <td align='right' valign='middle'>Desde<span class="rojo">*</span>:</td>
      <td valign='top'><input type='text' id="fecha2" name="fecha2" 
value="<?php echo date("d/m/Y");?>"  class="date" size="10" /></td>
      <td  align='right' valign='middle'>&nbsp;</td>
      <td align="left" valign='top'>&nbsp;</td>
      <td align="left" valign='middle'>&nbsp;</td>
      <td align='left'></td>
      </tr>
    <tr>
      <td align='right' valign='middle' height="10"></td>
      <td valign='top'></td>
      <td  align='right' valign='middle'></td>
      <td align="left" valign='top'></td>
      <td align="left" valign='middle'></td>
      <td align='left'></td>
      </tr>
    <tr>
      <td width='254' align='right' valign='middle'>Hasta<span class="rojo">*</span>:</td>
      <td width='136' valign='top'><input type='text' id="fecha3" name="fecha3" 
value="<?php echo date("d/m/Y");?>"  class="date" size="10" />
        </td>
      <td width='23'  align='right' valign='middle'>&nbsp;</td>
      <td width='21' align="left" valign='top'>&nbsp;</td>
      <td width='102' align="left" valign='middle'>&nbsp;</td>
      <td width="26" align='left'></td>
  </tr>
    <tr>
      <td align='right' valign='middle'>&nbsp;</td>
      <td valign='top'>&nbsp;</td>
      <td  align='right' valign='middle'>&nbsp;</td>
      <td align="left" valign='top'>&nbsp;</td>
      <td align="left" valign='middle'>&nbsp;</td>
      <td align='left'></td>
      </tr>
  </table>
    
    </td>
</tr>
</table>
</form>
</div>
</td></tr></table>

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