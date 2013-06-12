<?php
   session_start();
   include_once('conexion.php');  
   $db = new MySQL();
   if (!isset($_SESSION['softLogeoadmin'])) {
		header("Location: index.php");	
   }
   $estructura = $_SESSION['estructura'];
   if (!$db->tieneAccesoFile($estructura['Agenda'],'Reporte Financiero','nuevo_reporteestadoresultado.php')) {
		header("Location: cerrar.php");	
   }

   $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
   $tc = $db->getCampo('dolarcompra',$sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<link rel="stylesheet" href="estadoresultados/estadoresultado.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="estadoresultados/Nresultados.js"></script>
<script src="lib/Jtable.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/jquery.ui.core.js"></script>
<script src="js/ui/jquery.ui.widget.js"></script>
<script src="js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script src="js/jquery.validate.js"></script>

<script>	$(function() {	$( '#tabs' ).tabs();	});  </script>

<script>
$(document).ready(function()
{
$("#fecha").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});

$("#desde").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});

$("#hasta").datepicker({
showOn: "button",
buttonImage: "css/images/calendar.gif",
buttonImageOnly: true,
dateFormat: 'dd/mm/yy'
});

$("#formValidado").validate({});
});
</script>

<script>
  function checkclick(id){ if (document.getElementById(id).checked) document.getElementById(id).value=1; else document.getElementById(id).value=0;}
</script>






<style type="text/css">
#formValidado table tr td #tabs ul li table {
	font-size: 1pc;
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
<table width='100%' border='0' align='center' cellpadding='4' cellspacing='3'>
<tr >
<td colspan='9' align='center' >

<table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;&nbsp;&nbsp;
    <input name='enviar' type='button' onclick="verReporte();" class='botonseycon' id='enviar' value='Reporte[PDF]' />
 
 
 </td>
<td></td>
<td colspan="3" align='center'>

<table width="100%" border="0"  align="center">
  <tr>
    <td align="center">Sucursal</td>
    <td align="center">Desde</td>
    <td align="center">Hasta</td>
    <td align="center">Auxiliar</td>
    <td align="center">Logo</td>
    <td align="center">Moneda</td>
  </tr>
  <tr>
    <td width="24%" align="center">
    <select name="sucursal" id="sucursal" style="width:130px; background:#FFF;border:solid 1px #999;" >
    <option value="-1">Consolidado</option>
      <?php
		    $sucursal = "select idsucursal, left(nombrecomercial,20) from sucursal where estado=1";			
	        $db->imprimirCombo($sucursal);			
	    ?>
    </select></td>
    <td width="15%" align="center"><input type='text' id="desde" name="desde" 
    value="<?php 
          $fecha = explode("/", date("d/m/Y"));
		  $fechaInicial = "01/".$fecha[1]."/".$fecha[2];
		  echo $fechaInicial;
    ?>"
 class="date" size="9" /></td>
    <td width="15%" align="center"><input type='text' id="hasta" name="hasta" 
value="<?php echo date("d/m/Y");?>"
 class="date" size="9" /></td>
    <td width="15%" align="center"><input type="checkbox" name="auxiliares2" id="auxiliares2" onchange="mostrarAuxiliares()" style="display:block" checked="checked"/></td>
    <td width="12%" align="center"><input type="checkbox" name="logo" id="logo" onchange="mostrarAuxiliares()" style="display:block" checked="checked"/></td>
    <td width="19%" align="center"><select name="moneda" id="moneda" onchange="limpiarDetalle();" style="width:100px;background:#FFF;border:solid 1px #999;">
      <?php          
		  echo "<option value='Bolivianos' selected='selected'>Bolivianos</option>";	
		  echo "  <option value='Dolares'>Dolares</option>";	
      ?>
    </select></td>
  </tr>
</table>


</td> 
  </tr>
  <tr><td colspan="6"></td> </tr>
</table>


</td>
</tr>

<tr>
  <td width='2' align='right' valign='top'>&nbsp;</td>
  <td width='104' valign='top'>Mes<span class='rojo'></span>:
  <select name="mes" id="mes">
        <option value="1">Enero</option>
        <option value="2">Febrero</option>
        <option value="3">Marzo</option>
        <option value="4">Abril</option>
        <option value="5">Mayo</option>
        <option value="6">Junio</option>
        <option value="7">Julio</option>
        <option value="8">Agosto</option>
        <option value="9">Septiembre</option>
        <option value="10">Octubre</option>
        <option value="11">Noviembre</option>
        <option value="12">Diciembre</option>
   </select>
 
 </td>
  <td width='83' align="center" valign='top'>Año<span class='rojo'></span>:
  <select name="anio" id="anio" style="width:60px;">
       <?php	  
	    $anio = date("Y");
	    for($i = 2010; $i <= 2080; $i++) {
			if ($anio == $i) {
				echo  "<option value='$i' selected='selected'>$i</option>";		
			} else {
                echo  "<option value='$i' >$i</option>";		
			}
		}
       ?>
      </select></td>
 
  <td width='64' align="right" valign='top'>Sucursal:</td>
  <td width='137' valign='top'>
  <select name="sucursal2" id="sucursal2" style="width:130px; background:#FFF;border:solid 1px #999;">
  <?php
      $sucursal = "select idsucursal, left(nombrecomercial,20) from sucursal where estado=1";			
      $db->imprimirCombo($sucursal);			
  ?>
  </select></td>
  <td width='63' valign='top' align="center">T.C.: <?php echo $tc;?></td>
  <td width='87'  align='right' valign='top'>Ocultar Auxiliares:</td>
  <td width='20' align="left" valign='top'><input type="checkbox" name="auxiliares" id="auxiliares" onchange="mostrarAuxiliares()" style="display:block" checked="checked"/></td>
  <td width="90" align='left'>
  <input name='enviar2' type='button' onclick="getConsulta();" class='botonNegro' id='enviar2' value='Consultar' /></td>
</tr>
<tr>
  <td colspan='9' >
  <div id='tabs'>
  <ul  style='height:40px;'>
  <li><a href='#tabs-1' class="texto">Ingresos</a></li>
  <li><a href='#tabs-2' class="texto">Egresos</a></li>
  </ul>
  <div id='tabs-1' style="position:relative;height:400px;">
  
  <table width='100%' border='0' align='center'>
  <tr>
    <td width="285" align="right" valign="top">
    <div class="contenidoReporte">
    <table width="100%" border="0" id="tabla" style="margin-top:0px;" cellspacing="0" cellpadding="0">
            <tr>
              <th width="7" align="center" style="display:none">Nivel</th>
              <th width="146" align="center" class="cabeceraReporte">Cuenta</th>
              <th width="20" align="center" class="cabeceraReporte">Moneda</th>
              <th width="72" align="center" class="cabeceraReporte">Monto</th>
            </tr>
            <tbody id="detalleDisponible">
            
            </tbody>
    </table>
    </div>
    </td>
    <td width="10" valign="top">
    <td width="267" valign="top">
    <div class="contenidoReporte">
    <table width="100%" border="0" id="tabla2" style="margin-top:0px;" cellspacing="0" cellpadding="0">
      <tr class="cabeceraReporte">
        <th width="98%" align="center" colspan="2">Grafico</th>
      </tr>
      <tbody id="detalleGrafico">
        
      </tbody>
    </table>   
    </div>
    
     
      </table>
  
  </div>
  
  <div id='tabs-2' style="position:relative;height:400px;">
  
  <table width='100%' border='0' align='center'>
  <tr>
    <td width="285" align="right" valign="top">
    <div class="contenidoReporte">
    <table width="100%" border="0" id="tabla" style="margin-top:0px;" cellspacing="0" cellpadding="0">
            <tr>
              <th width="7" align="center" style="display:none">Nivel</th>
              <th width="146" align="center" class="cabeceraReporte">Cuenta</th>
              <th width="20" align="center" class="cabeceraReporte">Moneda</th>
              <th width="72" align="center" class="cabeceraReporte">Monto</th>
            </tr>
            <tbody id="detalleDisponible2">
            
            </tbody>
    </table>
    </div>
    </td>
    <td width="10" valign="top">
    <td width="267" valign="top">
    <div class="contenidoReporte">
    <table width="100%" border="0" id="tabla2" style="margin-top:0px;" cellspacing="0" cellpadding="0">
      <tr class="cabeceraReporte">
        <th width="98%" align="center" colspan="2">Grafico</th>
      </tr>
      <tbody id="detalleGrafico2">
        
      </tbody>
    </table>   
    </div>
    
     
      </table>
  
  </div>
  </div>
  
  
  

  </td>
</tr>
</table>
</form>
</div>
</td></tr></table>

<BR />
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